<?php

namespace App\Http\Controllers\SuperAdmin\Setting;

use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Define the fixed setting keys
    private $fixedSettings = [
        'max_patients_admin_awam' => 'Jumlah Maksimal ID Pasien untuk Admin Awam',
        'max_collab_admin_nakes' => 'Jumlah Maksimal Kolaborasi untuk Admin Nakes'
    ];

    public function index()
    {
        // Get existing settings from database
        $existingSettings = UserSetting::all()->keyBy('key');

        // Prepare settings array ensuring fixed settings exist
        $settings = [];

        foreach ($this->fixedSettings as $key => $label) {
            if (isset($existingSettings[$key])) {
                $settings[] = $existingSettings[$key];
            } else {
                // Create placeholder for settings that don't exist yet
                $settings[] = new UserSetting([
                    'key' => $key,
                    'value' => ''
                ]);
            }
        }

        return view('super-admin.setting.user.index', [
            'title' => 'User Limit Settings',
            'settings' => $settings,
            'fixedSettings' => $this->fixedSettings
        ]);
    }

    public function update(Request $request)
    {
        if ($request->has('userSettings') && is_array($request->userSettings)) {
            // Collect all items for validation
            $validationRules = [];
            $validationMessages = [];

            foreach ($request->userSettings as $index => $item) {
                // Only validate value as key is fixed - must be numeric
                $validationRules["userSettings.$index.value"] = 'nullable|numeric|min:1';
                $validationMessages["userSettings.$index.value.nullable"] = 'Nilai setting boleh kosong.';
                $validationMessages["userSettings.$index.value.numeric"] = 'Nilai setting harus berupa angka.';
                $validationMessages["userSettings.$index.value.min"] = 'Nilai setting minimal 1.';
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
                foreach ($request->userSettings as $index => $item) {
                    // Make sure the key is one of our fixed settings
                    if (!array_key_exists($item['key'], $this->fixedSettings)) {
                        continue;
                    }

                    if (isset($item['id']) && $item['id'] > 0) {
                        // Update existing setting
                        UserSetting::where('id', $item['id'])->update([
                            'value' => $item['value'],
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Create new setting
                        UserSetting::create([
                            'key' => $item['key'],
                            'value' => $item['value'],
                        ]);
                    }
                }

                DB::commit();
                Cache::forget('max_patient_for_admin_awam');
                Cache::forget('user_settings');

                return redirect()->route('super-admin.setting.user.index')
                    ->with('success', 'Setting Batasan Pengguna berhasil diperbarui.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error updating User settings: ' . $e->getMessage());

                return redirect()->back()
                    ->with('error', 'Gagal memperbarui setting batasan pengguna: ' . $e->getMessage())
                    ->withInput();
            }
        }

        return redirect()->route('super-admin.setting.user.index');
    }

    public function clearValue($id)
    {
        try {
            $setting = UserSetting::findOrFail($id);

            // Only clear the value, keep the key
            $setting->update([
                'value' => ''
            ]);

            // Clear cache
            Cache::forget('max_patient_for_admin_awam');
            Cache::forget('user_settings');

            // Regular form submission response
            return redirect()->route('super-admin.setting.user.index')
                ->with('success', 'Nilai setting berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('super-admin.setting.user.index')
                ->with('error', 'Gagal menghapus nilai setting: ' . $e->getMessage());
        }
    }
}
