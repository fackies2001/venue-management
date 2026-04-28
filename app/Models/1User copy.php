<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    const ROLE_USER         = 'user';
    const ROLE_NDRRMOC      = 'ndrrmoc_admin';
    const ROLE_NAB          = 'nab_admin';
    const ROLE_SUPER_ADMIN  = 'super_admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'contact_number',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
        'last_login_at'     => 'datetime',
    ];

    // ── Role helpers ──────────────────────────────────────────
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }
    public function isNdrrmocAdmin(): bool
    {
        return $this->role === self::ROLE_NDRRMOC;
    }
    public function isNabAdmin(): bool
    {
        return $this->role === self::ROLE_NAB;
    }
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [
            self::ROLE_NDRRMOC,
            self::ROLE_NAB,
            self::ROLE_SUPER_ADMIN,
        ]);
    }

    // ── Relationships ─────────────────────────────────────────
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
