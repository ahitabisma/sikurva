<?php

namespace App\Http\Controllers\SuperAdmin\Kurva;

use App\Exports\TableDataExport;
use App\Http\Controllers\Controller;
use App\Http\Services\KurvaTableSettingService;
use App\Models\KurvaTableSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(KurvaTableSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        // Check if the user has 2FA enabled
        if (!Auth::user()->google2fa_enabled) {
            // User needs to set up 2FA
            return redirect()->route('2fa.setup')
                ->with('error', 'You need to set up 2FA to access this page.');
        }

        // Check if user has passed verification for this session
        if (!session('google2fa.authenticated')) {
            // User has 2FA but needs to verify
            return redirect()->route('2fa.verify')
                ->with('error', 'Please verify your 2FA code to continue.');
        }

        $settings = $this->settingService->getAll();

        return view("super-admin.kurva.setting.index")->with([
            'title' => 'Manajemen Kurva Tabel Setting',
            'settings' => $settings
        ]);
    }
    public function edit($id)
    {
        // Check if the user has 2FA enabled
        if (!Auth::user()->google2fa_enabled) {
            // User needs to set up 2FA
            return redirect()->route('2fa.setup')
                ->with('error', 'You need to set up 2FA to access this page.');
        }

        // Check if user has passed verification for this session
        if (!session('google2fa.authenticated')) {
            // User has 2FA but needs to verify
            return redirect()->route('2fa.verify')
                ->with('error', 'Please verify your 2FA code to continue.');
        }

        $setting = $this->settingService->getById($id);
        return view("super-admin.kurva.setting.edit")->with([
            'title' => 'Edit Kurva Tabel Setting',
            'setting' => $setting
        ]);
    }

    public function update(Request $request, $id)
    {
        // Check if the user has 2FA enabled
        if (!Auth::user()->google2fa_enabled) {
            // User needs to set up 2FA
            return redirect()->route('2fa.setup')
                ->with('error', 'You need to set up 2FA to access this page.');
        }

        // Check if user has passed verification for this session
        if (!session('google2fa.authenticated')) {
            // User has 2FA but needs to verify
            return redirect()->route('2fa.verify')
                ->with('error', 'Please verify your 2FA code to continue.');
        }

        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|unique:kurva_table_settings,nama,' . $id,
            'judul' => 'required|string',
            'ket_y' => 'required|string',
            'y_min' => 'required|numeric',
            'y_max' => 'required|numeric|gt:y_min', // y_max harus lebih besar dari y_min
            'y_mayor' => 'required|numeric|gt:0',   // y_mayor harus positif
            'y_minor' => 'required|numeric|gt:0',   // y_minor harus positif
            'y_unit' => 'required|string',
            'ket_x' => 'required|string',
            'x_min' => 'required|numeric',
            'x_max' => 'required|numeric|gt:x_min', // x_max harus lebih besar dari x_min
            'x_mayor' => 'required|numeric|gt:0',   // x_mayor harus positif
            'x_minor' => 'required|numeric|gt:0',   // x_minor harus positif
            'x_unit' => 'required|string',
            'sumbu_y' => 'required|string',
            'sumbu_x' => 'required|string',
        ]);

        // Update data dengan input yang tervalidasi
        $isUpdated = $this->settingService->update($id, [
            'nama' => $validated['nama'],
            'judul' => $validated['judul'],
            'ket_y' => $validated['ket_y'],
            'y_min' => $validated['y_min'],
            'y_max' => $validated['y_max'],
            'y_mayor' => $validated['y_mayor'],
            'y_minor' => $validated['y_minor'],
            'y_unit' => $validated['y_unit'],
            'ket_x' => $validated['ket_x'],
            'x_min' => $validated['x_min'],
            'x_max' => $validated['x_max'],
            'x_mayor' => $validated['x_mayor'],
            'x_minor' => $validated['x_minor'],
            'x_unit' => $validated['x_unit'],
            'sumbu_y' => $validated['sumbu_y'],
            'sumbu_x' => $validated['sumbu_x'],
        ]);

        if (!$isUpdated) {
            return back()->with('error', 'Kurva Tabel Setting gagal diperbarui.');
        }

        // Hapus cache
        Cache::forget('kurva_table_settings');

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('super-admin.kurva.setting.index')
            ->with('success', 'Kurva Tabel Setting berhasil diperbarui.');
    }

    public function show(string $namaTabel)
    {
        // Check if the user has 2FA enabled
        if (!Auth::user()->google2fa_enabled) {
            // User needs to set up 2FA
            return redirect()->route('2fa.setup')
                ->with('error', 'You need to set up 2FA to access this page.');
        }

        // Check if user has passed verification for this session
        if (!session('google2fa.authenticated')) {
            // User has 2FA but needs to verify
            return redirect()->route('2fa.verify')
                ->with('error', 'Please verify your 2FA code to continue.');
        }

        // Combine both table column definitions to check against
        $allTableColumns = array_merge(
            KurvaTableSetting::TABLE_COLUMNS,
            KurvaTableSetting::TABLE_COLUMNS_IG
        );

        $allowedTables = array_keys($allTableColumns);
        if (!in_array($namaTabel, $allowedTables) || !Schema::hasTable($namaTabel)) {
            return redirect()->route('super-admin.kurva.setting.index')
                ->with('error', 'Tabel tidak valid atau tidak ditemukan.');
        }

        $setting = KurvaTableSetting::where('nama_tabel', $namaTabel)->first();
        if (!$setting) {
            return redirect()->route('super-admin.kurva.setting.index')
                ->with('error', 'Setting Kurva Tabel tidak ditemukan.');
        }

        $datas = DB::table($namaTabel)->paginate(25);

        // Check which table column definition the table belongs to
        $col = isset(KurvaTableSetting::TABLE_COLUMNS[$namaTabel])
            ? KurvaTableSetting::TABLE_COLUMNS[$namaTabel]
            : KurvaTableSetting::TABLE_COLUMNS_IG[$namaTabel];

        return view('super-admin.kurva.setting.show', [
            'title' => 'Data ' . $setting->nama,
            'setting' => $setting,
            'datas' => $datas,
            'namaTabel' => $namaTabel,
            'col' => $col,
        ]);
    }

    public function export($tableName, $columnName = null)
    {
        // Check if the user has 2FA enabled
        if (!Auth::user()->google2fa_enabled) {
            // User needs to set up 2FA
            return redirect()->route('2fa.setup')
                ->with('error', 'You need to set up 2FA to access this page.');
        }

        // Check if user has passed verification for this session
        if (!session('google2fa.authenticated')) {
            // User has 2FA but needs to verify
            return redirect()->route('2fa.verify')
                ->with('error', 'Please verify your 2FA code to continue.');
        }

        // Validate table name
        if (!in_array($tableName, [
            'table1',
            'table2',
            'table3',
            'table4',
            'table5',
            'table6',
            'table7',
            'table8',
            'table9',
            'table10',
            'table11',
            'table12'
        ])) {
            return back()->with('error', 'Tabel tidak valid');
        }

        $setting = DB::table('kurva_table_settings')
            ->where('nama_tabel', $tableName)
            ->select('nama_tabel', 'nama', 'judul')
            ->first();

        $fileName = trim($setting->nama ?? '');

        if ($fileName === '') {
            $fileName = 'Data_Tabel';
        }

        $fileName .= '.xlsx';


        return Excel::download(new TableDataExport($tableName, $columnName), $fileName);
    }
}
