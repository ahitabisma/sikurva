<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PatientShareMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $routeName = $request->route()->getName();

        // For patient routes
        if (!str_contains($routeName, 'patient.antro')) {
            // Regular patient routes
            $patientId = $request->route('id');

            // Cari data pasien di patients
            $patient = DB::table('patients')->where('id', $patientId)->first();

            // Jika tidak ditemukan
            if (!$patient) {
                abort(404, 'Patient not found');
            }

            $isOwner = $patient->created_by == $user->id;

            $isSharedToUser = DB::table('patient_shares')
                ->where('patient_id', $patientId)
                ->where('shared_to', $user->id)
                ->where('status', 'accepted')
                ->exists();

            $isCollaborator = DB::table('nakes_collaborators')
                ->where('user_id', $patient->created_by)
                ->where('collaborator_id', $user->id)
                ->exists();

            // Cek apakah user memiliki akses untuk melihat pasien ini
            $hasViewAccess = $isOwner || $isSharedToUser || $isCollaborator;

            if (!$hasViewAccess) {
                abort(403, 'Unauthorized access! | Anda tidak memiliki akses untuk melihat data pasien ini');
            }

            // Daftar route yang memerlukan hak akses owner patient
            $patientOwnerOnlyRoutes = [
                'patient.edit',
                'patient.update',
                'patient.destroy',
            ];

            // Cek apakah route saat ini memerlukan hak akses owner
            if (!$isOwner && in_array($routeName, $patientOwnerOnlyRoutes)) {
                abort(403, 'Hanya pemilik pasien yang dapat melakukan aksi ini');
            }
        }
        // For antro routes
        else {
            if (in_array($routeName, ['patient.antro.create', 'patient.antro.store'])) {
                // For antro creation, we check access to the patient
                $patientId = $request->route('patientId');

                // Cari data pasien
                $patient = DB::table('patients')->where('id', $patientId)->first();

                // Jika tidak ditemukan
                if (!$patient) {
                    abort(404, 'Patient not found');
                }

                $isOwnerOrShared = DB::table('patients')
                    ->where('id', $patientId)
                    ->where(function ($query) use ($user, $patientId) {
                        $query->where('created_by', $user->id)
                            ->orWhereExists(function ($query) use ($user, $patientId) {
                                $query->select(DB::raw(1))
                                    ->from('patient_shares')
                                    ->where('patient_id', $patientId)
                                    ->where('shared_to', $user->id)
                                    ->where('status', 'accepted');
                            });
                    })
                    ->exists();

                if (!$isOwnerOrShared) {
                    abort(403, 'Anda tidak memiliki akses untuk menambahkan data antro ke pasien ini');
                }
            } else {
                // For antro edit/update/delete actions
                $antroId = $request->route('id');
                $antro = DB::table('antro_patients')->where('id', $antroId)->first();

                if (!$antro) {
                    abort(404, 'Antro record not found');
                }

                // Untuk edit/update/delete, langsung cek apakah user adalah pembuat data antro
                if ($antro->created_by != $user->id && in_array($routeName, ['patient.antro.edit', 'patient.antro.update', 'patient.antro.destroy'])) {
                    abort(403, 'Anda hanya dapat mengubah atau menghapus data antro yang Anda buat sendiri');
                }

                // Untuk view, cek apakah user memiliki akses ke pasien terkait
                $patientId = $antro->patient_id;
                $canViewPatient = DB::table('patients')
                    ->where('id', $patientId)
                    ->where(function ($query) use ($user) {
                        $query->where('created_by', $user->id)
                            ->orWhereExists(function ($query) use ($user) {
                                $query->select(DB::raw(1))
                                    ->from('patient_shares')
                                    ->where('shared_to', $user->id)
                                    ->where('status', 'accepted');
                            });
                    })
                    ->exists();

                if (!$canViewPatient) {
                    abort(403, 'Anda tidak memiliki akses untuk melihat data pasien ini');
                }
            }
        }

        // Semua pengecekan berhasil, lanjutkan request
        return $next($request);
    }
}
