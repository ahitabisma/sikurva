<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //
    protected $table = 'patients';

    protected $fillable = [
        'created_by',
        'kode_lokal',
        'nama',
        'jenis_kelamin',
        'tgl_lahir',
        'count_usia_kehamilan_minggu',
        'usia_kehamilan_minggu',
        'tinggi_ayah',
        'tinggi_ibu',
        'no_wa',
        'email',
    ];

    // simpan nama dalam huruf kapital
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = strtoupper($value);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function antro()
    {
        return $this->hasMany(AntroPatient::class);
    }

    public function sharedData()
    {
        return $this->hasMany(SharedPatient::class);
    }
}
