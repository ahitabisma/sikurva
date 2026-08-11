<?php

namespace App\Http\Repositories;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class PatientRepository
{
    public function allQuery($userId, $search = null)
    {
        return DB::table('patients')
            ->leftJoin('users', 'patients.created_by', '=', 'users.id')
            ->leftJoin('patient_shares', function ($join) use ($userId) {
                $join->on('patients.id', '=', 'patient_shares.patient_id')
                    ->where('patient_shares.shared_to', '=', $userId)
                    ->where('patient_shares.status', '=', 'accepted');
            })
            ->leftJoin('nakes_collaborators', function ($join) use ($userId) {
                $join->on('patients.created_by', '=', 'nakes_collaborators.user_id')
                    ->where('nakes_collaborators.collaborator_id', '=', $userId)
                    ->where('nakes_collaborators.status', '=', 'accepted');
            })
            ->where(function ($query) use ($userId) {
                $query->where('patients.created_by', $userId)
                    ->orWhereNotNull('patient_shares.id')
                    ->orWhereNotNull('nakes_collaborators.id');
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('patients.nama', 'LIKE', "%{$search}%")
                        ->orWhere('patients.kode_lokal', 'LIKE', "%{$search}%")
                        ->orWhere('users.name', 'LIKE', "%{$search}%");
                });
            })
            ->select(
                'patients.*',
                'users.name as created_by_name'
            )
            ->distinct();
    }

    public function allPatients($page = 25, $search = null)
    {
        return DB::table('patients')
            ->leftJoin('users', 'patients.created_by', '=', 'users.id')
            ->leftJoin('instansis', 'users.instansi_id', '=', 'instansis.id')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('patients.nama', 'LIKE', "%{$search}%")
                        ->orWhere('patients.kode_lokal', 'LIKE', "%{$search}%")
                        ->orWhere('users.name', 'LIKE', "%{$search}%");
                });
            })
            ->select(
                'patients.*',
                'users.name as created_by_name',
                'users.kode_lokal as kode_lokal_user',
                'instansis.kode_lokal as kode_lokal_instansi',
            )->orderBy('patients.created_at', 'asc')->paginate($page);
    }

    public function all($userId)
    {
        return $this->allQuery($userId)->get();
    }

    public function paginated($userId, $page = 25, $search = null)
    {
        return $this->allQuery($userId, $search)
            ->orderBy('patients.created_at', 'desc')
            ->paginate($page);
    }

    public function findById(string $id): ?Patient
    {
        return Patient::find($id);
    }

    public function create(array $data): Patient
    {
        // Implementasi membuat data baru
        return Patient::create($data);
    }

    public function update($id, array $data): bool
    {
        // Implementasi mengupdate data
        $patient = $this->findById($id);
        return $patient ? $patient->update($data) : false;
    }

    public function delete($id): bool
    {
        // Implementasi menghapus data
        $patient = $this->findById($id);
        return $patient ? $patient->delete() : false;
    }

    public function checkKodeLokal($user, $kode_lokal)
    {
        return DB::table('patients')
            ->where('created_by', $user->id)
            ->where('kode_lokal', $kode_lokal)
            ->exists();
    }

    public function countTotalPatients($userId)
    {
        return DB::table('patients')
            ->where('created_by', $userId)
            ->count();
    }
}
