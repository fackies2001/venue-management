<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Building;
use App\Models\VenueEvent;
use App\Models\Venue;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Mail\BookingApproved;
use App\Mail\BookingCancelled;
use App\Mail\BookingRejected;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'venue'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($venueId = $request->input('venue_id')) {
            $query->where('venue_id', $venueId);
        }

        $bookings = $query->get();
        $venues   = Venue::active()->get();

        return view('admin.bookings.index', compact('bookings', 'venues'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'venue']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $venues    = Venue::active()->get();
        $buildings = Building::active()->orderBy('name')->get();

        //  KEPT: Kinukuha na ngayon ang Divisions galing sa database
        $divisions = Division::orderBy('name')->get();

        return view('admin.bookings.edit', compact('booking', 'venues', 'buildings', 'divisions'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'venue_id'           => ['required', 'exists:venues,id'],
            'event_title'        => ['required', 'string', 'max:255'],
            'agenda'             => ['nullable', 'string', 'max:255'],
            'event_date'         => ['required', 'date'],
            'start_time'         => ['required', 'date_format:H:i'],
            'end_time'           => ['required', 'date_format:H:i', 'after:start_time'],
            'expected_attendees' => ['required', 'integer', 'min:1'],
            'booker_name'        => ['required', 'string', 'max:255'],
            'service'            => ['required', 'string', 'max:255'],
            'division'           => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email'],
            'phone'              => ['required', 'string', 'max:255'],
            'attachment_path'    => ['nullable', 'file', 'max:5120', 'mimes:pdf,docx,jpg,png'],
            'remarks'            => ['nullable', 'string'],
        ]);

        if ($request->hasFile('attachment_path')) {
            if ($booking->attachment_path) {
                Storage::disk('public')->delete($booking->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment_path')->store('attachments', 'public');
        }

        // Check conflicts (ignore this booking's ID)
        $conflict = Booking::where('venue_id', $validated['venue_id'])
            ->where('id', '!=', $booking->id)
            ->where('event_date', $validated['event_date'])
            ->where('status', Booking::STATUS_APPROVED)
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })->exists();

        if ($conflict) {
            return back()->withErrors(['event_date' => 'The venue is already booked at that time.'])->withInput();
        }

        $booking->update($validated);

        //  CHANGED: 'deleted' → 'archived' for admin role
        ActivityLog::record(
            'archived',
            $booking,
            Auth::user()->name . ' archived booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        // If already approved, update the reflected VenueEvent on the Calendar
        if ($booking->status === Booking::STATUS_APPROVED) {
            $booking->venueEvent()->update([
                'venue_id'   => $booking->venue_id,
                'title'      => $booking->event_title,
                'event_date' => $booking->event_date,
                'start_time' => \Carbon\Carbon::parse($booking->event_date)->toDateString() . ' ' . \Carbon\Carbon::parse($booking->start_time)->format('H:i:s'),
                'end_time'   => \Carbon\Carbon::parse($booking->event_date)->toDateString() . ' ' . \Carbon\Carbon::parse($booking->end_time)->format('H:i:s'),
            ]);
        }

        $prefix = match (auth()->user()->role) {
            'admin'       => 'admin',
            'super_admin' => 'super-admin',
            default       => 'user',
        };

        return redirect()->route($prefix . '.bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function archive(Booking $booking)
    {
        ActivityLog::record(
            'archived',
            $booking,
            Auth::user()->name . ' archived booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        $booking->venueEvent()->delete();
        $booking->delete(); // Soft delete

        $prefix = match (auth()->user()->role) {
            'admin'       => 'admin',
            'super_admin' => 'super-admin',
            default       => 'user',
        };

        return redirect()->route($prefix . '.bookings.index')
            ->with('success', 'Booking archived successfully.');
    }

    public function destroy(Booking $booking)
    {
        // ── Log BEFORE delete so we still have the data ───────
        ActivityLog::record(
            'deleted',
            $booking,
            Auth::user()->name . ' deleted booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        if ($booking->attachment_path) {
            Storage::disk('public')->delete($booking->attachment_path);
        }

        $booking->venueEvent()->delete();
        $booking->delete(); // Soft deletes the booking

        return back()->with('success', 'Booking deleted and archived.');
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

        VenueEvent::create([
            'venue_id'   => $booking->venue_id,
            'booking_id' => $booking->id,
            'title'      => $booking->event_title,
            'event_date' => $booking->event_date,
            'start_time' => \Carbon\Carbon::parse($booking->event_date)->toDateString() . ' ' . \Carbon\Carbon::parse($booking->start_time)->format('H:i:s'),
            'end_time'   => \Carbon\Carbon::parse($booking->event_date)->toDateString() . ' ' . \Carbon\Carbon::parse($booking->end_time)->format('H:i:s'),
            'created_by' => Auth::id(),
        ]);

        // ── Log ──────────────────────────────────────────────
        ActivityLog::record(
            'approved',
            $booking,
            Auth::user()->name . ' approved booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        Mail::to($booking->user->email)->send(new BookingApproved($booking));

        return back()->with('success', 'Booking approved and user notified via email.');
    }

    public function reject(Request $request, Booking $booking)
    {
        // 1. Pinalitan natin ng 'nullable' imbes na 'required'
        $request->validate([
            'admin_remarks' => ['nullable', 'string'],
        ]);

        $booking->update([
            'status'        => Booking::STATUS_REJECTED,
            'admin_remarks' => $request->input('admin_remarks'),
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
        ]);

        // ── Log ──────────────────────────────────────────────
        ActivityLog::record(
            'rejected',
            $booking,
            Auth::user()->name . ' rejected booking "' . $booking->event_title . '"',
            // 2. Naglagay tayo ng fallback text kapag walang nilagay na reason
            array_merge($booking->toSnapshot(), ['reason' => $request->input('admin_remarks') ?? 'No reason provided'])
        );

        Mail::to($booking->user->email)->send(new BookingRejected($booking));

        return back()->with('success', 'Booking rejected and user notified via email.');
    }

    public function history(Request $request)
    {
        $query = Booking::with(['user', 'venue'])
            ->whereIn('status', [
                Booking::STATUS_APPROVED,
                Booking::STATUS_REJECTED,
                Booking::STATUS_CANCELLED,
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

        $history = $query->latest('event_date')->get();

        return view('super-admin.history', compact('history'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        // Ginawang nullable para optional lang ang reason
        $request->validate([
            'admin_remarks' => ['nullable', 'string'],
        ]);

        $booking->update([
            'status'        => Booking::STATUS_CANCELLED,
            'admin_remarks' => $request->input('admin_remarks'),
            'approved_by'   => \Illuminate\Support\Facades\Auth::id(),
            'approved_at'   => now(),
        ]);

        // Tanggalin ang event sa calendar
        if ($booking->venueEvent) {
            $booking->venueEvent()->delete();
        }

        // ── Log sa History ──────────────────────────────────────────
        \App\Models\ActivityLog::record(
            'cancelled',
            $booking,
            \Illuminate\Support\Facades\Auth::user()->name . ' cancelled booking "' . $booking->event_title . '"',
            array_merge($booking->toSnapshot(), ['reason' => $request->input('admin_remarks') ?? 'No reason provided'])
        );

        // ── I-send ang Cancelled Email na ginawa mo ────────────────────────────────
        \Illuminate\Support\Facades\Mail::to($booking->user->email)->send(new \App\Mail\BookingCancelled($booking));

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
