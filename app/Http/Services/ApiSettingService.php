<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiSettingService
{
    /**
     * Get API setting by key
     *
     * @param string $key
     * @return string|null
     */
    public function getApiSetting($key)
    {
        $setting = DB::table('api_settings')->where('key', $key)->first();

        if (!$setting) {
            return null;
        }

        // Return decrypted value if the setting is encrypted
        if ($setting->is_encrypted && !empty($setting->value)) {
            try {
                return Crypt::decryptString($setting->value);
            } catch (\Exception $e) {
                Log::error('Decryption failed for key: ' . $key . ' - ' . $e->getMessage());
                return null;
            }
        }

        // Return the raw value if not encrypted
        return $setting->value;
    }
}
