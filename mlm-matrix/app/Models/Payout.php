<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
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
        'method',
        'note',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the user who receives this payout.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if payout is pending.
     */
    public function isPending(): bool
    {
        return $this->paid_at === null;
    }

    /**
     * Check if payout is paid.
     */
    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    /**
     * Mark payout as paid.
     */
    public function markAsPaid(): void
    {
        $this->update(['paid_at' => now()]);
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount) . ' VND';
    }

    /**
     * Get status text.
     */
    public function getStatusTextAttribute(): string
    {
        return $this->isPaid() ? 'Đã thanh toán' : 'Chờ thanh toán';
    }

    /**
     * Scope for pending payouts.
     */
    public function scopePending($query)
    {
        return $query->whereNull('paid_at');
    }

    /**
     * Scope for paid payouts.
     */
    public function scopePaid($query)
    {
        return $query->whereNotNull('paid_at');
    }

    /**
     * Scope for payouts by method.
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Scope for payouts by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}