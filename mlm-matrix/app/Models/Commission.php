<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'receiver_user_id',
        'order_id',
        'payer_user_id',
        'level',
        'amount',
        'currency',
        'status',
        'cycle_id',
        'idempotency_key',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'integer',
        'level' => 'integer',
        'meta' => 'array',
    ];

    /**
     * Get the user who receives this commission.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    /**
     * Get the user who pays this commission.
     */
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_user_id');
    }

    /**
     * Get the order this commission is based on.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the cycle this commission belongs to.
     */
    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    /**
     * Check if commission is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if commission is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if commission is void.
     */
    public function isVoid(): bool
    {
        return $this->status === 'void';
    }

    /**
     * Approve the commission.
     */
    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    /**
     * Void the commission.
     */
    public function void(): void
    {
        $this->update(['status' => 'void']);
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount) . ' ' . $this->currency;
    }

    /**
     * Get percentage of commission.
     */
    public function getPercentageAttribute(): float
    {
        $config = config('mlm.commissions');
        return $config[$this->level] ?? 0;
    }

    /**
     * Scope for pending commissions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved commissions.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for void commissions.
     */
    public function scopeVoid($query)
    {
        return $query->where('status', 'void');
    }

    /**
     * Scope for commissions by level.
     */
    public function scopeByLevel($query, int $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope for commissions by cycle.
     */
    public function scopeByCycle($query, int $cycleId)
    {
        return $query->where('cycle_id', $cycleId);
    }

    /**
     * Scope for commissions by receiver.
     */
    public function scopeByReceiver($query, int $userId)
    {
        return $query->where('receiver_user_id', $userId);
    }

    /**
     * Scope for commissions by payer.
     */
    public function scopeByPayer($query, int $userId)
    {
        return $query->where('payer_user_id', $userId);
    }
}