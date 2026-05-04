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
    public function index(Request $request)
    {
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
        // ── Password-only update ──────────────────────────────
        if ($request->boolean('password_only')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            ActivityLog::record(
                'updated',
                $user,
                auth()->user()->name . ' changed password for user "' . $user->name . '"',
                [
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ]
            );

            return redirect()->route('super-admin.users.edit', $user)
                ->with('success', "Password for {$user->name} updated successfully.");
        }

        // ── Full profile update ───────────────────────────────
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'           => ['required', Rule::in([
                User::ROLE_USER,
                User::ROLE_ADMIN,
                User::ROLE_SUPER_ADMIN,
            ])],
            'division_id'    => ['nullable', 'exists:divisions,id'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'is_active'      => ['boolean'],
        ]);

        // Snapshot BEFORE update so we can show what changed
        $before = [
            'name'     => $user->name,
            'email'    => $user->email,
            'role'     => $user->role,
            'is_active' => $user->is_active ? 'active' : 'inactive',
        ];

        $user->update($validated);

        ActivityLog::record(
            'updated',
            $user,
            auth()->user()->name . ' updated profile of user "' . $user->name . '"',
            [
                'name'      => $user->name,
                'email'     => $user->email,
                'role'      => $user->role,
                'status'    => $user->is_active ? 'active' : 'inactive',
                'before'    => implode(', ', array_map(
                    fn($k, $v) => "$k: $v",
                    array_keys($before),
                    $before
                )),
            ]
        );

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        ActivityLog::record(
            'activated',
            $user,
            auth()->user()->name . ' activated user "' . $user->name . '"',
            [
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ]
        );

        return back()->with('success', "{$user->name} has been activated.");
    }

    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);

        ActivityLog::record(
            'deactivated',
            $user,
            auth()->user()->name . ' deactivated user "' . $user->name . '"',
            [
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ]
        );

        return back()->with('success', "{$user->name} has been deactivated.");
    }

    public function destroy(User $user)
    {
        if ($user->role === User::ROLE_SUPER_ADMIN) {
            return redirect()->route('super-admin.users.index')
                ->with('error', 'Super Admin accounts cannot be deleted.');
        }

        // Snapshot BEFORE delete so the log still has the data
        ActivityLog::record(
            'deleted',
            $user,
            auth()->user()->name . ' deleted user "' . $user->name . '"',
            [
                'name'   => $user->name,
                'email'  => $user->email,
                'role'   => $user->role,
                'status' => $user->is_active ? 'active' : 'inactive',
            ]
        );

        $user->delete();

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User deleted.');
    }

    public function approve(User $user)
    {
        $user->update([
            'is_approved' => User::APPROVAL_APPROVED,
            'is_active'   => true, // i-activate 
        ]);
        return back()->with('success', $user->name . ' has been approved and can now access the system.');
    }

    public function reject(User $user)
    {
        $user->update(['is_approved' => \App\Models\User::APPROVAL_REJECTED]);
        return back()->with('success', $user->name . ' has been rejected.');
    }
}
