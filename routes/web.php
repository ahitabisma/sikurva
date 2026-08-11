<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Refresh Captcha
Route::get('/get_captcha/{config?}', function (\Mews\Captcha\Captcha $captcha, $config = 'default') {
    return $captcha->src($config);
})->name('get_captcha');

// Verifikasi Email
Route::get('/email/verify', function () {
    if (Auth::user()->hasVerifiedEmail() && Auth::user()->roles()->first()->name === 'admin') {
        return redirect()->intended(route('patient.index', absolute: false));
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    if (Auth::user()->roles()->first()->name === 'admin') {
        return redirect()->intended(route('patient.index', absolute: false));
    }
    return redirect()->intended(route('super-admin.dashboard', absolute: false));
})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

// Resend Email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/edit-profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/edit-kode-lokal', [ProfileController::class, 'updateKodeLokal'])->name('profile.update-kode-lokal');

    // Sender Name
    Route::patch('/profile/sender-name', [ProfileController::class, 'updateSenderName'])->name('profile.update-sender-name');
    Route::delete('/profile/sender-name', [ProfileController::class, 'deleteSenderName'])->name('profile.delete-sender-name');

    // Header
    Route::patch('/edit-header', [ProfileController::class, 'updateHeader'])->name('profile.update-header');
    Route::delete('/profile/delete-header', [ProfileController::class, 'deleteHeader'])->name('profile.delete-header');
});

Route::get('/kliniks/search', function (Request $request) {
    $query = $request->input('q');
    $kliniks = DB::table('instansis')->where('name', 'like', "%$query%")
        ->limit(10)
        ->get(['id', 'name']);

    return response()->json($kliniks);
});

require __DIR__ . '/auth.php';
require __DIR__ . '/super-admin.php';
require __DIR__ . '/admin.php';
