<?php

namespace App\Http\Controllers\SuperAdmin\Setting;

use App\Http\Controllers\Controller;
use App\Models\PdfSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PdfController extends Controller
{
    // Define the fixed setting keys
    private $fixedSettings = [
        // 'nama' => 'Nama',
        'nama_instansi' => 'Nama Instansi',
        'alamat' => 'Alamat',
        'sender_name' => 'Sender Name'
    ];

    public function index()
    {
        // Get existing settings from database
        $existingSettings = PdfSetting::all()->keyBy('key');

        // Prepare settings array ensuring fixed settings exist
        $settings = [];

        foreach ($this->fixedSettings as $key => $label) {
            if (isset($existingSettings[$key])) {
                $settings[] = $existingSettings[$key];
            } else {
                // Create placeholder for settings that don't exist yet
                $settings[] = new PdfSetting([
                    'key' => $key,
                    'value' => ''
                ]);
            }
        }

        return view('super-admin.setting.pdf.index', [
            'title' => 'PDF Settings',
            'settings' => $settings,
            'fixedSettings' => $this->fixedSettings
        ]);
    }

    public function update(Request $request)
    {
        if ($request->has('pdfSettings') && is_array($request->pdfSettings)) {
            // Collect all items for validation
            $validationRules = [];
            $validationMessages = [];

            foreach ($request->pdfSettings as $index => $item) {
                // Only validate value as key is fixed
                $validationRules["pdfSettings.$index.value"] = 'nullable|string|max:255';
                $validationMessages["pdfSettings.$index.value.nullable"] = 'Nilai setting boleh kosong.';
                $validationMessages["pdfSettings.$index.value.max"] = 'Nilai setting maksimal 255 karakter.';
            }

            // Validate all items first
            $validator = Validator::make($request->all(), $validationRules, $validationMessages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // First, get all existing keys
                $existingSettings = PdfSetting::all();

                // Delete any settings that are not in the fixed settings list
                foreach ($existingSettings as $setting) {
                    if (!array_key_exists($setting->key, $this->fixedSettings)) {
                        $setting->delete();
                        Log::info("Deleted obsolete PDF setting: {$setting->key}");
                    }
                }

                // Now process the submitted settings
                foreach ($request->pdfSettings as $index => $item) {
                    // Make sure the key is one of our fixed settings
                    if (!array_key_exists($item['key'], $this->fixedSettings)) {
                        continue;
                    }

                    if (isset($item['id']) && $item['id'] > 0) {
                        // Update existing setting
                        PdfSetting::where('id', $item['id'])->update([
                            'value' => $item['value'],
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Create new setting
                        PdfSetting::create([
                            'key' => $item['key'],
                            'value' => $item['value'],
                        ]);
                    }
                }

                DB::commit();
                Cache::forget('pdf_settings');
                Cache::forget('pdf_settings_sender_name');

                return redirect()->route('super-admin.setting.pdf.index')
                    ->with('success', 'Setting PDF berhasil diperbarui.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error updating PDF settings: ' . $e->getMessage());

                return redirect()->back()
                    ->with('error', 'Gagal memperbarui setting PDF: ' . $e->getMessage())
                    ->withInput();
            }
        }

        return redirect()->route('super-admin.setting.pdf.index');
    }

    public function clearValue($id)
    {
        try {
            $setting = PdfSetting::findOrFail($id);

            // Only clear the value, keep the key
            $setting->update([
                'value' => ''
            ]);

            // Clear cache
            Cache::forget('pdf_settings');

            // Regular form submission response
            return redirect()->route('super-admin.setting.pdf.index')
                ->with('success', 'Nilai setting berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('super-admin.setting.pdf.index')
                ->with('error', 'Gagal menghapus nilai setting: ' . $e->getMessage());
        }
    }
}
