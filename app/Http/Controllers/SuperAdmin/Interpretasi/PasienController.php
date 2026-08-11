<?php

namespace App\Http\Controllers\SuperAdmin\Interpretasi;

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
        return view("super-admin.interpretasi.pasien.index")->with([
            'title' => 'Manajemen Pasien Interpretasi',
            'patients' => $patients
        ]);
    }
    public function create()
    {
        return view("super-admin.interpretasi.pasien.create")->with([
            'title' => 'Tambah Pasien Interpretasi'
        ]);
    }
    public function edit()
    {
        return view("super-admin.interpretasi.pasien.edit")->with([
            'title' => 'Edit Pasien Interpretasi',
        ]);
    }
}
