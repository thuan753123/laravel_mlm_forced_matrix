<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PlacementService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
            $user = $request->user();
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
            $depth = (int) $request->query('depth', 2);
            
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
            $user = $request->user();
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
     * Get downline for current user.
     */
    public function downline(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $maxDepth = (int) $request->query('depth', 2); // Chỉ hiển thị 2 tầng
            
            $downline = $this->placementService->getDownline($user, $maxDepth);
            
            return response()->json([
                'downline' => $downline,
                'count' => count($downline),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get downline', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
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
            $user = $request->user();
            $node = $user->node;
            
            if (!$node) {
                return response()->json([
                    'message' => 'Người dùng chưa được đặt trong ma trận.',
                ], 404);
            }
            
            $config = config('mlm');
            $width = $config['width'];
            $maxDepth = min(1, $config['max_depth']); // Chỉ hiển thị 1 tầng
            
            $visualization = $this->buildVisualizationData($node, $width, $maxDepth);
            
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
    private function buildVisualizationData($node, int $width, int $maxDepth): array
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
            $children = $node->children()->with('user')->orderBy('position')->get();

            foreach ($children as $index => $child) {
                if ($index < $width) {
                    $data['children'][$index] = $this->buildVisualizationData($child, $width, $maxDepth - 1);
                }
            }
        }

        return $data;
    }
}