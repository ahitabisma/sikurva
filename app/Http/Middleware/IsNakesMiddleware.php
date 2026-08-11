<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsNakesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has is_nakes attribute set to true
        if (!Auth::check() || !Auth::user()->is_nakes) {
            // Redirect to home with an error message
            abort(403, "Unauthorized access!");
        }

        return $next($request);
    }
}
