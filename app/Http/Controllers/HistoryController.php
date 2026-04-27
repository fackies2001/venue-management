<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['venue', 'user'])
            ->where('user_id', Auth::id())
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

        // paginate() — not get() — so hasPages() works in the view
        $history = $query->latest('event_date')->get();

        return view('user.history', compact('history'));
    }
}
