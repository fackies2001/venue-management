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
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', 'Please verify your email address before logging in.');
        }

        // ✅ Check if account is active
        if (! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'last_login_at' => now(),
                'updated_at'    => Auth::user()->updated_at, // ← preserve yung dati
            ]);


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
            User::ROLE_NDRRMOC     => redirect()->route('ndrrmoc.dashboard'),
            User::ROLE_NAB         => redirect()->route('nab.dashboard'),
            User::ROLE_SUPER_ADMIN => redirect()->route('super-admin.dashboard'),
            default                => redirect()->route('user.dashboard'),
        };
    }
}
