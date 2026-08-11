<?php

namespace App\Http\Repositories;

use App\Models\KurvaTableSetting;
use Illuminate\Support\Facades\DB;

class KurvaTableSettingRepository
{
    public function all()
    {
        // Implementasi mengambil semua data
        return DB::table('kurva_table_settings')->paginate(25);
    }

    public function find($id)
    {
        // Implementasi mencari data berdasarkan ID
        return KurvaTableSetting::find($id);
    }

    public function findByNamaTabel($namaTabel)
    {
        // Implementasi mencari data berdasarkan nama
        return DB::table('kurva_table_settings')
            ->where('nama_tabel', $namaTabel)
            ->first();
    }

    public function create(array $data)
    {
        // Implementasi membuat data baru
        return KurvaTableSetting::create($data);
    }

    public function update($id, array $data)
    {
        // Implementasi mengupdate data
        $setting = $this->find($id);
        return $setting ? $setting->update($data) : false;
    }

    public function delete($id)
    {
        // Implementasi menghapus data
        $setting = $this->find($id);
        return $setting ? $setting->delete() : false;
    }
}
