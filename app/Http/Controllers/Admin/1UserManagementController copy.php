<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        // Load all users (DataTables handles filtering/pagination client-side)
        $users = User::latest()->get();

        return view('super-admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('super-admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('super-admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // ── Password-only update ──────────────────────────────────────────
        if ($request->boolean('password_only')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('super-admin.users.edit', $user)
                ->with('success', "Password for {$user->name} updated successfully.");
        }

        // ── Full profile update ───────────────────────────────────────────
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'           => ['required', Rule::in([
                User::ROLE_USER,
                User::ROLE_ADMIN,
                User::ROLE_SUPER_ADMIN,
            ])],
            // ✅ ADDED: Validates the division dropdown input
            'division_id'    => ['nullable', 'exists:divisions,id'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'is_active'      => ['boolean'],
        ]);

        $user->update($validated);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function activate(User $user)
    {
        $user->update(['is_active' => true]);
        return back()->with('success', "{$user->name} has been activated.");
    }

    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);
        return back()->with('success', "{$user->name} has been deactivated.");
    }

    public function destroy(User $user)
    {

        if ($user->role === User::ROLE_SUPER_ADMIN) {
            return redirect()->route('super-admin.users.index')
                ->with('error', 'Super Admin accounts cannot be deleted.');
        }

        $user->delete();
        return redirect()->route('super-admin.users.index')
            ->with('success', 'User deleted.');
    }
}
