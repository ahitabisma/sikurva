<?php

namespace Database\Seeders;

use App\Models\LpBanner;
use App\Models\LpLayanan;
use App\Models\LpProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'bg_banner' => '/img/landing-page/banner/banner-1.jpeg',
                'title' => 'Solusi Digital untuk Pemantauan Tumbuh Kembang Anak',
                'subtitle' => "Supporting Your Child's Growth"
            ],
            [
                'bg_banner' => '/img/landing-page/banner/banner-2.jpeg',
                'title' => 'Digitalisasi Kurva Tumbuh Kembang Anak Indonesia',
                'subtitle' => "Keep Your Child's Healthy"
            ],
        ];

        $profile = [
            'name' => 'dr. Johannus Susanto Wibisono, Sp.A',
            'subtitle' => 'Spesialis Anak',
            'description' => 'dr. Johannus Susanto Wibisono, Sp.A merupakan seorang Dokter Anak. Beliau dapat membantu layanan konsultasi kesehatan pada anak menyeluruh. dr. Johannus Susanto Wibisono telah menyelesaikan pendidikan Spesialis Anak di Universitas Sam Ratulangi. Selain itu, Beliau juga tergabung dalam Ikatan Dokter Anak Indonesia (IDAI).',
            'skills' => ['Spesialis', 'Dokter', 'Anak'],
            'photo' => '/img/landing-page/profile/profile.jpg',
        ];

        $layanans = [
            [
                'image' => '/img/landing-page/layanan/patient.png',
                'title' => 'Pencatatan Data Pasien',
                'description' => 'Layanan pencatatan data pasien kami memungkinkan penyimpanan informasi anak secara akurat dan terorganisir.',
            ],
            [
                'image' => '/img/landing-page/layanan/redo-arrow.png',
                'title' => 'Generate Kurva',
                'description' => 'Menghasilkan kurva tumbuh kembang anak yang mudah dipahami berdasarkan data yang tercatat.',
            ],
            [
                'image' => '/img/landing-page/layanan/interpreter.png',
                'title' => 'Generate Interpretasi',
                'description' => 'Interpretasi otomatis yang memberikan wawasan mendalam tentang status perkembangan anak.',
            ],
        ];

        foreach ($banners as $banner) {
            LpBanner::create($banner);
        }

        LpProfile::firstOrCreate($profile);

        foreach ($layanans as $layanan) {
            LpLayanan::create($layanan);
        }
    }
}
