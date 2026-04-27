<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCancelled;

class VenueBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('venue')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $venues = Venue::active()->get();

        $buildings = Venue::active()
            ->whereNotNull('building')
            ->distinct()
            ->orderBy('building')
            ->pluck('building');

        return view('user.bookings.create', compact('venues', 'buildings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id'           => ['required', 'exists:venues,id'],
            'event_title'        => ['required', 'string', 'max:255'],
            'agenda'             => ['nullable', 'string', 'max:255'],
            'event_date'         => ['required', 'date', 'after_or_equal:today'],
            'start_time'         => ['required', 'date_format:H:i'],
            'end_time'           => ['required', 'date_format:H:i', 'after:start_time'],
            'expected_attendees' => ['required', 'integer', 'min:1'],
            'purpose'            => ['nullable', 'string'],
            'building'           => ['required', 'string'],
            'booker_name'        => ['required', 'string', 'max:255'],
            'service'            => ['required', 'string', 'max:255'],
            'division'           => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email'],
            'phone'              => ['required', 'string', 'max:255'],
            'attachment_path'    => ['nullable', 'file', 'max:5120', 'mimes:pdf,docx,jpg,png'],
            'remarks'            => ['nullable', 'string'],
        ]);

        // Handle file upload
        if ($request->hasFile('attachment_path')) {
            $validated['attachment_path'] = $request->file('attachment_path')
                ->store('attachments', 'public');
        }

        // Check for conflicting approved bookings
        $conflict = Booking::where('venue_id', $validated['venue_id'])
            ->where('event_date', $validated['event_date'])
            ->where('status', Booking::STATUS_APPROVED)
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })->exists();

        if ($conflict) {
            return back()
                ->withErrors(['event_date' => 'The venue is already booked at that time.'])
                ->withInput();
        }

        Booking::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'status'  => Booking::STATUS_PENDING,
        ]));

        // Redirect based on role so all roles land on the right booking list

        $route = match (auth()->user()->role) {
            'ndrrmoc_admin' => 'ndrrmoc.bookings.index',
            'nab_admin'     => 'nab.bookings.index',
            'super_admin'   => 'super-admin.bookings.index',
            default         => 'user.bookings.index',
        };

        return redirect()->route($route)
            ->with('success', 'Booking request submitted successfully.');
    }

    public function show(Booking $booking)
    {
        // Manual check — dapat sa user na nag-book lang makakakita
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['super_admin', 'ndrrmoc_admin', 'nab_admin']);

        // User lang: sariling booking nila, at pending lang
        if (!$isAdmin) {
            if ($booking->user_id !== $user->id) {
                abort(403);
            }
            if (!$booking->isPending()) {
                return back()->with('error', 'Only pending bookings can be cancelled.');
            }
            $booking->update(['status' => Booking::STATUS_CANCELLED]);
            return back()->with('success', 'Booking cancelled.');
        }

        // Admin: pwedeng i-cancel ang approved bookings, with reason
        if (!$booking->isPending() && !$booking->isApproved()) {
            return back()->with('error', 'Only pending or approved bookings can be cancelled.');
        }

        $request->validate([
            'admin_remarks' => ['required', 'string'],
        ]);

        $booking->update([
            'status'        => Booking::STATUS_CANCELLED,
            'admin_remarks' => $request->admin_remarks,
        ]);

        // Send cancellation email
        \Mail::to($booking->user->email)->send(
            new \App\Mail\BookingCancelled($booking)
        );

        return back()->with('success', 'Booking has been cancelled and the requester has been notified.');
    }
}
