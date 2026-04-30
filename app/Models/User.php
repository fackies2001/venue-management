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
    const ROLE_ADMIN        = 'admin';
    const ROLE_SUPER_ADMIN  = 'super_admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact_number',
        'is_active',
        'division_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

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

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }


    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
