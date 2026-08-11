<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->roles->first()->name == 'super-admin') {
            return redirect()->route('super-admin.dashboard');
        } elseif (!$user || $user->roles->first()->name != 'admin') {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
