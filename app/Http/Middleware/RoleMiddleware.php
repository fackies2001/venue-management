<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        //  AYOS: Huwag i-logout, i-abort na lang o i-redirect
        if (! in_array(Auth::user()->role, $roles)) {
            abort(404, 'Unauthorized to access this page.');
            // OR: return redirect()->back()->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
