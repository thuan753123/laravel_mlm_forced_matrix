<?php

namespace App\Services;

use App\Models\Cycle;
use App\Models\Commission;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CycleService
{
    /**
     * Get the current active cycle.
     */
    public function currentCycle(): ?Cycle
    {
        return Cycle::current()->first();
    }
    
    /**
     * Get or create current cycle.
     */
    public function getOrCreateCurrentCycle(): Cycle
    {
        $cycle = $this->currentCycle();
        
        if (!$cycle) {
            $cycle = $this->createNewCycle();
        }
        
        return $cycle;
    }
    
    /**
     * Create a new cycle.
     */
    public function createNewCycle(): Cycle
    {
        $config = config('mlm');
        $period = $config['cycle_period'];
        
        $now = now();
        $startsAt = $now->copy();
        $endsAt = $this->calculateCycleEnd($now, $period);
        
        $cycle = Cycle::create([
            'period' => $period,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);
        
        Log::info('New cycle created', [
            'cycle_id' => $cycle->id,
            'period' => $period,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);
        
        return $cycle;
    }
    
    /**
     * Calculate cycle end date based on period.
     */
    private function calculateCycleEnd(Carbon $start, string $period): Carbon
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
     * Close the current cycle and create a new one.
     */
    public function closeCurrentCycle(): Cycle
    {
        return DB::transaction(function () {
            $currentCycle = $this->currentCycle();
            
            if (!$currentCycle) {
                throw new \Exception('No current cycle to close.');
            }
            
            // Close the current cycle
            $currentCycle->close();
            
            // Approve all pending commissions for the closed cycle
            $commissionService = app(CommissionService::class);
            $commissionService->approveCommissionsForCycle($currentCycle);
            
            // Create new cycle
            $newCycle = $this->createNewCycle();
            
            Log::info('Cycle closed and new cycle created', [
                'closed_cycle_id' => $currentCycle->id,
                'new_cycle_id' => $newCycle->id,
            ]);
            
            return $newCycle;
        });
    }
    
    /**
     * Close expired cycles.
     */
    public function closeExpiredCycles(): int
    {
        $expiredCycles = Cycle::expired()->open()->get();
        $closedCount = 0;
        
        foreach ($expiredCycles as $cycle) {
            $cycle->close();
            $closedCount++;
            
            Log::info('Expired cycle closed', [
                'cycle_id' => $cycle->id,
                'period' => $cycle->period,
                'ends_at' => $cycle->ends_at,
            ]);
        }
        
        return $closedCount;
    }
    
    /**
     * Get cycle statistics.
     */
    public function getCycleStats(Cycle $cycle): array
    {
        $totalOrders = $cycle->orders()->count();
        $totalOrderValue = $cycle->orders()->sum('amount');
        $totalCommissions = $cycle->commissions()->sum('amount');
        $approvedCommissions = $cycle->commissions()->where('status', 'approved')->sum('amount');
        $pendingCommissions = $cycle->commissions()->where('status', 'pending')->sum('amount');
        
        return [
            'cycle_id' => $cycle->id,
            'period' => $cycle->period,
            'starts_at' => $cycle->starts_at,
            'ends_at' => $cycle->ends_at,
            'is_open' => $cycle->isOpen(),
            'total_orders' => $totalOrders,
            'total_order_value' => $totalOrderValue,
            'total_commissions' => $totalCommissions,
            'approved_commissions' => $approvedCommissions,
            'pending_commissions' => $pendingCommissions,
            'duration_days' => $cycle->duration_in_days,
        ];
    }
    
    /**
     * Get cycle history.
     */
    public function getCycleHistory(int $limit = 10): array
    {
        return Cycle::orderBy('starts_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($cycle) {
                return $this->getCycleStats($cycle);
            })
            ->toArray();
    }
    
    /**
     * Get user's commission summary for a cycle.
     */
    public function getUserCycleSummary(User $user, Cycle $cycle): array
    {
        $commissionService = app(CommissionService::class);
        return $commissionService->getCommissionSummary($user, $cycle);
    }
    
    /**
     * Get all users with commissions in a cycle.
     */
    public function getCycleCommissionUsers(Cycle $cycle): array
    {
        return $cycle->commissions()
            ->with('receiver')
            ->get()
            ->groupBy('receiver_user_id')
            ->map(function ($commissions, $userId) {
                $user = $commissions->first()->receiver;
                return [
                    'user_id' => $userId,
                    'user_name' => $user->fullname ?? $user->email,
                    'total_commissions' => $commissions->sum('amount'),
                    'commission_count' => $commissions->count(),
                ];
            })
            ->values()
            ->toArray();
    }
    
    /**
     * Process cycle closure (approve commissions, create payouts, etc.).
     */
    public function processCycleClosure(Cycle $cycle): array
    {
        return DB::transaction(function () use ($cycle) {
            $results = [
                'commissions_approved' => 0,
                'payouts_created' => 0,
                'total_payout_amount' => 0,
            ];
            
            // Approve all pending commissions
            $commissionService = app(CommissionService::class);
            $results['commissions_approved'] = $commissionService->approveCommissionsForCycle($cycle);
            
            // Create payouts for users with approved commissions
            $userCommissions = $cycle->commissions()
                ->where('status', 'approved')
                ->get()
                ->groupBy('receiver_user_id');
            
            foreach ($userCommissions as $userId => $commissions) {
                $totalAmount = $commissions->sum('amount');
                
                if ($totalAmount > 0) {
                    $user = $commissions->first()->receiver;
                    
                    // Create payout record
                    $payout = $user->payouts()->create([
                        'amount' => $totalAmount,
                        'method' => 'bank_transfer', // Default method
                        'note' => "Commission payout for cycle {$cycle->id}",
                    ]);
                    
                    $results['payouts_created']++;
                    $results['total_payout_amount'] += $totalAmount;
                }
            }
            
            Log::info('Cycle closure processed', [
                'cycle_id' => $cycle->id,
                'results' => $results,
            ]);
            
            return $results;
        });
    }
    
    /**
     * Get cycle performance metrics.
     */
    public function getCyclePerformance(Cycle $cycle): array
    {
        $stats = $this->getCycleStats($cycle);
        
        // Calculate performance metrics
        $commissionRate = $stats['total_order_value'] > 0 
            ? ($stats['total_commissions'] / $stats['total_order_value']) * 100 
            : 0;
        
        $avgOrderValue = $stats['total_orders'] > 0 
            ? $stats['total_order_value'] / $stats['total_orders'] 
            : 0;
        
        $avgCommissionPerUser = $stats['total_commissions'] > 0 
            ? $stats['total_commissions'] / max(1, $this->getCycleCommissionUsers($cycle)) 
            : 0;
        
        return [
            'cycle_id' => $cycle->id,
            'period' => $cycle->period,
            'duration_days' => $stats['duration_days'],
            'total_orders' => $stats['total_orders'],
            'total_order_value' => $stats['total_order_value'],
            'total_commissions' => $stats['total_commissions'],
            'commission_rate' => round($commissionRate, 2),
            'avg_order_value' => round($avgOrderValue),
            'avg_commission_per_user' => round($avgCommissionPerUser),
            'is_open' => $cycle->isOpen(),
        ];
    }
}