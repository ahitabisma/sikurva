<?php

namespace Database\Seeders;

use App\Models\Instansi;
use App\Models\PointBatch;
use App\Models\PointTransaction;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::create(['name' => 'super-admin']);
        $admin = Role::create(['name' => 'admin']);

        // Create Super Admin user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('123123123'),
            'email_verified_at' => now(),
            'phone' => '08123123123',
            'address' => 'A',
            'is_nakes' => false,
            'status' => true,
        ]);

        $user->assignRole($superAdmin);

        // Create Admin user with institution
        $userAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123123123'),
            'email_verified_at' => now(),
            'phone' => '08123123123',
            'address' => 'A',
            'is_nakes' => true,
            'status' => true,
        ]);

        $instansi = Instansi::create([
            'name' => 'Klinik Anak Indonesia A',
            'referral_code' => strtoupper(Str::random(6))
        ]);

        $userAdmin->update(['instansi_id' => $instansi->id]);
        $userAdmin->assignRole($admin);

        // Create 5 patients for userAdmin
        $this->createPatientsForUser($userAdmin, 5);

        // Create point batch for admin's institution
        $pointBatchUserAdmin = PointBatch::create([
            'user_id' => null,
            'instansi_id' => $userAdmin->instansi->id,
            'user_subscription_id' => null,
            'points' => 10000,
            'remaining_points' => 10000,
            'type' => 'bonus',
            'expired_at' => now()->addDays(365),
        ]);

        PointTransaction::create([
            'user_id' => null,
            'instansi_id' => $userAdmin->instansi->id,
            'point_batch_id' => $pointBatchUserAdmin->id,
            'points' => 10000,
            'type' => 'bonus',
            'description' => 'Bonus Pendaftaran',
        ]);

        // 3 Nakes Users (each with their own institution)
        $nakesUsers = [
            [
                'name' => 'Dr. Andi Pratama',
                'email' => 'andi.pratama@gmail.com',
                'phone' => '081234567890',
                'address' => 'Jl. Kesehatan No. 10, Jakarta Selatan',
                'institution' => 'Klinik Sehat Anak',
            ],
            [
                'name' => 'Dr. Siti Nurhayati',
                'email' => 'siti.nurhayati@gmail.com',
                'phone' => '081234567891',
                'address' => 'Jl. Medika No. 15, Jakarta Barat',
                'institution' => 'Klinik Bunda Sayang',
            ],
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'phone' => '081234567892',
                'address' => 'Jl. Sehat No. 25, Jakarta Timur',
                'institution' => 'Rumah Sakit Ibu dan Anak',
            ],
        ];

        foreach ($nakesUsers as $userData) {
            // Create institution for this nakes
            $instansiNakes = Instansi::create([
                'name' => $userData['institution'],
                'referral_code' => strtoupper(Str::random(6))
            ]);

            // Create nakes user
            $nakes = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt('123123123'),
                'email_verified_at' => now(),
                'phone' => $userData['phone'],
                'address' => $userData['address'],
                'is_nakes' => true,
                'instansi_id' => $instansiNakes->id,
                'status' => true,
            ]);

            $nakes->assignRole($admin);

            // Create point batch for this nakes' institution
            $pointBatch = PointBatch::create([
                'user_id' => null,
                'instansi_id' => $instansiNakes->id,
                'user_subscription_id' => null,
                'points' => 5000,
                'remaining_points' => 5000,
                'type' => 'bonus',
                'expired_at' => now()->addDays(365),
            ]);

            PointTransaction::create([
                'user_id' => null,
                'instansi_id' => $instansiNakes->id,
                'point_batch_id' => $pointBatch->id,
                'points' => 5000,
                'type' => 'bonus',
                'description' => 'Bonus Pendaftaran Nakes',
            ]);

            // Create 5 patients for each nakes
            $this->createPatientsForUser($nakes, 5);
        }

        // 3 Non-Nakes Users
        $nonNakesUsers = [
            [
                'name' => 'Bisma Putra',
                'email' => 'bisma@gmail.com',
                'phone' => '08123123123',
                'address' => 'Jl. Merdeka No. 5, Jakarta Pusat',
            ],
            [
                'name' => 'Putri Wulandari',
                'email' => 'putri@gmail.com',
                'phone' => '081234567893',
                'address' => 'Jl. Damai No. 8, Jakarta Selatan',
            ],
            [
                'name' => 'Rudi Hermawan',
                'email' => 'rudi@gmail.com',
                'phone' => '081234567894',
                'address' => 'Jl. Sentosa No. 12, Jakarta Utara',
            ],
        ];

        foreach ($nonNakesUsers as $userData) {
            $nonNakes = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt('123123123'),
                'email_verified_at' => now(),
                'phone' => $userData['phone'],
                'address' => $userData['address'],
                'is_nakes' => false,
                'status' => true,
            ]);

            $nonNakes->assignRole($admin);

            $pointBatch = PointBatch::create([
                'user_id' => $nonNakes->id,
                'instansi_id' => null,
                'user_subscription_id' => null,
                'points' => 7000,
                'remaining_points' => 7000,
                'type' => 'bonus',
                'expired_at' => now()->addDays(120),
            ]);

            PointTransaction::create([
                'user_id' => $nonNakes->id,
                'instansi_id' => null,
                'point_batch_id' => $pointBatch->id,
                'points' => 7000,
                'type' => 'bonus',
                'description' => 'Bonus Pendaftaran',
            ]);

            // Create 5 patients for each non-nakes
            $this->createPatientsForUser($nonNakes, 5);
        }
    }

    /**
     * Create patients for a specific user
     */
    private function createPatientsForUser(User $user, int $count): void
    {
        $genders = ['L', 'P'];

        // Get existing local codes for this user to avoid duplicates
        $existingCodes = Patient::where('created_by', $user->id)->pluck('kode_lokal')->toArray();

        for ($i = 1; $i <= $count; $i++) {
            $gender = $genders[array_rand($genders)];
            $birthDate = now()->subYears(rand(0, 5))->subMonths(rand(0, 11))->subDays(rand(0, 30));

            // Generate a unique local code for this user
            do {
                $localCode = rand(1000, 9999);
            } while (in_array($localCode, $existingCodes));

            $existingCodes[] = $localCode;

            // Generate name based on gender
            $name = $gender === 'L'
                ? $this->getMaleName()
                : $this->getFemaleName();

            // Generate random height for parents
            $fatherHeight = rand(160, 190);
            $motherHeight = rand(150, 175);

            // Random pregnancy week between 32-40
            $pregnancyWeek = rand(32, 40);

            Patient::create([
                'created_by' => $user->id,
                'kode_lokal' => $localCode,
                'nama' => $name,
                'jenis_kelamin' => $gender,
                'tgl_lahir' => $birthDate->format('Y-m-d'),
                'usia_kehamilan_minggu' => $pregnancyWeek,
                'count_usia_kehamilan_minggu' => $pregnancyWeek,
                'tinggi_ayah' => $fatherHeight,
                'tinggi_ibu' => $motherHeight,
                'no_wa' => '08' . rand(1000000000, 9999999999),
                'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
            ]);
        }
    }

    /**
     * Get random male name
     */
    private function getMaleName(): string
    {
        $firstNames = [
            'Ahmad',
            'Budi',
            'Cahyo',
            'Deni',
            'Eko',
            'Fajar',
            'Galih',
            'Hadi',
            'Irfan',
            'Joko',
            'Krisna',
            'Luthfi',
            'Muhammad',
            'Naufal',
            'Putra'
        ];

        $lastNames = [
            'Pratama',
            'Wijaya',
            'Saputra',
            'Nugraha',
            'Hidayat',
            'Kusuma',
            'Putra',
            'Santoso',
            'Wibowo',
            'Setiawan'
        ];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    /**
     * Get random female name
     */
    private function getFemaleName(): string
    {
        $firstNames = [
            'Ayu',
            'Bunga',
            'Citra',
            'Dina',
            'Erni',
            'Fitri',
            'Gita',
            'Hana',
            'Indah',
            'Jasmine',
            'Kirana',
            'Laras',
            'Maya',
            'Nadia',
            'Putri'
        ];

        $lastNames = [
            'Lestari',
            'Wulandari',
            'Utami',
            'Sari',
            'Yulianti',
            'Anggraini',
            'Puspita',
            'Rahmawati',
            'Safitri',
            'Pertiwi'
        ];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    /**
     * Get random city
     */
    private function getRandomCity(): string
    {
        $cities = [
            'Jakarta',
            'Bandung',
            'Surabaya',
            'Yogyakarta',
            'Medan',
            'Semarang',
            'Makassar',
            'Denpasar',
            'Padang',
            'Palembang'
        ];

        return $cities[array_rand($cities)];
    }

    /**
     * Get random blood type
     */
    private function getRandomBloodType(): string
    {
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhFactor = ['+', '-'];

        return $bloodTypes[array_rand($bloodTypes)] . $rhFactor[array_rand($rhFactor)];
    }
}
