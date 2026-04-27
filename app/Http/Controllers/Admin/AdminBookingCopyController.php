<?php
/*
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\VenueEvent;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'venue']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($venueId = $request->input('venue_id')) {
            $query->where('venue_id', $venueId);
        }

        $bookings = $query->latest()->get();
        $venues   = Venue::active()->get();

        return view('admin.bookings.index', compact('bookings', 'venues'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'venue']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Request $request, Booking $booking)
    {
        $request->validate([
            'admin_remarks' => ['nullable', 'string'],
        ]);

        $booking->update([
            'status'        => Booking::STATUS_APPROVED,
            'admin_remarks' => $request->input('admin_remarks'),
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
        ]);

        // Auto-create calendar event
        VenueEvent::create([
            'venue_id'   => $booking->venue_id,
            'booking_id' => $booking->id,
            'title'      => $booking->event_title,
            'description' => $booking->purpose,
            'event_date' => $booking->event_date,
            'start_time' => $booking->event_date->toDateString() . ' ' . \Carbon\Carbon::parse($booking->start_time)->format('H:i:s'),
            'end_time'   => $booking->event_date->toDateString() . ' ' . \Carbon\Carbon::parse($booking->end_time)->format('H:i:s'),
            'color'      => '#3788d8',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Booking approved and added to calendar.');
    }

    public function reject(Request $request, Booking $booking)
    {
        $request->validate([
            'admin_remarks' => ['required', 'string'],
        ]);

        $booking->update([
            'status'        => Booking::STATUS_REJECTED,
            'admin_remarks' => $request->input('admin_remarks'),
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
        ]);

        return back()->with('success', 'Booking rejected.');
    }

    public function history(Request $request)
    {
        $query = Booking::with(['user', 'venue'])
            ->whereIn('status', [
                Booking::STATUS_APPROVED,
                Booking::STATUS_REJECTED,
                Booking::STATUS_CANCELLED,
                Booking::STATUS_COMPLETED,
            ]);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($from = $request->input('date_from')) {
            $query->whereDate('event_date', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('event_date', '<=', $to);
        }

        $history = $query->latest('event_date')->paginate(15)->withQueryString();

        $history = $query->latest('event_date')->get();
    }
}
