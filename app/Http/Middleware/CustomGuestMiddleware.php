<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Your custom guest middleware logic here
        if (Auth::guard('web')->check()) {
            if (Auth::user()->hasVerifiedEmail() && Auth::user()->roles()->first()->name === 'admin') {
                return redirect()->intended(route('patient.index'));
            } else if (Auth::user()->hasVerifiedEmail() && Auth::user()->roles()->first()->name === 'super-admin') {
                return redirect()->intended(route('super-admin.dashboard'));
            } else if (!Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}
