<?php

use Carbon\Carbon;

if (!function_exists('asset_vite')) {
    function asset_vite($path)
    {
        $manifestPath = public_path('build/manifest.json');

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);

            return asset('build/' . $manifest[$path]['file']);
        }

        return asset($path);
    }
}


/**
 * Ambil context berdasarkan apakah user punya instansi_id.
 * Output:
 * [
 *   'user_id' => null|int,
 *   'instansi_id' => null|int
 * ]
 */

if (!function_exists('getInstansiOrUserContext')) {
    function getInstansiOrUserContext($user)
    {
        return [
            'user_id' => $user->instansi_id ? null : $user->id,
            'instansi_id' => $user->instansi_id ?? null,
        ];
    }
}


if (!function_exists('gender')) {
    function gender($gender)
    {
        return $gender === 'L' ? 'Male' : 'Female';
    }
}

if (!function_exists('formatDateBirth')) {
    function formatDateBirth($date)
    {
        return Carbon::parse($date)->format('d F Y'); // Output: 15 May 1990
    }
}

if (!function_exists('calculateAge')) {
    function calculateAge($birthDate)
    {
        if (!$birthDate) {
            return 'Tanggal lahir tidak tersedia';
        }

        $birthDate = Carbon::parse($birthDate);
        $currentDate = Carbon::now();
        $diff = $birthDate->diff($currentDate);

        return sprintf('%d tahun, %d bulan, %d hari', $diff->y, $diff->m, $diff->d);
    }
}


if (!function_exists('formatPrice')) {
    function formatPrice($price)
    {
        return number_format($price, 0, ',', '.');
    }
}

if (!function_exists('calculateExpiredAt')) {
    function calculateExpiredAt(string $durationType, int $duration): ?Carbon
    {
        return match ($durationType) {
            'hari' => now()->addDays($duration),
            'bulan' => now()->addMonths($duration),
            'tahun' => now()->addYears($duration),
            default => null,
        };
    }
}

if (!function_exists('convertDaysToYear')) {
    function convertDaysToYear($tglPeriksa, $totalUsiaHari)
    {
        // Tanggal periksa dalam DateTime
        $tglPeriksaDate = new \DateTime($tglPeriksa);

        // Kurangi total hari koreksi untuk mendapatkan tanggal "lahir koreksi"
        $tglLahirKoreksi = (clone $tglPeriksaDate)->modify("-{$totalUsiaHari} days");

        // Hitung selisih dari tanggal lahir koreksi ke tanggal periksa
        $selisih = $tglLahirKoreksi->diff($tglPeriksaDate);

        return [$selisih->y, $selisih->m, $selisih->d]; // tahun, bulan, hari
    }
}


if (!function_exists('convertDaysToWeek')) {
    function convertDaysToWeek($tglPeriksa, $totalHari)
    {
        // Tanggal periksa
        $tglPeriksaDate = new \DateTime($tglPeriksa);

        // Mundur sebanyak total hari dari tanggal periksa
        $tglAwalKehamilan = (clone $tglPeriksaDate)->modify("-{$totalHari} days");

        // Hitung selisih
        $selisih = $tglAwalKehamilan->diff($tglPeriksaDate);

        $totalHariSelisih = $selisih->days;
        $minggu = floor($totalHariSelisih / 7);
        $hari = $totalHariSelisih % 7;

        return [$minggu, $hari];
    }
}
