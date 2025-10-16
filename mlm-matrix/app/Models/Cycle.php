<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cycle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'period',
        'starts_at',
        'ends_at',
        'closed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Get commissions for this cycle.
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Get orders for this cycle.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if cycle is open.
     */
    public function isOpen(): bool
    {
        return $this->closed_at === null;
    }

    /**
     * Check if cycle is closed.
     */
    public function isClosed(): bool
    {
        return $this->closed_at !== null;
    }

    /**
     * Check if cycle is current (active).
     */
    public function isCurrent(): bool
    {
        $now = now();
        return $this->isOpen() && 
               $this->starts_at <= $now && 
               $this->ends_at >= $now;
    }

    /**
     * Check if cycle is expired.
     */
    public function isExpired(): bool
    {
        return $this->ends_at < now();
    }

    /**
     * Close the cycle.
     */
    public function close(): void
    {
        $this->update(['closed_at' => now()]);
    }

    /**
     * Get total commissions for this cycle.
     */
    public function getTotalCommissionsAttribute(): int
    {
        return $this->commissions()->sum('amount');
    }

    /**
     * Get approved commissions for this cycle.
     */
    public function getApprovedCommissionsAttribute(): int
    {
        return $this->commissions()->where('status', 'approved')->sum('amount');
    }

    /**
     * Get total orders for this cycle.
     */
    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    /**
     * Get total order value for this cycle.
     */
    public function getTotalOrderValueAttribute(): int
    {
        return $this->orders()->sum('amount');
    }

    /**
     * Get duration in days.
     */
    public function getDurationInDaysAttribute(): int
    {
        return $this->starts_at->diffInDays($this->ends_at);
    }

    /**
     * Scope for open cycles.
     */
    public function scopeOpen($query)
    {
        return $query->whereNull('closed_at');
    }

    /**
     * Scope for closed cycles.
     */
    public function scopeClosed($query)
    {
        return $query->whereNotNull('closed_at');
    }

    /**
     * Scope for current cycles.
     */
    public function scopeCurrent($query)
    {
        $now = now();
        return $query->whereNull('closed_at')
                    ->where('starts_at', '<=', $now)
                    ->where('ends_at', '>=', $now);
    }

    /**
     * Scope for expired cycles.
     */
    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<', now());
    }
}