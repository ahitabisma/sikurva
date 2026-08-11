<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntroPatient extends Model
{
    protected $table = 'antro_patients';
    protected $fillable = [
        'patient_id',
        'created_by',
        'tgl_periksa',
        'usia_bulan',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'usia_hari',
        'imt',
        'notes',
        'total_usia_hari',
        'usia_koreksi_bulan',
        'usia_koreksi_total_hari',
        'usia_gestasi_minggu',
        'usia_gestasi_total_hari',
    ];

    protected static function boot()
    {
        parent::boot();

        // Hook into the 'saving' event (runs before create or update)
        static::saving(function ($model) {
            $model->calculateIMT();
        });
    }

    // Hitung Indeks Massa Tubuh
    public function calculateIMT()
    {
        if ($this->berat_badan && $this->berat_badan != 0 && $this->tinggi_badan && $this->tinggi_badan != 0) {
            // Convert height from cm to m
            $heightInMeters = $this->tinggi_badan / 100;
            // Calculate IMT: weight (kg) / (height (m))^2
            $this->imt = $this->berat_badan / ($heightInMeters * $heightInMeters);
            // Round to 2 decimal places
            $this->imt = round($this->imt, 1);
        } else {
            $this->imt = null; // Explicitly set to null if calculation can't be done
        }
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
