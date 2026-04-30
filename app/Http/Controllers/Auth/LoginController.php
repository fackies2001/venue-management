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

        // ✅ FIXED: Check if email is verified, BUT respect the email link
        if (! Auth::user()->hasVerifiedEmail()) {

            // Kung galing sila sa email link, ibalik natin sila doon para ma-process yung verification
            if (session()->has('url.intended') && str_contains(session('url.intended'), '/email/verify/')) {
                return redirect()->intended();
            }

            // Kung normal login lang at hindi galing sa email link, ibato sa verification notice page
            return redirect()->route('verification.notice');
        }

        // ✅ Check if account is active
        if (! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
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
}
