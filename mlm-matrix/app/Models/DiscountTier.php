<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountTier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tier',
        'revenue_from',
        'revenue_to',
        'applicable_revenue_from',
        'applicable_revenue_to',
        'discount_rate',
        'tier_name',
        'note',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'revenue_from' => 'decimal:2',
        'revenue_to' => 'decimal:2',
        'applicable_revenue_from' => 'decimal:2',
        'applicable_revenue_to' => 'decimal:2',
        'discount_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope để lấy các tier đang active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('tier');
    }

    /**
     * Lấy tất cả các tier được sắp xếp theo bậc
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('tier');
    }
}

