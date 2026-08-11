<?php

namespace App\Http\Controllers\SuperAdmin\Kurva;

use App\Exports\KurvaTableExport;
use App\Http\Controllers\Controller;
use App\Imports\KurvaTableImport;
use App\Models\KurvaTableSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class TableController extends Controller
{
    // Daftar tabel yang diizinkan
    private $allowedTables = [
        'table1',
        'table2',
        'table3',
        'table4',
        'table5',
        'table6',
        'table7',
        'table8',
    ];

    private $allowedTablesIg = [
        'table9',
        'table10',
        'table11',
        'table12',

    ];

    // Mapping kolom utama
    private $columnMapping = KurvaTableSetting::TABLE_COLUMNS;
    private $columnMappingIg = KurvaTableSetting::TABLE_COLUMNS_IG;

    /**
     * Validate if the table is allowed and exists.
     */
    private function isValidTable($namaTabel)
    {
        return in_array($namaTabel, $this->allowedTables) && Schema::hasTable($namaTabel);
    }

    private function isValidTableIg($namaTabel)
    {
        return in_array($namaTabel, $this->allowedTablesIg) && Schema::hasTable($namaTabel);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create($namaTabel)
    {
        if (!$this->isValidTable($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.index', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        $setting = KurvaTableSetting::where('nama_tabel', $namaTabel)->first();
        return view('super-admin.kurva.setting.table.create', [
            'title' => 'Tambah Data ' . ($setting ? $setting->nama : $namaTabel),
            'namaTabel' => $namaTabel,
            'column' => $this->columnMapping[$namaTabel],
            'isTable8' => $namaTabel === 'table8',
        ]);
    }

    public function createIg($namaTabel)
    {
        if (!$this->isValidTableIg($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.index', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        $setting = KurvaTableSetting::where('nama_tabel', $namaTabel)->first();
        return view('super-admin.kurva.setting.table.create-ig', [
            'title' => 'Tambah Data ' . ($setting ? $setting->nama : $namaTabel),
            'namaTabel' => $namaTabel,
            'column' => $this->columnMappingIg[$namaTabel],
            'isTable12' => $namaTabel === 'table12',
        ]);
    }

    /**
     * Store a newly created record in storage.
     */
    public function store(Request $request, $namaTabel)
    {
        if (!$this->isValidTable($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        $column = $this->columnMapping[$namaTabel];

        // Definisikan aturan validasi dasar
        $rules = [
            'jenis_kelamin' => 'required|in:L,P',
            $column => 'required|numeric',
            'l' => 'required|numeric',
            'm' => 'required|numeric',
            's' => 'required|numeric',
            'sd4neg' => 'required|numeric',
            'sd3neg' => 'required|numeric',
            'sd2neg' => 'required|numeric',
            'sd1neg' => 'required|numeric',
            'sd0' => 'required|numeric',
            'sd1' => 'required|numeric',
            'sd2' => 'required|numeric',
            'sd3' => 'required|numeric',
            'sd4' => 'required|numeric',
        ];

        // Tambahkan aturan validasi khusus untuk table8
        if ($namaTabel === 'table8') {
            $rules['stdev'] = 'required|numeric';
            $rules['sd5neg'] = 'required|numeric';
        }

        // Validasi input berdasarkan aturan
        $validated = $request->validate($rules);

        // Insert data ke tabel
        DB::table($namaTabel)->insert($validated);

        // Hapus Cache
        Cache::forget("kurva_data_{$namaTabel}_L");
        Cache::forget("kurva_data_{$namaTabel}_P");

        return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function storeIg(Request $request, $namaTabel)
    {
        if (!$this->isValidTableIg($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        $column = $this->columnMappingIg[$namaTabel];

        // Definisikan aturan validasi dasar
        $rules = [
            'jenis_kelamin' => 'required|in:L,P',
            $column => 'required|numeric',
            'z3neg' => 'required|numeric',
            'z2neg' => 'required|numeric',
            'z1neg' => 'required|numeric',
            'z0' => 'required|numeric',
            'z1' => 'required|numeric',
            'z2' => 'required|numeric',
            'z3' => 'required|numeric',
        ];

        // Tambahkan aturan validasi khusus untuk table8
        if (in_array($namaTabel, ['table9', 'table10', 'table11'])) {
            $rules['days'] = 'required|numeric';
        }

        // Validasi input berdasarkan aturan
        $validated = $request->validate($rules);

        // Insert data ke tabel
        DB::table($namaTabel)->insert($validated);

        // Hapus Cache
        Cache::forget("kurva_data_{$namaTabel}_L");
        Cache::forget("kurva_data_{$namaTabel}_P");

        return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
            ->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit($namaTabel, $id)
    {
        if (!$this->isValidTable($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        $data = DB::table($namaTabel)->where('id', $id)->first();

        if (!$data) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Data tidak ditemukan.');
        }

        $setting = KurvaTableSetting::where('nama_tabel', $namaTabel)->first();
        return view('super-admin.kurva.setting.table.edit', [
            'title' => 'Edit Data ' . ($setting ? $setting->nama : $namaTabel),
            'namaTabel' => $namaTabel,
            'data' => $data,
            'column' => $this->columnMapping[$namaTabel],
            'isTable8' => $namaTabel === 'table8',
        ]);
    }

    public function editIg($namaTabel, $id)
    {
        if (!$this->isValidTableIg($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        $data = DB::table($namaTabel)->where('id', $id)->first();

        if (!$data) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Data tidak ditemukan.');
        }

        $setting = KurvaTableSetting::where('nama_tabel', $namaTabel)->first();
        return view('super-admin.kurva.setting.table.edit-ig', [
            'title' => 'Edit Data ' . ($setting ? $setting->nama : $namaTabel),
            'namaTabel' => $namaTabel,
            'data' => $data,
            'column' => $this->columnMappingIg[$namaTabel],
            'isTable12' => $namaTabel === 'table12',
        ]);
    }

    /**
     * Update the specified record in storage.
     */
    public function update(Request $request, $namaTabel, $id)
    {
        if (!$this->isValidTable($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        // Mapping Column
        $column = $this->columnMapping[$namaTabel];

        // Definisikan aturan validasi dasar
        $rules = [
            'jenis_kelamin' => 'required|in:L,P',
            $column => 'required|numeric',
            'l' => 'required|numeric',
            'm' => 'required|numeric',
            's' => 'required|numeric',
            'sd4neg' => 'required|numeric',
            'sd3neg' => 'required|numeric',
            'sd2neg' => 'required|numeric',
            'sd1neg' => 'required|numeric',
            'sd0' => 'required|numeric',
            'sd1' => 'required|numeric',
            'sd2' => 'required|numeric',
            'sd3' => 'required|numeric',
            'sd4' => 'required|numeric',
        ];

        // Tambahkan aturan validasi khusus untuk table8
        if ($namaTabel === 'table8') {
            $rules['stdev'] = 'required|numeric';
            $rules['sd5neg'] = 'required|numeric';
        }

        // Validasi input berdasarkan aturan
        $validated = $request->validate($rules);

        // Update data ke tabel
        try {
            DB::table($namaTabel)->where('id', $id)->update($validated);

            // Hapus Cache
            Cache::forget("kurva_data_{$namaTabel}_L");
            Cache::forget("kurva_data_{$namaTabel}_P");

            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            //throw $th;
            Log::error('Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
            if ($e->getCode() === '23000') {
                // Error code 23000 untuk pelanggaran constraint unik di MySQL/PostgreSQL
                return back()->withInput()->with('error', "Kombinasi kolom jenis_kelamin dan kolom $column sudah digunakan.");
            }

            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function updateIg(Request $request, $namaTabel, $id)
    {
        if (!$this->isValidTableIg($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        // Mapping Column
        $column = $this->columnMappingIg[$namaTabel];

        // Definisikan aturan validasi dasar
        $rules = [
            'jenis_kelamin' => 'required|in:L,P',
            $column => 'required|numeric',
            'z3neg' => 'required|numeric',
            'z2neg' => 'required|numeric',
            'z1neg' => 'required|numeric',
            'z0' => 'required|numeric',
            'z1' => 'required|numeric',
            'z2' => 'required|numeric',
            'z3' => 'required|numeric',
        ];

        // Tambahkan aturan validasi khusus untuk table8
        if (in_array($namaTabel, ['table9', 'table10', 'table11'])) {
            $rules['days'] = 'required|numeric';
        }

        // Validasi input berdasarkan aturan
        $validated = $request->validate($rules);

        // Update data ke tabel
        try {
            DB::table($namaTabel)->where('id', $id)->update($validated);

            // Hapus Cache
            Cache::forget("kurva_data_{$namaTabel}_L");
            Cache::forget("kurva_data_{$namaTabel}_P");

            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            //throw $th;
            Log::error('Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
            if ($e->getCode() === '23000') {
                // Error code 23000 untuk pelanggaran constraint unik di MySQL/PostgreSQL
                return back()->withInput()->with('error', "Kombinasi kolom jenis_kelamin dan kolom $column sudah digunakan.");
            }

            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy($namaTabel, $id)
    {
        if (!$this->isValidTable($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        DB::table($namaTabel)->where('id', $id)->delete();

        // Hapus Cache
        Cache::forget("kurva_data_{$namaTabel}_L");
        Cache::forget("kurva_data_{$namaTabel}_P");

        return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
            ->with('success', 'Data berhasil dihapus.');
    }

    public function destroyIg($namaTabel, $id)
    {
        if (!$this->isValidTableIg($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        DB::table($namaTabel)->where('id', $id)->delete();

        // Hapus Cache
        Cache::forget("kurva_data_{$namaTabel}_L");
        Cache::forget("kurva_data_{$namaTabel}_P");

        return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
            ->with('success', 'Data berhasil dihapus.');
    }

    public function import($namaTabel)
    {
        return view('super-admin.kurva.setting.table.import')->with([
            'title' => 'Import Data ' . ucfirst($namaTabel),
            'namaTabel' => $namaTabel,
        ]);
    }

    public function importStore(Request $request, $namaTabel)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            if ($this->isValidTable($namaTabel)) {
                $column = $this->columnMapping[$namaTabel];
            } elseif ($this->isValidTableIg($namaTabel)) {
                $column = $this->columnMappingIg[$namaTabel];
            } else {
                return back()->with('error', 'Tabel tidak valid atau tidak ditemukan.');
            }

            Excel::import(new KurvaTableImport($namaTabel, $column), $request->file('file'));

            // Hapus Cache
            Cache::forget("kurva_data_{$namaTabel}_L");
            Cache::forget("kurva_data_{$namaTabel}_P");

            return redirect()->route('super-admin.kurva.setting.show', $namaTabel)
                ->with('success', 'Data berhasil diimport dari Excel');
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengimport: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimport: ' . $e->getMessage());
        }
    }

    public function export($namaTabel)
    {
        if ($this->isValidTable($namaTabel)) {
            $column = $this->columnMapping[$namaTabel];
        } elseif ($this->isValidTableIg($namaTabel)) {
            $column = $this->columnMappingIg[$namaTabel];
        } else {
            return back()->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        return Excel::download(new KurvaTableExport($namaTabel, $column), 'template_' . $namaTabel . '.xlsx');
    }
}
