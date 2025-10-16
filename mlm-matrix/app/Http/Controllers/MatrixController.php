<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Node;
use App\Services\PlacementService;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PDO;

class MatrixController extends Controller
{
    public function __construct(
        private PlacementService $placementService,
        private ExternalApiService $externalApiService
    ) {}

    /**
     * Display matrix page with API data.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->referral_code) {
            return view('matrix.waiting', [
                'user' => $user,
                'message' => 'Bạn chưa có mã giới thiệu. Vui lòng liên hệ admin để được hỗ trợ.'
            ]);
        }

        try {
            // Fetch users from API that match this user's referral code
            $apiUsers = $this->externalApiService->fetchUsersByReferralCode($user->referral_code);
            
            $stats = [
                'total_downline' => count($apiUsers),
                'active_downline' => count(array_filter($apiUsers, function($apiUser) {
                    return $apiUser['active'] ?? true;
                })),
                'depth' => 1,
                'position' => 0,
            ];

            return view('matrix.index', compact('user', 'stats', 'apiUsers'));
            
        } catch (\Exception $e) {
            Log::error('Failed to load matrix data from API', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'referral_code' => $user->referral_code,
            ]);

            return view('matrix.waiting', [
                'user' => $user,
                'message' => 'Không thể tải dữ liệu ma trận từ API. Vui lòng thử lại sau.'
            ]);
        }
    }

    /**
     * Get current user's matrix information from API.
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user || !$user->referral_code) {
                return response()->json([
                    'message' => 'Người dùng không có mã giới thiệu.',
                ], 404);
            }

            // Fetch users from API that match this user's referral code
            $apiUsers = $this->externalApiService->fetchUsersByReferralCode($user->referral_code);
            
            $stats = [
                'total_downline' => count($apiUsers),
                'active_downline' => count(array_filter($apiUsers, function($apiUser) {
                    return $apiUser['active'] ?? true;
                })),
                'depth' => 1,
                'position' => 0,
            ];
            
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'referral_code' => $user->referral_code,
                ],
                'stats' => $stats,
                'downline_count' => count($apiUsers),
                'downline' => array_map(function ($apiUser) {
                    return [
                        'id' => $apiUser['id'],
                        'fullname' => $apiUser['fullname'] ?? 'Unknown',
                        'email' => $apiUser['email'] ?? 'unknown@example.com',
                        'referral_code' => $apiUser['referralCode'] ?? '',
                        'referralCode' => $apiUser['referralCode'] ?? '',
                        'active' => $apiUser['active'] ?? true,
                        'plan' => $apiUser['plan'] ?? 'FREE',
                        'avatar_url' => $apiUser['avatarUrl'] ?? null,
                        'phone_number' => $apiUser['phoneNumber'] ?? null,
                    ];
                }, $apiUsers),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get user matrix info from API', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'referral_code' => $request->user()?->referral_code,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy thông tin ma trận từ API.',
            ], 500);
        }
    }
    
    /**
     * Get matrix tree for a user from API.
     */
    public function tree(Request $request, ?User $user = null): JsonResponse
    {
        try {
            $targetUser = $user ?? $request->user();
            
            if (!$targetUser || !$targetUser->referral_code) {
                return response()->json([
                    'message' => 'Người dùng không có mã giới thiệu.',
                ], 404);
            }

            // Fetch users from API that match this user's referral code
            $apiUsers = $this->externalApiService->fetchUsersByReferralCode($targetUser->referral_code);
            
            $tree = $this->buildApiTreeData($targetUser, $apiUsers);
            
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
            Log::error('Failed to get matrix tree from API', [
                'error' => $e->getMessage(),
                'user_id' => $user?->id ?? $request->user()?->id,
                'referral_code' => $user?->referral_code ?? $request->user()?->referral_code,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy cây ma trận từ API.',
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
     * Get paginated downline list for current user using external API.
     */
    public function downline(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            if (!$user || !$user->referral_code) {
                return response()->json([
                    'message' => 'Người dùng không có mã giới thiệu.',
                ], 404);
            }

            // Parameters for pagination and filtering
            $page = (int) $request->query('page', 1);
            $perPage = min((int) $request->query('per_page', 10), 100);
            $search = $request->query('search', '');

            // Fetch users from external API with pagination
            $result = $this->externalApiService->fetchUsersByReferralCodePaginated(
                $user->referral_code, 
                $page, 
                $perPage, 
                $search
            );

            return response()->json([
                'downline' => $result['data'],
                'pagination' => $result['pagination'],
                'filters' => [
                    'search' => $search,
                ],
                'summary' => $result['summary'],
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get downline list from API', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'referral_code' => $request->user()?->referral_code,
            ]);

            return response()->json([
                'message' => 'Không thể lấy danh sách downline từ API.',
            ], 500);
        }
    }
    
    /**
     * Build tree data structure from API data.
     */
    private function buildApiTreeData(User $user, array $apiUsers): array
    {
        $config = config('mlm');
        $maxWidth = $config['width']; // Maximum width for layout
        $actualCount = count($apiUsers); // Actual number of items to display
        
        $data = [
            'id' => $user->id,
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'referral_code' => $user->referral_code,
                'role' => $user->role,
                'active' => $user->active,
            ],
            'depth' => 0,
            'position' => 0,
            'children' => array_fill(0, $actualCount, null), // Initialize with actual count, not max width
        ];
        
        // Add API users as children in matrix positions
        foreach ($apiUsers as $index => $apiUser) {
            $data['children'][$index] = [
                'id' => $apiUser['id'],
                'user' => [
                    'id' => $apiUser['id'],
                    'fullname' => $apiUser['fullname'] ?? 'Unknown',
                    'email' => $apiUser['email'] ?? 'unknown@example.com',
                    'referral_code' => $apiUser['referral_code'] ?? '', // Map to referral_code for consistency
                    'referralCode' => $apiUser['referralCode'] ?? '', // Keep both for compatibility
                    'active' => $apiUser['active'] ?? true,
                    'plan' => $apiUser['plan'] ?? 'FREE',
                    'avatar_url' => $apiUser['avatarUrl'] ?? null,
                    'phone_number' => $apiUser['phoneNumber'] ?? null,
                    'role' => 'API_USER', // Mark as API user
                ],
                'depth' => 1,
                'position' => $index,
                'children' => array_fill(0, $maxWidth, null), // API users can have children too
                'is_api_user' => true, // Flag to identify API users
            ];
        }
        
        // Add summary info
        $data['children_info'] = [
            'total_children' => $actualCount,
            'filled_slots' => $actualCount,
            'available_slots' => 0, // No empty slots since we show exact count
            'has_more' => false, // Will be set by caller based on pagination
        ];
        
        return $data;
    }
    
    /**
     * Get matrix visualization data from API.
     */
    public function visualization(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user || !$user->referral_code) {
                return response()->json([
                    'message' => 'Người dùng không có mã giới thiệu.',
                ], 404);
            }

            // Pagination params for keeping tree consistent with downline list
            $page = (int) $request->query('page', 1);
            $perPage = min((int) $request->query('per_page', 10), 100);

            // Fetch users from API that match this user's referral code with pagination (same as downline list)
            $result = $this->externalApiService->fetchUsersByReferralCodePaginated(
                $user->referral_code,
                $page,
                $perPage,
                $request->query('search', '')
            );
            $apiUsers = $result['data'];
            
            $config = config('mlm');
            $width = $config['width'];
            $maxDepth = min(1, $config['max_depth']); // Chỉ hiển thị 1 tầng

            // Build visualization data from API
            $visualization = $this->buildApiTreeData($user, $apiUsers);
            // Augment children_info for UI display (avoid undefined)
            $visualization['children_info']['displayed'] = count($apiUsers);
            $visualization['children_info']['total'] = $result['pagination']['total'] ?? count($apiUsers);
            $visualization['children_info']['has_more'] = ($result['pagination']['has_more_pages'] ?? false) || (($result['pagination']['total'] ?? 0) > $visualization['children_info']['displayed']);
            
            return response()->json([
                'visualization' => $visualization,
                'pagination' => $result['pagination'] ?? null,
                'config' => [
                    'width' => $width,
                    'max_depth' => $maxDepth,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get matrix visualization from API', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'referral_code' => $request->user()?->referral_code,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy dữ liệu hiển thị ma trận từ API.',
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