<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // Check if email is verified
        if (! Auth::user()->hasVerifiedEmail()) {
            if (session()->has('url.intended') && str_contains(session('url.intended'), '/email/verify/')) {
                return redirect()->intended();
            }
            return redirect()->route('verification.notice');
        }

        // Check if account is active (deactivated by admin)
        if (! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        // Check if account is still pending approval
        if (Auth::user()->isPending()) {
            return redirect()->route('approval.pending');
        }

        // Check if account has been rejected
        if (Auth::user()->isRejected()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', 'Your account has been rejected. Please contact the administrator.');
        }

        return $this->redirectByRole(Auth::user());
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            User::ROLE_USER        => redirect()->route('user.dashboard'),
            User::ROLE_ADMIN       => redirect()->route('admin.dashboard'),
            User::ROLE_SUPER_ADMIN => redirect()->route('super-admin.dashboard'),
            default                => redirect()->route('user.dashboard'),
        };
    }

    public function approvalPending()
    {
        return view('auth.approval-pending');
    }
}
