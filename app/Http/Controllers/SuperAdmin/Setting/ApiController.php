<?php

namespace App\Http\Controllers\SuperAdmin\Setting;

use App\Http\Controllers\Controller;
use App\Models\ApiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    protected $apiKeys = [
        'MIDTRANS_MERCHANT_ID',
        'MIDTRANS_CLIENT_KEY',
        'MIDTRANS_SERVER_KEY',
        'WHATSAPP_API_TOKEN',
        // 'WHATSAPP_WEBHOOK_SECRET'
    ];

    protected $encryptedKeys = [
        'MIDTRANS_CLIENT_KEY',
        'MIDTRANS_SERVER_KEY',
        'WHATSAPP_API_TOKEN',
        // 'WHATSAPP_WEBHOOK_SECRET'
    ];

    public function index()
    {
        // Define the exact order we want
        $orderedKeys = [
            'MIDTRANS_MERCHANT_ID',
            'MIDTRANS_CLIENT_KEY',
            'MIDTRANS_SERVER_KEY',
            'WHATSAPP_API_TOKEN',
        ];

        // Get all settings from database
        $apiSettings = ApiSetting::whereIn('key', $this->apiKeys)->get();

        // Create missing settings
        $existingKeys = $apiSettings->pluck('key')->toArray();

        foreach ($this->apiKeys as $key) {
            if (!in_array($key, $existingKeys)) {
                ApiSetting::create([
                    'key' => $key,
                    'value' => '',
                    'is_encrypted' => in_array($key, $this->encryptedKeys)
                ]);
            }
        }

        // Refresh the collection and make sure we sort in our desired order
        if (count($apiSettings) < count($this->apiKeys)) {
            $apiSettings = ApiSetting::whereIn('key', $this->apiKeys)->get();
        }

        // Sort according to our predefined order
        $apiSettings = $apiSettings->sortBy(function ($setting) use ($orderedKeys) {
            return array_search($setting->key, $orderedKeys);
        });

        // Properly decrypt values for display
        foreach ($apiSettings as $setting) {
            if ($setting->is_encrypted && !empty($setting->value)) {
                try {
                    $setting->value = Crypt::decryptString($setting->value);
                } catch (\Exception $e) {
                    // Log the error for debugging
                    // Log::error("Failed to decrypt {$setting->key}: " . $e->getMessage());
                    $setting->value = '';  // Clear invalid encrypted values
                }
            }
        }

        return view('super-admin.setting.api.index', compact('apiSettings'));
    }

    public function update(Request $request)
    {
        $validationRules = [];
        foreach ($this->apiKeys as $key) {
            $validationRules[$key] = 'nullable|string';
        }

        $validated = $request->validate($validationRules);

        foreach ($this->apiKeys as $key) {
            if (isset($validated[$key])) {
                // Check if this key should be encrypted
                $isEncrypted = in_array($key, $this->encryptedKeys);

                // Encrypt the value if needed and not empty
                $valueToStore = $validated[$key];
                if ($isEncrypted && !empty($valueToStore)) {
                    $valueToStore = Crypt::encryptString($valueToStore);
                }

                ApiSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $valueToStore,
                        'is_encrypted' => $isEncrypted
                    ]
                );
            }
        }

        return redirect()->route('super-admin.setting.api.index')->with('success', 'API settings updated successfully');
    }

    public function delete($key)
    {
        if (!in_array($key, $this->apiKeys)) {
            return redirect()->route('super-admin.setting.api.index')->with('error', 'This setting cannot be modified');
        }

        $setting = ApiSetting::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => '']);
            return redirect()->route('super-admin.setting.api.index')->with('success', $key . ' value has been cleared');
        }

        return redirect()->route('super-admin.setting.api.index')->with('error', 'API setting not found');
    }
}
