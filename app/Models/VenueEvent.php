<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'booking_id',
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'color',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    // ── Relationships ─────────────────────────────────────────
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Calendar format for FullCalendar JS ───────────────────
    // app/Models/VenueEvent.php

    public function toCalendarEvent(): array
    {
        // event_date could be Carbon or string — handle both
        $date = $this->event_date instanceof \Carbon\Carbon
            ? $this->event_date->format('Y-m-d')
            : \Carbon\Carbon::parse($this->event_date)->format('Y-m-d');

        // start_time / end_time are stored as "HH:mm:ss" strings
        $startTime = \Carbon\Carbon::parse($this->start_time)->format('H:i:s');
        $endTime   = \Carbon\Carbon::parse($this->end_time)->format('H:i:s');

        return [
            'id'              => $this->id,
            'title'           => $this->event_title,
            'start'           => $date . 'T' . $startTime,   // e.g. "2026-04-20T14:00:00"
            'end'             => $date . 'T' . $endTime,     // e.g. "2026-04-20T16:00:00"
            'backgroundColor' => '#1a3c72',
            'borderColor'     => '#1a3c72',
            'display'         => 'block',
            'extendedProps'   => [
                'venue'       => $this->venue?->name,
                'description' => $this->agenda ?? $this->remarks,
                'booker'      => $this->booker_name,
                'time'        => \Carbon\Carbon::parse($this->start_time)->format('h:i A')
                    . ' – '
                    . \Carbon\Carbon::parse($this->end_time)->format('h:i A'),
            ],
        ];
    }
}
