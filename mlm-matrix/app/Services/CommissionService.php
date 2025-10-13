<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Commission;
use App\Models\Cycle;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CommissionService
{
    /**
     * Distribute commissions for an order.
     */
    public function distribute(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $config = config('mlm');
            $commissions = $config['commissions'];
            $maxDepth = $config['max_depth'];
            $cappingPerCycle = $config['capping_per_cycle'];
            $qualifyRules = $config['qualify_rules'];
            
            // Get the upline chain
            $placementService = app(PlacementService::class);
            $uplineChain = $placementService->getUplineChain($order->user);
            
            // Get current cycle
            $cycle = $this->getCurrentCycle();
            
            // Distribute commissions to each level
            foreach ($uplineChain as $level => $uplineUser) {
                $actualLevel = $level + 1; // 1-based level
                
                if ($actualLevel > $maxDepth) {
                    break;
                }
                
                if (!isset($commissions[$actualLevel])) {
                    continue;
                }
                
                // Check if user qualifies for commission
                if (!$this->userQualifies($uplineUser, $qualifyRules)) {
                    continue;
                }
                
                // Calculate commission amount
                $commissionRate = $commissions[$actualLevel];
                $commissionAmount = (int) round($order->amount * $commissionRate);
                
                if ($commissionAmount <= 0) {
                    continue;
                }
                
                // Check capping per cycle
                $totalCommissions = $this->getUserTotalCommissionsInCycle($uplineUser, $cycle);
                
                if ($totalCommissions >= $cappingPerCycle) {
                    Log::info('Commission capped for user', [
                        'user_id' => $uplineUser->id,
                        'total_commissions' => $totalCommissions,
                        'cap' => $cappingPerCycle,
                    ]);
                    continue;
                }
                
                // Apply capping
                $remainingCap = $cappingPerCycle - $totalCommissions;
                $commissionAmount = min($commissionAmount, $remainingCap);
                
                // Create commission record
                $this->createCommission([
                    'receiver_user_id' => $uplineUser->id,
                    'order_id' => $order->id,
                    'payer_user_id' => $order->user_id,
                    'level' => $actualLevel,
                    'amount' => $commissionAmount,
                    'currency' => $order->currency,
                    'status' => 'pending',
                    'cycle_id' => $cycle->id,
                    'idempotency_key' => $this->generateIdempotencyKey($order, $uplineUser, $actualLevel),
                    'meta' => [
                        'commission_rate' => $commissionRate,
                        'order_amount' => $order->amount,
                        'capped' => $commissionAmount < ($order->amount * $commissionRate),
                    ],
                ]);
            }
            
            Log::info('Commissions distributed for order', [
                'order_id' => $order->id,
                'commissions_created' => $order->commissions()->count(),
            ]);
        });
    }
    
    /**
     * Create a commission record with idempotency check.
     */
    private function createCommission(array $data): Commission
    {
        // Check for existing commission with same idempotency key
        $existing = Commission::where('idempotency_key', $data['idempotency_key'])->first();
        
        if ($existing) {
            Log::info('Commission already exists', [
                'idempotency_key' => $data['idempotency_key'],
                'commission_id' => $existing->id,
            ]);
            return $existing;
        }
        
        return Commission::create($data);
    }
    
    /**
     * Generate idempotency key for commission.
     */
    private function generateIdempotencyKey(Order $order, User $receiver, int $level): string
    {
        return hash('sha256', implode('|', [
            $order->id,
            $receiver->id,
            $level,
            $order->created_at->toISOString(),
        ]));
    }
    
    /**
     * Check if user qualifies for commission.
     */
    private function userQualifies(User $user, array $qualifyRules): bool
    {
        // Check KYC requirement
        if ($qualifyRules['kyc_required'] && !$user->active) {
            return false;
        }
        
        // Check minimum personal volume
        $minVolume = $qualifyRules['min_personal_volume'];
        if ($minVolume > 0) {
            $personalVolume = $user->orders()
                ->where('status', 'paid')
                ->sum('amount');
            
            if ($personalVolume < $minVolume) {
                return false;
            }
        }
        
        // Check active order days
        $activeDays = $qualifyRules['active_order_days'];
        if ($activeDays > 0) {
            $lastOrder = $user->orders()
                ->where('status', 'paid')
                ->orderBy('paid_at', 'desc')
                ->first();
            
            if (!$lastOrder || $lastOrder->paid_at->diffInDays(now()) > $activeDays) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get user's total commissions in current cycle.
     */
    private function getUserTotalCommissionsInCycle(User $user, Cycle $cycle): int
    {
        return $user->receivedCommissions()
            ->where('cycle_id', $cycle->id)
            ->where('status', '!=', 'void')
            ->sum('amount');
    }
    
    /**
     * Get current cycle.
     */
    private function getCurrentCycle(): Cycle
    {
        $cycle = Cycle::current()->first();
        
        if (!$cycle) {
            $cycle = $this->createNewCycle();
        }
        
        return $cycle;
    }
    
    /**
     * Create a new cycle.
     */
    private function createNewCycle(): Cycle
    {
        $config = config('mlm');
        $period = $config['cycle_period'];
        
        $now = now();
        $startsAt = $now->copy();
        $endsAt = $this->calculateCycleEnd($now, $period);
        
        return Cycle::create([
            'period' => $period,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);
    }
    
    /**
     * Calculate cycle end date based on period.
     */
    private function calculateCycleEnd(\Carbon\Carbon $start, string $period): \Carbon\Carbon
    {
        switch ($period) {
            case 'daily':
                return $start->copy()->endOfDay();
            case 'weekly':
                return $start->copy()->endOfWeek();
            case 'monthly':
                return $start->copy()->endOfMonth();
            default:
                return $start->copy()->endOfWeek();
        }
    }
    
    /**
     * Approve pending commissions for a cycle.
     */
    public function approveCommissionsForCycle(Cycle $cycle): int
    {
        $count = $cycle->commissions()
            ->where('status', 'pending')
            ->update(['status' => 'approved']);
        
        Log::info('Commissions approved for cycle', [
            'cycle_id' => $cycle->id,
            'approved_count' => $count,
        ]);
        
        return $count;
    }
    
    /**
     * Get commission summary for a user.
     */
    public function getCommissionSummary(User $user, Cycle $cycle = null): array
    {
        $query = $user->receivedCommissions();
        
        if ($cycle) {
            $query->where('cycle_id', $cycle->id);
        }
        
        $commissions = $query->get();
        
        return [
            'total_amount' => $commissions->sum('amount'),
            'pending_amount' => $commissions->where('status', 'pending')->sum('amount'),
            'approved_amount' => $commissions->where('status', 'approved')->sum('amount'),
            'void_amount' => $commissions->where('status', 'void')->sum('amount'),
            'by_level' => $commissions->groupBy('level')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'amount' => $group->sum('amount'),
                ];
            }),
        ];
    }
    
    /**
     * Get commission history for a user.
     */
    public function getCommissionHistory(User $user, int $limit = 50): array
    {
        return $user->receivedCommissions()
            ->with(['order', 'payer'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($commission) {
                return [
                    'id' => $commission->id,
                    'amount' => $commission->amount,
                    'level' => $commission->level,
                    'status' => $commission->status,
                    'order_id' => $commission->order_id,
                    'payer_name' => $commission->payer->fullname ?? $commission->payer->email,
                    'created_at' => $commission->created_at,
                ];
            })
            ->toArray();
    }
}