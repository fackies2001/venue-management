<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Venue;
use App\Models\Building;
use App\Models\Division;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VenueBookingController extends Controller
{
    /**
     * Display a listing of the bookings (My Bookings / Manage Bookings).
     */
    public function index(Request $request)
    {
        $query = Booking::with(['venue', 'user', 'approvedBy']);

        // Kung regular user, sarili niya lang na bookings ang makikita niya
        $role = auth()->user()->role;
        if (!in_array($role, ['admin', 'super_admin', 'ndrrmoc_admin', 'nab_admin'])) {
            $query->where('user_id', auth()->id());
        }

        // Apply Filters galing sa UI (Status)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply Filters galing sa UI (Venue)
        if ($request->filled('venue_id')) {
            $query->where('venue_id', $request->venue_id);
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();
        $venues = Venue::active()->get();

        return view('user.bookings.index', compact('bookings', 'venues'));
    }

    /**
     * View specific booking
     */
    public function show($id)
    {
        $booking = Booking::with(['venue', 'user', 'approvedBy'])->findOrFail($id);
        return view('user.bookings.show', compact('booking'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        // Relational setup: Fetch active buildings with their active venues
        $buildings = Building::with(['venues' => function ($query) {
            $query->active();
        }])->active()->get();

        $venues = Venue::active()->get();
        $divisions = Division::all();

        return view('user.bookings.create', compact('buildings', 'venues', 'divisions'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id'           => 'required|exists:venues,id',
            'event_title'        => 'required|string|max:255',
            'agenda'             => 'nullable|string|max:255',
            'event_date'         => 'required|date|after_or_equal:today',
            'start_time'         => 'required',
            'end_time'           => 'required|after:start_time',
            'expected_attendees' => 'required|integer|min:1',
            'booker_name'        => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'service'            => 'required|string|max:255',
            'division'           => 'required|string|max:255',
            'phone'              => 'required|string|max:20',
            'attachment_path'    => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:5120',
            'remarks'            => 'nullable|string',
        ]);

        // 1. I-prepare ang Carbon instances para sa Time Check
        $eventDate = $validated['event_date'];
        $newStart  = \Carbon\Carbon::parse("$eventDate {$validated['start_time']}")->format('Y-m-d H:i:s');
        $newEnd    = \Carbon\Carbon::parse("$eventDate {$validated['end_time']}")->format('Y-m-d H:i:s');

        // 2. CHECK PARA SA OVERLAPPING BOOKING (The SweetAlert Trigger)
        $isBooked = \App\Models\Booking::where('venue_id', $validated['venue_id'])
            ->where('event_date', $eventDate)
            ->whereIn('status', ['approved', 'pending']) // I-check ang active bookings[cite: 2]
            ->where(function ($query) use ($newStart, $newEnd) {
                $query->where('start_time', '<', $newEnd)
                    ->where('end_time', '>', $newStart);
            })
            ->exists();

        if ($isBooked) {
            // Lalabas ang SweetAlert dito dahil sa 'error' session key
            return back()
                ->withInput()
                ->with('error', 'the venue is already booked');
        }

        // 3. File Upload handling
        if ($request->hasFile('attachment_path')) {
            $file     = $request->file('attachment_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['attachment_path'] = $file->storeAs('bookings/attachments', $filename, 'public');
        }

        // 4. Final Data Preparation
        $validated['start_time'] = $newStart;
        $validated['end_time']   = $newEnd;
        $validated['user_id']    = auth()->id();
        $validated['status']     = \App\Models\Booking::STATUS_PENDING;

        // 5. Create Booking
        $booking = \App\Models\Booking::create($validated);

        // Activity Log
        \App\Models\ActivityLog::record(
            'created',
            $booking,
            auth()->user()->name . ' submitted new booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        $prefix = match (auth()->user()->role) {
            'admin'       => 'admin',
            'super_admin' => 'super-admin',
            default       => 'user',
        };

        return redirect()->route("$prefix.calendar")
            ->with('success', 'Booking submitted successfully and is pending approval.');
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);

        if (!in_array(auth()->user()->role, ['admin', 'super_admin']) && !$booking->isPending()) {
            return redirect()->back()->with('error', 'You can only edit pending bookings.');
        }

        $buildings = Building::with(['venues' => function ($query) {
            $query->active();
        }])->active()->get();

        $venues = Venue::active()->get();
        $divisions = Division::all();

        return view('user.bookings.edit', compact('booking', 'buildings', 'venues', 'divisions'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if (!in_array(auth()->user()->role, ['admin', 'super_admin']) && !$booking->isPending()) {
            return redirect()->back()->with('error', 'You can only edit pending bookings.');
        }

        $validated = $request->validate([
            'venue_id'           => 'required|exists:venues,id',
            'event_title'        => 'required|string|max:255',
            'agenda'             => 'nullable|string|max:255',
            'event_date'         => 'required|date',
            'start_time'         => 'required',
            'end_time'           => 'required|after:start_time',
            'expected_attendees' => 'required|integer|min:1',
            'booker_name'        => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'service'            => 'required|string|max:255',
            'division'           => 'required|string|max:255',
            'phone'              => 'required|string|max:20',
            'attachment_path'    => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:5120',
            'remarks'            => 'nullable|string'
        ]);

        if ($request->hasFile('attachment_path')) {
            $file     = $request->file('attachment_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['attachment_path'] = $file->storeAs('bookings/attachments', $filename, 'public');
        }

        $eventDate               = $validated['event_date'];
        $validated['start_time'] = Carbon::parse("$eventDate {$validated['start_time']}")->format('Y-m-d H:i:s');
        $validated['end_time']   = Carbon::parse("$eventDate {$validated['end_time']}")->format('Y-m-d H:i:s');

        $booking->update($validated);

        // ── Log ──────────────────────────────────────────────
        //  ADDED: Logging functionality so user edits don't go unnoticed!
        ActivityLog::record(
            'updated',
            $booking,
            auth()->user()->name . ' updated their pending booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        $prefix = match (auth()->user()->role) {
            'admin'       => 'admin',
            'super_admin' => 'super-admin',
            default       => 'user',
        };

        return redirect()->route("$prefix.bookings.index")->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            if ($booking->user_id !== auth()->id() || !$booking->isPending()) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
        }

        // ── Log BEFORE delete ─────────────────────────────────
        ActivityLog::record(
            'deleted',
            $booking,
            auth()->user()->name . ' deleted booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        $booking->delete(); // Soft delete

        return redirect()->back()->with('success', 'Booking deleted successfully.');
    }

    /**
     * Cancel a booking without deleting it from the database.
     */
    public function cancel(Booking $booking)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            if ($booking->user_id !== auth()->id()) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
        }

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        // ── Log ──────────────────────────────────────────────
        ActivityLog::record(
            'cancelled',
            $booking,
            auth()->user()->name . ' cancelled booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        return redirect()->back()->with('success', 'Booking has been cancelled.');
    }
}
