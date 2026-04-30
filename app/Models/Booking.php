<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes; // 

    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'venue_id',
        'event_title',
        'agenda',
        'event_date',
        'start_time',
        'end_time',
        'expected_attendees',
        'purpose',
        'building',
        'booker_name',
        'service',
        'division',
        'email',
        'phone',
        'attachment_path',
        'remarks',
        'status',
        'admin_remarks',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'event_date'  => 'date',
        'start_time'  => 'datetime',
        'end_time'    => 'datetime',
        'approved_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function venueEvent()
    {
        return $this->hasOne(VenueEvent::class);
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    // ── Helpers ───────────────────────────────────────────────
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED  => 'bg-success',
            self::STATUS_REJECTED  => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-secondary',
            self::STATUS_COMPLETED => 'bg-info',
            default                => 'bg-warning text-dark',
        };
    }

    // ── Snapshot helper for activity log ─────────────────────

    public function toSnapshot(): array
    {
        return [
            'event_title' => $this->event_title,
            'venue'       => $this->venue?->name,
            'event_date'  => $this->event_date?->format('M d, Y'),
            'booker_name' => $this->booker_name,
            'division'    => $this->division,
            'status'      => $this->status,
        ];
    }
}
