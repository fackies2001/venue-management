<?php
/*
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if ($request->hasFile('attachment_path')) {
            $validated['attachment_path'] = $request->file('attachment_path')->store('attachments', 'public');
        }

        $conflict = Booking::where('venue_id', $validated['venue_id'])
            ->where('event_date', $validated['event_date'])
            ->where('status', Booking::STATUS_APPROVED)
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })->exists();

        if ($conflict) {
            return back()->withErrors(['event_date' => 'The venue is already booked at that time.'])->withInput();
        }

        Booking::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'status'  => Booking::STATUS_PENDING,
        ]));

        // Redirect based on role
        $user = Auth::user();
        $role = $user->role;

        return match ($role) {
            'super_admin'   => redirect()->route('super-admin.bookings.index')->with('success', 'Booking request submitted successfully.'),
            'ndrrmoc_admin' => redirect()->route('ndrrmoc.bookings.index')->with('success', 'Booking request submitted successfully.'),
            'nab_admin'     => redirect()->route('nab.bookings.index')->with('success', 'Booking request submitted successfully.'),
            default         => redirect()->route('user.bookings.index')->with('success', 'Booking request submitted successfully.'),
        };
    }

    public function show(Booking $booking)
    {
        $user = Auth::user();
        if ($user->role === 'user' && $booking->user_id !== $user->id) {
            abort(403);
        }
        return view('user.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        $user = Auth::user();
        if ($user->role === 'user' && $booking->user_id !== $user->id) {
            abort(403);
        }

        if (! $booking->isPending()) {
            return back()->with('error', 'Only pending bookings can be cancelled.');
        }

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        return back()->with('success', 'Booking cancelled.');
    }
}
