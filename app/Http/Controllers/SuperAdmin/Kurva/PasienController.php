<?php

namespace App\Http\Controllers\SuperAdmin\Kurva;

use App\Http\Controllers\Controller;
use App\Http\Services\PatientService;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function index()
    {
        $patients = $this->patientService->getAllPatients(25);
        return view("super-admin.kurva.pasien.index")->with([
            'title' => 'Manajemen Pasien Kurva',
            'patients' => $patients
        ]);
    }

    public function preview(string $id)
    {
        $patient = $this->patientService->findById($id);
        return view("super-admin.kurva.pasien.preview")->with([
            'title' => 'Lihat Pasien Kurva',
            'patient' => $patient
        ]);
    }
    public function create()
    {
        return view("super-admin.kurva.pasien.create")->with([
            'title' => 'Tambah Pasien Kurva'
        ]);
    }
    public function edit()
    {
        return view("super-admin.kurva.pasien.edit")->with([
            'title' => 'Edit Pasien Kurva',
        ]);
    }
}
