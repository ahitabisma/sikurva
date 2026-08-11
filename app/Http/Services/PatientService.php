<?php

namespace App\Http\Services;

use App\Http\Repositories\PatientRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class PatientService
{
    protected $patientRepository;

    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function getAllPatients($page)
    {
        return $this->patientRepository->allPatients($page);
    }

    public function getAll($userId)
    {
        return $this->patientRepository->all($userId);
    }

    public function getPaginated($userId, $page = 25, $search = null)
    {
        return $this->patientRepository->paginated($userId, $page, $search);
    }

    public function findById(string $id)
    {
        return $this->patientRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->patientRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->patientRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->patientRepository->delete($id);
    }

    public function checkKodeLokal($user, $kode_lokal)
    {
        return $this->patientRepository->checkKodeLokal($user, $kode_lokal);
    }

    public function countTotalPatients($userId)
    {
        return $this->patientRepository->countTotalPatients($userId);
    }
}
