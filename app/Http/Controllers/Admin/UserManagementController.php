<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        $users = User::latest()->get();
        return view('super-admin.users.index', compact('users'));
    }

    /**
     * Show the detailed view of a specific user.
     */
    public function show(User $user)
    {
        return view('super-admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the user's account and division.
     */
    public function edit(User $user)
    {
        return view('super-admin.users.edit', [
            'user' => $user,
            // Fetch divisions for the dropdown list in the UI
            'divisions' => \App\Models\Division::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified user account.
     */
    public function update(Request $request, User $user)
    {
        // Handle Administrative Password Reset
        if ($request->filled('password')) {
            $request->validate([
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(16)->letters()->numbers()->symbols()
                ],
            ]);

            $user->update(['password' => Hash::make($request->password)]);

            return back()->with('success', "Password for {$user->name} reset successfully.");
        }

        // Handle Full Account Update
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'        => ['required', 'string', 'in:user,admin,super_admin'],
            'division_id' => ['required', 'exists:divisions,id'],
            // Change 'boolean' to 'nullable' to prevent the validation error seen in your screenshot
            'is_active'   => ['nullable'],
        ]);

        // Manually handle the toggle value: if present, it's true; if missing, it's false
        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User account updated successfully.');
    }

    /**
     * Activate the user account manually.
     */
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);
        return back()->with('success', "{$user->name} has been activated.");
    }

    /**
     * Deactivate the user account manually.
     */
    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);
        return back()->with('success', "{$user->name} has been deactivated.");
    }

    /**
     * Approve user registration and enable access.
     */
    public function approve(User $user)
    {
        $user->update([
            'is_approved' => User::APPROVAL_APPROVED,
            'is_active'   => true,
        ]);
        return back()->with('success', $user->name . ' has been approved.');
    }

    /**
     * Reject user registration.
     */
    public function reject(User $user)
    {
        $user->update(['is_approved' => \App\Models\User::APPROVAL_REJECTED]);
        return back()->with('success', $user->name . ' registration rejected.');
    }
}
