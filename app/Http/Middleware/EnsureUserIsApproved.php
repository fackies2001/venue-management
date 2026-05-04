<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Block access if the authenticated user has not been approved
     * by the Super Admin yet (or has been rejected).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Super admins are always allowed — they approve others
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Rejected users → show rejection page
        if ($user->isRejected()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', 'Your account has been rejected. Please contact the administrator.');
        }

        // Pending users → show waiting page
        if ($user->isPending()) {
            return redirect()->route('approval.pending');
        }

        return $next($request);
    }
}
