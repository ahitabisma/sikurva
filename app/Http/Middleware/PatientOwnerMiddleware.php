<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PatientOwnerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $patientId = $request->route('id');

        $patient = DB::table('patients')->where('id', $patientId)->first();
        if (!$patient) {
            abort(404, 'Patient not found');
        }

        if ($patient->created_by !== $user->id) {
            abort(403, 'Unauthorized access! | Hanya owner yang bisa melakukan aksi ini');
        }

        return $next($request);
    }
}
