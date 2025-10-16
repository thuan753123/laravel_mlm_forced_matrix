<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Cycle;
use App\Services\CommissionService;
use App\Services\CycleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CommissionController extends Controller
{
    public function __construct(
        private CommissionService $commissionService,
        private CycleService $cycleService
    ) {}

    /**
     * Display commission page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get current cycle
        $cycle = $this->cycleService->currentCycle();
        
        // Get commission summary
        $summary = $this->commissionService->getCommissionSummary($user, $cycle);
        
        return view('commissions.index', compact('user', 'cycle', 'summary'));
    }

    /**
     * Get commissions for current user.
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $perPage = (int) $request->query('per_page', 15);
            $status = $request->query('status');
            $cycleId = $request->query('cycle_id');
            
            $query = $user->receivedCommissions()->with(['order', 'payer']);
            
            if ($status) {
                $query->where('status', $status);
            }
            
            if ($cycleId) {
                $query->where('cycle_id', $cycleId);
            }
            
            $commissions = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);
            
            return response()->json([
                'commissions' => $commissions->items(),
                'pagination' => [
                    'current_page' => $commissions->currentPage(),
                    'last_page' => $commissions->lastPage(),
                    'per_page' => $commissions->perPage(),
                    'total' => $commissions->total(),
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get user commissions', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy danh sách hoa hồng.',
            ], 500);
        }
    }
    
    /**
     * Get commission summary for current user.
     */
    public function summary(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $cycleId = $request->query('cycle_id');
            
            $cycle = null;
            if ($cycleId) {
                $cycle = Cycle::find($cycleId);
            } else {
                $cycle = $this->cycleService->currentCycle();
            }
            
            $summary = $this->commissionService->getCommissionSummary($user, $cycle);
            
            return response()->json([
                'summary' => $summary,
                'cycle' => $cycle ? [
                    'id' => $cycle->id,
                    'period' => $cycle->period,
                    'starts_at' => $cycle->starts_at,
                    'ends_at' => $cycle->ends_at,
                ] : null,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get commission summary', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy tóm tắt hoa hồng.',
            ], 500);
        }
    }
    
    /**
     * Get commission history for current user.
     */
    public function history(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $limit = (int) $request->query('limit', 50);
            
            $history = $this->commissionService->getCommissionHistory($user, $limit);
            
            return response()->json([
                'history' => $history,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get commission history', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy lịch sử hoa hồng.',
            ], 500);
        }
    }
    
    /**
     * Get commission details.
     */
    public function show(Commission $commission): JsonResponse
    {
        try {
            $user = request()->user();
            
            // Check if user owns this commission
            if ($commission->receiver_user_id !== $user->id && !$user->canAccessAdmin()) {
                return response()->json([
                    'message' => 'Không có quyền truy cập hoa hồng này.',
                ], 403);
            }
            
            $commission->load(['order', 'payer', 'cycle']);
            
            return response()->json([
                'commission' => [
                    'id' => $commission->id,
                    'amount' => $commission->amount,
                    'currency' => $commission->currency,
                    'level' => $commission->level,
                    'status' => $commission->status,
                    'created_at' => $commission->created_at,
                    'order' => [
                        'id' => $commission->order->id,
                        'amount' => $commission->order->amount,
                        'currency' => $commission->order->currency,
                        'status' => $commission->order->status,
                    ],
                    'payer' => [
                        'id' => $commission->payer->id,
                        'fullname' => $commission->payer->fullname,
                        'email' => $commission->payer->email,
                    ],
                    'cycle' => $commission->cycle ? [
                        'id' => $commission->cycle->id,
                        'period' => $commission->cycle->period,
                        'starts_at' => $commission->cycle->starts_at,
                        'ends_at' => $commission->cycle->ends_at,
                    ] : null,
                    'meta' => $commission->meta,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get commission details', [
                'error' => $e->getMessage(),
                'commission_id' => $commission->id,
                'user_id' => request()->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy chi tiết hoa hồng.',
            ], 500);
        }
    }
    
    /**
     * Get current cycle information.
     */
    public function currentCycle(): JsonResponse
    {
        try {
            $cycle = $this->cycleService->currentCycle();
            
            if (!$cycle) {
                return response()->json([
                    'message' => 'Không có chu kỳ hiện tại.',
                ], 404);
            }
            
            $stats = $this->cycleService->getCycleStats($cycle);
            
            return response()->json([
                'cycle' => [
                    'id' => $cycle->id,
                    'period' => $cycle->period,
                    'starts_at' => $cycle->starts_at,
                    'ends_at' => $cycle->ends_at,
                    'is_open' => $cycle->isOpen(),
                ],
                'stats' => $stats,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get current cycle', [
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy thông tin chu kỳ hiện tại.',
            ], 500);
        }
    }
    
    /**
     * Close current cycle (admin only).
     */
    public function closeCycle(): JsonResponse
    {
        try {
            $user = request()->user();
            
            if (!$user->canAccessAdmin()) {
                return response()->json([
                    'message' => 'Không có quyền đóng chu kỳ.',
                ], 403);
            }
            
            $newCycle = $this->cycleService->closeCurrentCycle();
            
            Log::info('Cycle closed by admin', [
                'closed_by' => $user->id,
                'new_cycle_id' => $newCycle->id,
            ]);
            
            return response()->json([
                'message' => 'Chu kỳ đã được đóng thành công.',
                'new_cycle' => [
                    'id' => $newCycle->id,
                    'period' => $newCycle->period,
                    'starts_at' => $newCycle->starts_at,
                    'ends_at' => $newCycle->ends_at,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to close cycle', [
                'error' => $e->getMessage(),
                'user_id' => request()->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Đóng chu kỳ thất bại.',
            ], 500);
        }
    }
    
    /**
     * Get cycle history.
     */
    public function cycleHistory(): JsonResponse
    {
        try {
            $history = $this->cycleService->getCycleHistory();
            
            return response()->json([
                'history' => $history,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get cycle history', [
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy lịch sử chu kỳ.',
            ], 500);
        }
    }
    
    /**
     * Get commission statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $cycleId = $request->query('cycle_id');
            
            $query = $user->receivedCommissions();
            
            if ($cycleId) {
                $query->where('cycle_id', $cycleId);
            }
            
            $stats = [
                'total_commissions' => $query->count(),
                'total_amount' => $query->sum('amount'),
                'pending_commissions' => $query->where('status', 'pending')->count(),
                'pending_amount' => $query->where('status', 'pending')->sum('amount'),
                'approved_commissions' => $query->where('status', 'approved')->count(),
                'approved_amount' => $query->where('status', 'approved')->sum('amount'),
                'void_commissions' => $query->where('status', 'void')->count(),
                'void_amount' => $query->where('status', 'void')->sum('amount'),
            ];
            
            // Get by level
            $byLevel = $query->get()->groupBy('level')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'amount' => $group->sum('amount'),
                ];
            });
            
            return response()->json([
                'stats' => $stats,
                'by_level' => $byLevel,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get commission stats', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy thống kê hoa hồng.',
            ], 500);
        }
    }
}