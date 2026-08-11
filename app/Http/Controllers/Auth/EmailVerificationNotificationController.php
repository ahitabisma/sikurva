<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if (Auth::user()->hasVerifiedEmail() && Auth::user()->roles()->first()->name === 'admin') {
                return redirect()->intended(route('patient.index', absolute: false));
            } else if (Auth::user()->hasVerifiedEmail() && Auth::user()->roles()->first()->name === 'super-admin') {
                return redirect()->intended(route('super-admin.dashboard', absolute: false));
            }
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
