<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\VenueEvent;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueCalendarController extends Controller
{
    public function index()
    {
        $venues = Venue::active()->get();

        $buildings = Venue::active()
            ->whereNotNull('building')
            ->distinct()
            ->orderBy('building')
            ->pluck('building');

        return view('user.calendar', compact('venues', 'buildings'));
    }

    public function events(Request $request)
    {
        $startDate = substr($request->input('start'), 0, 10);
        $endDate   = substr($request->input('end'), 0, 10);

        $query = Booking::with('venue')
            ->whereIn('status', ['approved', 'pending'])
            ->whereBetween('event_date', [$startDate, $endDate]);

        if ($venueId = $request->input('venue_id')) {
            $query->where('venue_id', $venueId);
        }

        $events = $query->get()->map(function ($booking) {
            $date = substr($booking->event_date, 0, 10);
            $startTime = substr($booking->start_time, 11, 8);
            $endTime   = substr($booking->end_time, 11, 8);


            // Color from DB
            $color = $booking->venue?->color ?? '#6c757d';

            $startDisplay = date('h:i A', strtotime($booking->start_time));
            $endDisplay   = date('h:i A', strtotime($booking->end_time));

            return [
                'id'              => $booking->id,
                'title'           => $booking->event_title,
                'start'           => $date . 'T' . $startTime,
                'end'             => $date . 'T' . $endTime,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'display'         => 'block',
                'extendedProps'   => [
                    'venue'       => $booking->venue?->name ?? '—',
                    'description' => $booking->agenda ?? $booking->remarks,
                    'booker'      => $booking->booker_name,
                    'time'        => $startDisplay . ' – ' . $endDisplay,
                ],
            ];
        });

        return response()->json($events);
    }
}
