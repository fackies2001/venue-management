<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'building',
        'color',
        'location',
        'capacity',
        'description',
        'amenities',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function events()
    {
        return $this->hasMany(VenueEvent::class);
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
