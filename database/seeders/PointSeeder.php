<?php

namespace Database\Seeders;

use App\Models\PointSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'type' => 'bonus',
                'user_type' => 'nakes',
                'name' => 'referrer',
                'points' => 250,
                'duration' => 6,
                'duration_type' => 'bulan',
            ],
            [
                'type' => 'bonus',
                'user_type' => 'non-nakes',
                'name' => 'referrer',
                'points' => 250,
                'duration' => 6,
                'duration_type' => 'bulan',
            ],
            [
                'type' => 'bonus',
                'user_type' => 'nakes',
                'name' => 'referral',
                'points' => 500,
                'duration' => 6,
                'duration_type' => 'bulan',
            ],
            [
                'type' => 'bonus',
                'user_type' => 'non-nakes',
                'name' => 'referral',
                'points' => 500,
                'duration' => 6,
                'duration_type' => 'bulan',
            ],
            [
                'type' => 'bonus',
                'user_type' => 'non-nakes',
                'name' => 'pengguna-baru',
                'points' => 1000,
                'duration' => 1,
                'duration_type' => 'tahun',
            ],
            [
                'type' => 'bonus',
                'user_type' => 'nakes',
                'name' => 'pengguna-baru',
                'points' => 1000,
                'duration' => 4,
                'duration_type' => 'bulan',
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'tambah-pasien',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'download-grafik',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'push-email-grafik',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'push-whatsapp-grafik',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => 'non-nakes',
                'name' => 'share-pasien',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'tambah-antro',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'tambah-header',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'penilaian',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => 'nakes',
                'name' => 'kolaborasi',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'import-pasien',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'import-antro',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'no-wa-custom',
                'points' => 100,
            ],
            [
                'type' => 'usage',
                'user_type' => null,
                'name' => 'email-custom',
                'points' => 100,
            ],
        ];

        foreach ($settings as $setting) {
            PointSetting::create($setting);
        }
    }
}
