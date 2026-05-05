<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\VenueCalendarController;
use App\Http\Controllers\VenueBookingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ArchiveController;
use App\Http\Controllers\Auth\OtpController;
// ── Email Verification Routes ─────────────────────────────────

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::find($id);

    if (!$user) {
        return redirect()->route('login')->with('error', 'Invalid verification link.');
    }

    if (! $request->hasValidSignature()) {
        return redirect()->route('login')->with('error', 'The verification link is invalid or has expired.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
        // is_active stays false until super admin approves

    }

    return redirect()->route('login')
        ->with('success', $user->email . ' was successfully verified. You can now log in.');
})->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// ── Auth Routes ───────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/approval-pending', [LoginController::class, 'approvalPending'])
    ->middleware('auth')
    ->name('approval.pending');


// ── Profile Routes ──────────────────────────────────────────
Route::middleware(['auth', 'verified', 'approved'])->group(function () {
    Route::get('/profile',           [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileController::class, 'updatePassword'])->name('profile.password');
});


// ── Regular User Routes ───────────────────────────────────────
Route::middleware(['auth', 'verified', 'approved', 'role:user'])->prefix('dashboard')->name('user.')->group(function () {
    Route::get('/', fn() => redirect()->route('user.calendar'))->name('dashboard');
    Route::get('/calendar',        [VenueCalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [VenueCalendarController::class, 'events'])->name('calendar.events');
    Route::get('/bookings',              [VenueBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create',       [VenueBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings',             [VenueBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}',    [VenueBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit',     [VenueBookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}',          [VenueBookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}',       [VenueBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::patch('/bookings/{booking}/cancel', [VenueBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
});

// ── Admin Routes ──────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'approved', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.bookings.index'))->name('dashboard');
    Route::get('/calendar',        [VenueCalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [VenueCalendarController::class, 'events'])->name('calendar.events');
    Route::get('/bookings',                       [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create',                [VenueBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings',                      [VenueBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}',             [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit',        [AdminBookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}',             [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}',          [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::patch('/bookings/{booking}/approve',   [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::patch('/bookings/{booking}/reject',    [AdminBookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('/bookings/{booking}/archive', [AdminBookingController::class, 'archive'])->name('bookings.archive');
    Route::patch('/bookings/{booking}/cancel',    [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/history', [AdminBookingController::class, 'history'])->name('history');
});


// ── Super Admin Routes ────────────────────────────────────────
Route::middleware(['auth', 'verified', 'approved', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('super-admin.bookings.index'))->name('dashboard');
    Route::get('/calendar',        [VenueCalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [VenueCalendarController::class, 'events'])->name('calendar.events');
    Route::get('/bookings',                       [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create',                [VenueBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings',                      [VenueBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}',             [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit',        [AdminBookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}',             [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}',          [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::patch('/bookings/{booking}/approve',   [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::patch('/bookings/{booking}/reject',    [AdminBookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('/bookings/{booking}/archive', [AdminBookingController::class, 'archive'])->name('bookings.archive');
    Route::patch('/bookings/{booking}/cancel',    [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/history', [AdminBookingController::class, 'history'])->name('history');
    Route::resource('buildings', BuildingController::class)->except(['show', 'create', 'edit']);
    Route::resource('venues', VenueController::class)->except(['show', 'create', 'edit']);
    Route::get('/archive',                          [ArchiveController::class, 'index'])->name('archive.index');
    Route::patch('/archive/{id}/restore',           [ArchiveController::class, 'restore'])->name('archive.restore');
    Route::delete('/archive/{id}/force-delete',     [ArchiveController::class, 'forceDelete'])->name('archive.force-delete');

    // User management
    Route::get('/users',                       [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}',                [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit',           [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',                [UserManagementController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/activate',     [UserManagementController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/deactivate',   [UserManagementController::class, 'deactivate'])->name('users.deactivate');
    Route::delete('/users/{user}',             [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
    Route::patch('/users/{user}/reject', [UserManagementController::class, 'reject'])->name('users.user-reject');

    // Division management
    Route::resource('divisions', DivisionController::class)->except(['show', 'create', 'edit']);

});

// ── 2FA OTP Routes ──────────────────────────────────────────────
Route::get('/login/otp', [OtpController::class, 'index'])->name('otp.index');
Route::post('/login/otp', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/login/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
