<?php

namespace App\Http\Repositories;

use App\Models\AntroPatient;
use Illuminate\Support\Facades\DB;

class AntroRepository
{
    public function all()
    {
        // Implementasi mengambil semua data
        return DB::table('antro_patients')
            ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
            ->select('antro_patients.*', 'users.name as created_by_name')
            ->orderBy('antro_patients.tgl_periksa', 'desc')
            ->get();
    }

    public function allByPatientId($patientId)
    {
        return DB::table('antro_patients')
            ->where('antro_patients.patient_id', $patientId)
            ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
            ->select('antro_patients.*', 'users.name as created_by_name')
            ->orderBy('antro_patients.tgl_periksa', 'desc');
    }

    public function allByPatientIdPaginated($patientId, $page = 25)
    {
        return $this->allByPatientId($patientId)->paginate($page);
    }

    public function find($id)
    {
        // Implementasi mencari data berdasarkan ID
        return AntroPatient::find($id);
    }

    public function create(array $data)
    {
        // Implementasi membuat data baru
        return AntroPatient::create($data);
    }

    public function update($id, array $data)
    {
        // Implementasi mengupdate data
        $antroPatient = $this->find($id);
        return $antroPatient ? $antroPatient->update($data) : false;
    }

    public function delete($id)
    {
        // Implementasi menghapus data
        $antroPatient = $this->find($id);
        return $antroPatient ? $antroPatient->delete() : false;
    }
}
