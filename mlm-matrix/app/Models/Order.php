<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'status',
        'paid_at',
        'cycle_id',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * Get the user who placed this order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cycle this order belongs to.
     */
    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    /**
     * Get commissions generated from this order.
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Check if order is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if order is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if order is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Mark order as paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark order as cancelled.
     */
    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount) . ' ' . $this->currency;
    }

    /**
     * Get total commissions generated from this order.
     */
    public function getTotalCommissionsAttribute(): int
    {
        return $this->commissions()->sum('amount');
    }

    /**
     * Get approved commissions for this order.
     */
    public function getApprovedCommissionsAttribute(): int
    {
        return $this->commissions()->where('status', 'approved')->sum('amount');
    }
}