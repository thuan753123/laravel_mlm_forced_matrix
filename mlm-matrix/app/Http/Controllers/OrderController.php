<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private CommissionService $commissionService,
        private \App\Services\ExternalApiService $externalApiService
    ) {}

    /**
     * Render Orders UI page.
     */
    public function page()
    {
        return view('orders.index');
    }

    /**
     * Get orders for current user.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $page = (int) $request->query('page', 1);
            $perPage = (int) $request->query('per_page', 20);
            $status = $request->query('status'); // SUCCESS|PENDING|FAILED
            $search = $request->query('search', '');

            if (!$user || !$user->referral_code) {
                return response()->json([
                    'orders' => [],
                    'pagination' => [
                        'current_page' => $page,
                        'last_page' => 0,
                        'per_page' => $perPage,
                        'total' => 0,
                    ],
                ]);
            }

            $result = $this->externalApiService->fetchPaymentsPaginated($user->referral_code, $page, $perPage, $status, $search);

            return response()->json([
                'orders' => $result['data'],
                'pagination' => $result['pagination'],
                'filters' => [
                    'status' => $status,
                    'search' => $search,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get orders', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy danh sách đơn hàng.',
            ], 500);
        }
    }
    
    /**
     * Create a new order.
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = $request->user();
            
            $order = DB::transaction(function () use ($data, $user) {
                // Create order
                $order = $user->orders()->create([
                    'amount' => $data['amount'],
                    'currency' => $data['currency'] ?? 'VND',
                    'status' => 'pending',
                    'meta' => $data['meta'] ?? [],
                ]);
                
                return $order;
            });
            
            Log::info('Order created', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'amount' => $order->amount,
            ]);
            
            return response()->json([
                'message' => 'Đơn hàng đã được tạo thành công.',
                'order' => [
                    'id' => $order->id,
                    'amount' => $order->amount,
                    'currency' => $order->currency,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                ],
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Failed to create order', [
                'error' => $e->getMessage(),
                'data' => $request->validated(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Tạo đơn hàng thất bại.',
            ], 500);
        }
    }
    
    /**
     * Get order details.
     */
    public function show(Order $order): JsonResponse
    {
        try {
            $user = request()->user();
            
            // Check if user owns this order
            if ($order->user_id !== $user->id && !$user->canAccessAdmin()) {
                return response()->json([
                    'message' => 'Không có quyền truy cập đơn hàng này.',
                ], 403);
            }
            
            $order->load(['commissions.receiver', 'cycle']);
            
            return response()->json([
                'order' => [
                    'id' => $order->id,
                    'amount' => $order->amount,
                    'currency' => $order->currency,
                    'status' => $order->status,
                    'paid_at' => $order->paid_at,
                    'created_at' => $order->created_at,
                    'meta' => $order->meta,
                    'commissions' => $order->commissions->map(function ($commission) {
                        return [
                            'id' => $commission->id,
                            'receiver' => [
                                'id' => $commission->receiver->id,
                                'fullname' => $commission->receiver->fullname,
                                'email' => $commission->receiver->email,
                            ],
                            'level' => $commission->level,
                            'amount' => $commission->amount,
                            'status' => $commission->status,
                            'created_at' => $commission->created_at,
                        ];
                    }),
                    'cycle' => $order->cycle ? [
                        'id' => $order->cycle->id,
                        'period' => $order->cycle->period,
                        'starts_at' => $order->cycle->starts_at,
                        'ends_at' => $order->cycle->ends_at,
                    ] : null,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get order details', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'user_id' => request()->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy chi tiết đơn hàng.',
            ], 500);
        }
    }
    
    /**
     * Mark order as paid.
     */
    public function pay(Order $order): JsonResponse
    {
        try {
            $user = request()->user();
            
            // Check if user owns this order
            if ($order->user_id !== $user->id && !$user->canAccessAdmin()) {
                return response()->json([
                    'message' => 'Không có quyền thanh toán đơn hàng này.',
                ], 403);
            }
            
            if ($order->isPaid()) {
                return response()->json([
                    'message' => 'Đơn hàng đã được thanh toán.',
                ], 400);
            }
            
            if ($order->isCancelled()) {
                return response()->json([
                    'message' => 'Không thể thanh toán đơn hàng đã bị hủy.',
                ], 400);
            }
            
            DB::transaction(function () use ($order) {
                // Mark order as paid
                $order->markAsPaid();
                
                // Distribute commissions
                $this->commissionService->distribute($order);
            });
            
            Log::info('Order marked as paid', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'amount' => $order->amount,
            ]);
            
            return response()->json([
                'message' => 'Đơn hàng đã được thanh toán thành công.',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'paid_at' => $order->paid_at,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to mark order as paid', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'user_id' => request()->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Thanh toán đơn hàng thất bại.',
            ], 500);
        }
    }
    
    /**
     * Cancel an order.
     */
    public function cancel(Order $order): JsonResponse
    {
        try {
            $user = request()->user();
            
            // Check if user owns this order
            if ($order->user_id !== $user->id && !$user->canAccessAdmin()) {
                return response()->json([
                    'message' => 'Không có quyền hủy đơn hàng này.',
                ], 403);
            }
            
            if ($order->isPaid()) {
                return response()->json([
                    'message' => 'Không thể hủy đơn hàng đã thanh toán.',
                ], 400);
            }
            
            if ($order->isCancelled()) {
                return response()->json([
                    'message' => 'Đơn hàng đã bị hủy.',
                ], 400);
            }
            
            $order->markAsCancelled();
            
            Log::info('Order cancelled', [
                'order_id' => $order->id,
                'user_id' => $user->id,
            ]);
            
            return response()->json([
                'message' => 'Đơn hàng đã được hủy.',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to cancel order', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'user_id' => request()->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Hủy đơn hàng thất bại.',
            ], 500);
        }
    }
    
    /**
     * Get order statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            $stats = [
                'total_orders' => $user->orders()->count(),
                'total_amount' => $user->orders()->sum('amount'),
                'paid_orders' => $user->orders()->where('status', 'paid')->count(),
                'paid_amount' => $user->orders()->where('status', 'paid')->sum('amount'),
                'pending_orders' => $user->orders()->where('status', 'pending')->count(),
                'pending_amount' => $user->orders()->where('status', 'pending')->sum('amount'),
                'cancelled_orders' => $user->orders()->where('status', 'cancelled')->count(),
                'cancelled_amount' => $user->orders()->where('status', 'cancelled')->sum('amount'),
            ];
            
            return response()->json([
                'stats' => $stats,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get order stats', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy thống kê đơn hàng.',
            ], 500);
        }
    }
}