<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Node;
use App\Services\PlacementService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PDO;

class MatrixController extends Controller
{
    public function __construct(
        private PlacementService $placementService
    ) {}

    /**
     * Display matrix page.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->node) {
            // User chưa được đặt trong matrix, hiển thị trang chờ
            return view('matrix.waiting', [
                'user' => $user,
                'message' => 'Bạn chưa được đặt trong ma trận. Vui lòng liên hệ admin để được hỗ trợ.'
            ]);
        }

        $stats = $this->placementService->getMatrixStats($user);

        return view('matrix.index', compact('user', 'stats'));
    }

    /**
     * Get current user's matrix information.
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = User::find(1); // Use ID 1 for admin user
            if (!$user) {
                return response()->json([
                    'message' => 'Không tìm thấy admin user.',
                ], 404);
            }

            $node = $user->node;

            if (!$node) {
                return response()->json([
                    'message' => 'Người dùng chưa được đặt trong ma trận.',
                ], 404);
            }
            
            $stats = $this->placementService->getMatrixStats($user);
            $uplineChain = $this->placementService->getUplineChain($user);
            
            return response()->json([
                'node' => [
                    'id' => $node->id,
                    'depth' => $node->depth,
                    'position' => $node->position,
                    'parent_id' => $node->parent_id,
                ],
                'stats' => $stats,
                'upline_count' => count($uplineChain),
                'upline' => array_map(function ($uplineUser) {
                    return [
                        'id' => $uplineUser->id,
                        'fullname' => $uplineUser->fullname,
                        'email' => $uplineUser->email,
                        'referral_code' => $uplineUser->referral_code,
                    ];
                }, $uplineChain),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get user matrix info', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy thông tin ma trận.',
            ], 500);
        }
    }
    
    /**
     * Get matrix tree for a user.
     */
    public function tree(Request $request, ?User $user = null): JsonResponse
    {
        try {
            $targetUser = $user ?? $request->user();
            $depth = (int) $request->query('depth', 1);
            
            if (!$targetUser) {
                return response()->json([
                    'message' => 'Người dùng không tồn tại.',
                ], 404);
            }
            
            $node = $targetUser->node;
            
            if (!$node) {
                return response()->json([
                    'message' => 'Người dùng chưa được đặt trong ma trận.',
                ], 404);
            }
            
            $tree = $this->buildTreeData($node, $depth);
            
            return response()->json([
                'user' => [
                    'id' => $targetUser->id,
                    'fullname' => $targetUser->fullname,
                    'email' => $targetUser->email,
                    'referral_code' => $targetUser->referral_code,
                ],
                'tree' => $tree,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get matrix tree', [
                'error' => $e->getMessage(),
                'user_id' => $user?->id ?? $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy cây ma trận.',
            ], 500);
        }
    }
    
    /**
     * Get matrix statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $user = User::where('role', 'admin')->first();
            if (!$user) {
                return response()->json([
                    'message' => 'Không tìm thấy admin user.',
                ], 404);
            }

            $stats = $this->placementService->getMatrixStats($user);
            
            // Get additional statistics
            $totalUsers = User::count();
            $activeUsers = User::where('active', true)->count();
            $totalNodes = \App\Models\Node::count();
            
            return response()->json([
                'user_stats' => $stats,
                'system_stats' => [
                    'total_users' => $totalUsers,
                    'active_users' => $activeUsers,
                    'total_nodes' => $totalNodes,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get matrix stats', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy thống kê ma trận.',
            ], 500);
        }
    }
    
    /**
     * Get paginated downline list for current user (optimized for large datasets).
     */
    public function downline(Request $request): JsonResponse
    {
        try {
            $adminId = 1;
            $pdo = DB::connection()->getPdo();

            // Check if admin node exists
            $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM nodes WHERE id = ? AND user_id = ?');
            $stmt->execute([$adminId, $adminId]);
            $nodeCheck = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($nodeCheck['count'] == 0) {
                return response()->json([
                    'message' => 'Không tìm thấy admin node.',
                ], 404);
            }

            // Get node data
            $stmt = $pdo->prepare('SELECT * FROM nodes WHERE id = ?');
            $stmt->execute([$adminId]);
            $nodeData = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Parameters for pagination and filtering
            $page = (int) $request->query('page', 1);
            $perPage = min((int) $request->query('per_page', 50), 100);
            $search = $request->query('search', '');
            $sortBy = $request->query('sort_by', 'position');
            $sortOrder = $request->query('sort_order', 'asc');

            $searchParam = $search ? "%{$search}%" : '';
            $searchCondition = $search ? "AND (u.fullname LIKE ? OR u.email LIKE ? OR u.referral_code LIKE ?)" : '';

            $countStmt = $pdo->prepare("
                SELECT COUNT(*) as total
                FROM nodes n
                INNER JOIN users u ON n.user_id = u.id
                WHERE n.parent_id = ?
                {$searchCondition}
            ");

            if ($search) {
                $countStmt->execute([$nodeData['id'], $searchParam, $searchParam, $searchParam]);
            } else {
                $countStmt->execute([$nodeData['id']]);
            }

            $totalResult = $countStmt->fetch(\PDO::FETCH_ASSOC);
            $total = $totalResult['total'] ?? 0;

            $offset = ($page - 1) * $perPage;
            $orderDirection = $sortOrder === 'desc' ? 'DESC' : 'ASC';

            $allowedSortFields = ['position', 'fullname', 'email', 'created_at'];
            $sortField = in_array($sortBy, $allowedSortFields) ? $sortBy : 'position';

            $mainStmt = $pdo->prepare("
                SELECT
                    n.id,
                    n.user_id,
                    u.fullname,
                    u.email,
                    u.referral_code,
                    u.active as is_active,
                    n.position,
                    n.depth,
                    n.created_at
                FROM nodes n
                INNER JOIN users u ON n.user_id = u.id
                WHERE n.parent_id = ?
                {$searchCondition}
                ORDER BY {$sortField} {$orderDirection}
                LIMIT ? OFFSET ?
            ");

            if ($search) {
                $mainStmt->execute([$nodeData['id'], $searchParam, $searchParam, $searchParam, $perPage, $offset]);
            } else {
                $mainStmt->execute([$nodeData['id'], $perPage, $offset]);
            }

            $downlines = $mainStmt->fetchAll(\PDO::FETCH_ASSOC);

            $formattedDownlines = [];
            foreach ($downlines as $downline) {
                // Ensure UTF-8 encoding
                $fullname = mb_convert_encoding($downline['fullname'] ?? '', 'UTF-8', 'UTF-8');
                $email = mb_convert_encoding($downline['email'] ?? '', 'UTF-8', 'UTF-8');
                $referralCode = mb_convert_encoding($downline['referral_code'] ?? '', 'UTF-8', 'UTF-8');
                
                $formattedDownlines[] = [
                    'id' => (int) $downline['id'],
                    'user_id' => (int) $downline['user_id'],
                    'fullname' => $fullname,
                    'email' => $email,
                    'referral_code' => $referralCode,
                    'position' => (int) $downline['position'],
                    'depth' => (int) $downline['depth'],
                    'created_at' => $downline['created_at'],
                    'is_active' => (bool) $downline['is_active'],
                    'avatar' => mb_substr($fullname ?: $email, 0, 1),
                ];
            }

            return response()->json([
                'downline' => $formattedDownlines,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => ceil($total / $perPage),
                    'from' => ($page - 1) * $perPage + 1,
                    'to' => min($page * $perPage, $total),
                    'has_more_pages' => $page < ceil($total / $perPage),
                ],
                'filters' => [
                    'search' => $search,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                ],
                'summary' => [
                    'total_downlines' => $total,
                    'active_downlines' => (function() use ($pdo, $nodeData, $search, $searchParam, $searchCondition) {
                        $stmt = $pdo->prepare("
                            SELECT COUNT(*) as count
                            FROM nodes n
                            INNER JOIN users u ON n.user_id = u.id
                            WHERE n.parent_id = ? AND u.active = 1
                            {$searchCondition}
                        ");
                        if ($search) {
                            $stmt->execute([$nodeData['id'], $searchParam, $searchParam, $searchParam]);
                        } else {
                            $stmt->execute([$nodeData['id']]);
                        }
                        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                        return $result['count'] ?? 0;
                    })(),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get downline list', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Không thể lấy danh sách downline.',
            ], 500);
        }
    }
    
    /**
     * Build tree data structure.
     */
    private function buildTreeData($node, int $maxDepth): array
    {
        $data = [
            'id' => $node->id,
            'user' => [
                'id' => $node->user->id,
                'fullname' => $node->user->fullname,
                'email' => $node->user->email,
                'referral_code' => $node->user->referral_code,
            ],
            'depth' => $node->depth,
            'position' => $node->position,
            'children' => [],
        ];
        
        if ($maxDepth > 0) {
            $children = $node->children()->with('user')->orderBy('position')->get();
            
            foreach ($children as $child) {
                $data['children'][] = $this->buildTreeData($child, $maxDepth - 1);
            }
        }
        
        return $data;
    }
    
    /**
     * Get matrix visualization data.
     */
    public function visualization(Request $request): JsonResponse
    {
        try {
            $user = User::find(1); // Use ID 1 for admin user
            if (!$user) {
                return response()->json([
                    'message' => 'Không tìm thấy admin user.',
                ], 404);
            }

            $node = $user->node;

            if (!$node) {
                return response()->json([
                    'message' => 'Người dùng chưa được đặt trong ma trận.',
                ], 404);
            }
            
            $config = config('mlm');
            $width = $config['width'];
            $maxDepth = min(1, $config['max_depth']); // Chỉ hiển thị 1 tầng

            // For large datasets, limit visualization to prevent performance issues
            $maxVisualizationChildren = 10; // Chỉ hiển thị tối đa 10 children trong visualization

            $visualization = $this->buildVisualizationData($node, $width, $maxDepth, $maxVisualizationChildren);
            
            return response()->json([
                'visualization' => $visualization,
                'config' => [
                    'width' => $width,
                    'max_depth' => $maxDepth,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get matrix visualization', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy dữ liệu hiển thị ma trận.',
            ], 500);
        }
    }
    
    /**
     * Build visualization data for matrix display.
     */
    private function buildVisualizationData($node, int $width, int $maxDepth, int $maxChildren = 10): array
    {
        // Get sponsor information
        $sponsor = null;
        if ($node->user->referred_by) {
            $sponsorUser = User::find($node->user->referred_by);
            if ($sponsorUser) {
                $sponsor = [
                    'id' => $sponsorUser->id,
                    'fullname' => $sponsorUser->fullname,
                    'email' => $sponsorUser->email,
                    'referral_code' => $sponsorUser->referral_code,
                ];
            }
        }

        $data = [
            'id' => $node->id,
            'user' => [
                'id' => $node->user->id,
                'fullname' => $node->user->fullname,
                'email' => $node->user->email,
                'referral_code' => $node->user->referral_code,
            ],
            'sponsor' => $sponsor,
            'depth' => $node->depth,
            'position' => $node->position,
            'children' => array_fill(0, $width, null),
        ];

        if ($maxDepth > 0) {
            $children = $node->children()
                ->with('user')
                ->orderBy('position')
                ->limit($maxChildren) // Giới hạn số children hiển thị để tránh performance issues
                ->get();

            $totalChildren = $node->children()->count();
            $data['children_info'] = [
                'displayed' => min($maxChildren, $totalChildren),
                'total' => $totalChildren,
                'has_more' => $totalChildren > $maxChildren,
            ];

            foreach ($children as $index => $child) {
                if ($index < $width) {
                    $data['children'][$index] = $this->buildVisualizationData($child, $width, $maxDepth - 1, $maxChildren);
                }
            }
        }

        return $data;
    }
}