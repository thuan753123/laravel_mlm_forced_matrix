<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'provider',
        'fullname',
        'plan_id',
        'plan',
        'avatar_url',
        'phone_number',
        'active',
        'referral_code',
        'referred_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'last_login_time' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = $user->generateReferralCode();
            }
        });
    }

    /**
     * Generate a unique referral code.
     */
    public function generateReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Get the user who referred this user.
     */
    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Get users referred by this user.
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    /**
     * Get the user who created this user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this user.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the node associated with this user.
     */
    public function node()
    {
        return $this->hasOne(Node::class);
    }

    /**
     * Get orders for this user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get commissions received by this user.
     */
    public function receivedCommissions()
    {
        return $this->hasMany(Commission::class, 'receiver_user_id');
    }

    /**
     * Get commissions paid by this user.
     */
    public function paidCommissions()
    {
        return $this->hasMany(Commission::class, 'payer_user_id');
    }

    /**
     * Get payouts for this user.
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is manager.
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is agent.
     */
    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    /**
     * Check if user is member.
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Check if user can access admin features.
     */
    public function canAccessAdmin(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    /**
     * Update last login time.
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_time' => now()]);
    }
}