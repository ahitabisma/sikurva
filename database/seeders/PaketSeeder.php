<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pakets = [
            [
                'name' => 'Basic',
                'point' => 100,
                'duration' => 1,
                'duration_type' => 'tahun',
                'price' => 100000,
                'description' => ['Cocok untuk Orang Tua / Awam'],
                'status' => 1,
            ],
            [
                'name' => 'Starter',
                'point' => 1000,
                'duration' => 4,
                'duration_type' => 'bulan',
                'price' => 1000000,
                'description' => ['Cocok untuk Klinik Kecil'],
                'status' => 1,
            ],
            [
                'name' => 'Pro',
                'point' => 5000,
                'duration' => 6,
                'duration_type' => 'bulan',
                'price' => 2500000,
                'description' => ['Cocok untuk Klinik Sedang'],
                'status' => 1,
            ],
            [
                'name' => 'Ultimate',
                'point' => 10000,
                'duration' => 1,
                'duration_type' => 'tahun',
                'price' => 5000000,
                'description' => ['Cocok untuk Klinik Besar'],
                'status' => 1,
            ],
        ];

        foreach ($pakets as $paket) {
            $paket['description'] = json_encode($paket['description']);
            Subscription::create($paket);
        }
    }
}
