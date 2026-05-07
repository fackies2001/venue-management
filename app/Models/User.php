<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_USER         = 'user';
    const ROLE_ADMIN        = 'admin';
    const ROLE_SUPER_ADMIN  = 'super_admin';

    // Approval status constants
    const APPROVAL_PENDING  = 'pending';
    const APPROVAL_APPROVED = 'approved';
    const APPROVAL_REJECTED = 'rejected';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact_number',
        'is_active',
        'is_approved',
        'division_id',
        'otp_code',
        'otp_expires_at',
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
        'last_login_at'    =>  'datetime',
    ];

    // ── Approval helper methods ───────────────────────────────

    public function isPending(): bool
    {
        return $this->is_approved === self::APPROVAL_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->is_approved === self::APPROVAL_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->is_approved === self::APPROVAL_REJECTED;
    }

    // ── Role helper methods ───────────────────────────────────

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    // ── Relationships ─────────────────────────────────────────

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
