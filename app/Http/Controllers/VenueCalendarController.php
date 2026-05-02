<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Building;
use App\Models\Venue;
use App\Models\Division;
use Illuminate\Http\Request;

class VenueCalendarController extends Controller
{
    public function index()
    {
        $buildings = Building::with(['venues' => function ($query) {
            $query->active();
        }])->active()->get();

        $venues = Venue::active()->get();
        $divisions = Division::all();

        return view('user.calendar', compact('venues', 'buildings', 'divisions'));
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

            $color = $booking->venue?->color ?? '#6c757d';
            $startDisplay = date('h:i A', strtotime($booking->start_time));
            $endDisplay   = date('h:i A', strtotime($booking->end_time));

            //  FIXED: Append Room/Floor to the venue name in the modal!
            $venueName = $booking->venue ? $booking->venue->name : '—';
            if ($booking->venue && $booking->venue->room_floor) {
                $venueName .= ' (' . $booking->venue->room_floor . ')';
            }

            return [
                'id'              => $booking->id,
                'title'           => $booking->event_title,
                'start'           => $date . 'T' . $startTime,
                'end'             => $date . 'T' . $endTime,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'display'         => 'block',
                'extendedProps'   => [
                    'venue'       => $venueName,
                    'description' => $booking->agenda ?? $booking->remarks,
                    'booker'      => $booking->booker_name,
                    'time'        => $startDisplay . ' – ' . $endDisplay,
                ],
            ];
        });

        return response()->json($events);
    }
}
