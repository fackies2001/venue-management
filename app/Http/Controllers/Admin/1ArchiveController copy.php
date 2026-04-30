<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchiveController extends Controller
{
    /**
     * Display the activity logs and soft-deleted (archived) bookings.
     */
    public function index(Request $request)
    {
        // ── 1. Activity Logs (Paginated & Filterable) ─────────
        $logsQuery = ActivityLog::with('user')->latest();

        if ($action = $request->input('action')) {
            $logsQuery->where('action', $action);
        }
        if ($from = $request->input('date_from')) {
            $logsQuery->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $logsQuery->whereDate('created_at', '<=', $to);
        }
        if ($search = $request->input('search')) {
            $logsQuery->where('description', 'like', "%{$search}%");
        }

        // Using a specific pagination name ('logs_page') in case you add pagination to bookings later
        $logs = $logsQuery->paginate(20, ['*'], 'logs_page');

        // ── 2. Soft-Deleted (Archived) Bookings ───────────────
        $deletedBookings = Booking::onlyTrashed()
            ->with(['user', 'venue'])
            ->latest('deleted_at')
            ->get();

        return view('super-admin.archive.index', compact('logs', 'deletedBookings'));
    }

    /**
     * Restore a soft-deleted booking.
     */
    public function restore($id)
    {
        $booking = Booking::onlyTrashed()->findOrFail($id);

        $booking->restore();

        // Log the restoration
        ActivityLog::record(
            'restored',
            $booking,
            auth()->user()->name . ' restored deleted booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        return back()->with('success', 'Booking "' . $booking->event_title . '" has been restored successfully.');
    }

    /**
     * Permanently delete a booking from the database.
     */
    public function forceDelete($id)
    {
        $booking = Booking::onlyTrashed()->findOrFail($id);

        // Log the permanent deletion BEFORE we actually destroy the record
        ActivityLog::record(
            'permanently_deleted',
            $booking,
            auth()->user()->name . ' permanently deleted booking "' . $booking->event_title . '"',
            $booking->toSnapshot()
        );

        // Optional: If you need to permanently delete the attachment file too, do it here
        // if ($booking->attachment_path) {
        //     \Illuminate\Support\Facades\Storage::disk('public')->delete($booking->attachment_path);
        // }

        $booking->forceDelete();

        return back()->with('success', 'Booking has been permanently deleted.');
    }
}
