@extends('layouts.tailadmin')

@section('content')
    <div class="flex h-fit flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white xl:h-full xl:w-full mb-5">
        <!-- Header Section -->
        <div class="px-4 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Hasil Penilaian</h3>
            <x-cancel-button url="{{ route('patient.preview', $patient->id) }}"><i class="fas fa-arrow-left"></i>
                Kembali</x-cancel-button>
        </div>

        <!-- Identitas Anak -->
        <div class="border-t border-gray-100 p-5 sm:p-6 grid grid-cols-1 md:grid-cols-3 gap-6 overflow-y-auto">
            {{-- Column 1: Basic Patient Info --}}
            <div class="grid grid-cols-1 gap-3 text-sm text-gray-700 leading-relaxed">
                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Nama Anak</span>
                    <span>: {{ $patient->nama }} {{ '(' . $patient->jenis_kelamin . ')' }}</span>
                </div>

                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Tanggal Lahir</span>
                    <span>: {{ \Carbon\Carbon::parse($patient->tgl_lahir)->translatedFormat('d M Y') }}</span>
                </div>

                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Tangal Periksa</span>
                    <span>: {{ \Carbon\Carbon::parse($latestAntro->tgl_periksa)->translatedFormat('d M Y') }}</span>
                </div>

                <div class="flex items-center space-x-5">
                    <div class="">
                        <span class="font-medium text-gray-900 w-32">Tinggi Ayah</span>
                        <span>: {{ $patient->tinggi_ayah ? $patient->tinggi_ayah . ' cm' : '-' }}</span>
                    </div>
                    <div class="">
                        <span class="font-medium text-gray-900 w-32">Tinggi Ibu</span>
                        <span>: {{ $patient->tinggi_ibu ? $patient->tinggi_ibu . ' cm' : '-' }}</span>
                    </div>
                </div>

                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">TPG</span>
                    <span>:
                        @if ($interpretasiGizi['tinggi_potensi_genetik']['tpg'])
                            {{ $interpretasiGizi['tinggi_potensi_genetik']['tpg'] }} cm
                            ({{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_bawah'] }}
                            –
                            {{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_atas'] }}
                            cm)
                        @else
                            -
                        @endif
                    </span>
                </div>

            </div>

            {{-- Column 2: Parent Heights --}}
            <div class="grid grid-cols-1 gap-3 text-sm text-gray-700 leading-relaxed">
                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Usia Kehamilan (GA)</span>
                    <span>: {{ $patient->usia_kehamilan_minggu }} mg</span>
                </div>
                <div class="flex items-center">
                    @php
                        // Only calculate age conversion once per row
                        [$tahun, $bulan, $hari] = convertDaysToYear(
                            $latestAntro->tgl_periksa ?? now(),
                            $latestAntro->total_usia_hari ?? 0,
                        );
                    @endphp
                    <span class="font-medium text-gray-900 w-32">Usia Kronologis</span>
                    <span>: {{ $tahun }} th {{ $bulan }} bl {{ $hari }}
                        hr</span>
                </div>
                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Usia Koreksi</span>
                    <span>:
                        @if ($latestAntro->usia_koreksi_total_hari && $latestAntro->usia_koreksi_total_hari != 0)
                            @php
                                // Only calculate correction age when needed
                                [$tahunKoreksi, $bulanKoreksi, $hariKoreksi] = convertDaysToYear(
                                    $latestAntro->tgl_periksa ?? now(),
                                    $latestAntro->usia_koreksi_total_hari,
                                );
                            @endphp
                            {{ $tahunKoreksi }} th {{ $bulanKoreksi }} bl
                            {{ $hariKoreksi }}
                            hr
                        @elseif(
                            $latestAntro->total_usia_hari == 0 ||
                                $latestAntro->usia_koreksi_total_hari == 0 ||
                                is_null($latestAntro->usia_koreksi_total_hari))
                            0
                        @else
                            0
                        @endif
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Usia Paska Menstruasi (PMA)</span>
                    <span>:
                        @if (
                            !is_null($latestAntro->usia_gestasi_total_hari) &&
                                $latestAntro->usia_gestasi_total_hari != 0 &&
                                $latestAntro->usia_gestasi_total_hari <= 448)
                            @php
                                // Only calculate gestational age when needed
                                [$mingguGestasi, $hariGestasi] = convertDaysToWeek(
                                    $latestAntro->tgl_periksa ?? now(),
                                    $latestAntro->usia_gestasi_total_hari,
                                );

                                // Apply rounding rule: if days >= 4, round up to next complete week
                                // if ($hariGestasi >= 4) {
                                //     $mingguGestasi += 1;
                                //     $hariGestasi = 0;
                                // } elseif ($hariGestasi < 4) {
                                //     $hariGestasi = 0;
                                // }

                            @endphp
                            {{ $mingguGestasi }} mg{{ $hariGestasi > 0 ? " $hariGestasi hr" : '' }}
                        @elseif(
                            !is_null($latestAntro->usia_gestasi_total_hari) &&
                                $latestAntro->usia_gestasi_total_hari != 0 &&
                                $latestAntro->usia_gestasi_total_hari > 448)
                            PMA > 64 mg
                        @else
                            0
                        @endif
                    </span>
                </div>

                <div class="flex items-center space-x-5">
                    <div class="">
                        <span class="font-medium text-gray-900 w-32">Usia Berat</span>
                        <span>: {{ $interpretasiGizi['weight_age'] ?? '-' }} bl</span>
                    </div>
                    <div class="">
                        <span class="font-medium text-gray-900 w-32">Usia Tinggi</span>
                        <span>: {{ $interpretasiGizi['height_age'] ?? '-' }} bl</span>
                    </div>
                </div>

                {{-- Empty div to maintain alignment with column 1 --}}
                {{-- <div class="flex items-center opacity-0">
                <span class="font-medium text-gray-900 w-32">Placeholder</span>
                <span>: Placeholder</span>
            </div> --}}
            </div>

            {{-- Column 3: Contact Information --}}
            <div class="grid grid-cols-1 gap-3 text-sm text-gray-700 leading-relaxed">
                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Berat Badan</span>
                    <span>: {{ $latestAntro->berat_badan ? $latestAntro->berat_badan . ' kg' : '-' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Tinggi Badan</span>
                    <span>: {{ $latestAntro->tinggi_badan ? $latestAntro->tinggi_badan . ' cm' : '-' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Lingkar Kepala</span>
                    <span>: {{ $latestAntro->lingkar_kepala ? $latestAntro->lingkar_kepala . ' cm' : '-' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="font-medium text-gray-900 w-32">Berat Badan Ideal</span>
                    <span>:
                        {{ isset($interpretasiGizi['bbtb']) && isset($interpretasiGizi['bbtb']['bb_ideal']) ? $interpretasiGizi['bbtb']['bb_ideal'] . ' kg' : '*' }}</span>
                </div>
                {{-- Empty div to maintain alignment with column 1 --}}
                <div class="flex items-center opacity-0">
                    <span class="font-medium text-gray-900 w-32">Placeholder</span>
                    <span>: Placeholder</span>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="px-4 border-b border-gray-200">
        </div>

        <!-- Intepretasi Gizi -->
        {{-- Jika prematur dan US <= 6 bln --}}
        @if (
            $patient->usia_kehamilan_minggu <= 36 &&
                $patient->usia_kehamilan_minggu >= 27 &&
                $latestAntro->total_usia_hari <= 730 &&
                $latestAntro->usia_gestasi_total_hari != null &&
                $latestAntro->usia_gestasi_minggu < 64)
            <div
                class="max-w-full overflow-x-auto custom-scrollbar p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 overflow-y-auto">
                <table class="min-w-full col-span-2">
                    <thead>
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap" width="30%" colspan="3">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Intepretasi Gizi
                                            </span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Z Skor
                                            </span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap" colspan="3">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Percentil
                                            </span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                    <tbody>
                        {{-- BB/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Berat Badan Menurut Umur
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $latestAntro->berat_badan ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        kg
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['bb']['z_score']))
                                            {{ $interpretasiGizi['bb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['bb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center" colspan="3">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['bb']['percentil']))
                                            {{ $interpretasiGizi['bb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        {{-- TB/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Tinggi Badan Menurut Umur
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $latestAntro->tinggi_badan ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        cm
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['tb']['z_score']))
                                            {{ $interpretasiGizi['tb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['tb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center" colspan="3">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['tb']['percentil']))
                                            {{ $interpretasiGizi['tb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        {{-- LK/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Lingkar Kepala Menurut Umur
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $latestAntro->lingkar_kepala ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        cm
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['lk']['z_score']))
                                            {{ $interpretasiGizi['lk']['z_score'] == '-INF' ? '*' : $interpretasiGizi['lk']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center" colspan="3">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['lk']['percentil']))
                                            {{ $interpretasiGizi['lk']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        {{-- BB/TB --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Berat Badan Menurut Panjang Badan
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['bbtb']['z_score']))
                                            {{ $interpretasiGizi['bbtb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['bbtb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center" colspan="3">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['bbtb']['percentil']))
                                            {{ $interpretasiGizi['bbtb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Kenaikkan yg diharapkan --}}
                <table class="min-w-full col-span-1 ">
                    <thead>
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap" colspan="2">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Kenaikan Perminggu
                                            </span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                    <tbody>
                        {{-- BB/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap" colspan="2">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['kenaikan_per_minggu'], $interpretasiGizi['kenaikan_per_minggu']['bb']))
                                            {{ round($interpretasiGizi['kenaikan_per_minggu']['bb'], 2) }} kg
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>

                        {{-- TB/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap" colspan="2">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['kenaikan_per_minggu'], $interpretasiGizi['kenaikan_per_minggu']['tb']))
                                            {{ round($interpretasiGizi['kenaikan_per_minggu']['tb'], 2) }} cm
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>

                        {{-- LK/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap" colspan="2">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['kenaikan_per_minggu'], $interpretasiGizi['kenaikan_per_minggu']['lk']))
                                            {{ round($interpretasiGizi['kenaikan_per_minggu']['lk'], 2) }} cm
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div
                class="max-w-full overflow-x-auto custom-scrollbar pt-5 px-5 sm:px-6 sm:pt-6 grid grid-cols-1 lg:grid-cols-2 gap-6 overflow-y-auto !mb-0 !-pb-50">
                <table class="min-w-full col-span-2">
                    <thead>
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap" width="30%">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Intepretasi Gizi
                                            </span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap" width="10%">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Z Skor
                                            </span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap" width="10%">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Percentil
                                            </span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap" width="20%">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Status Gizi
                                            </span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap" width="30%" colspan="3">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Nilai Normal
                                            </span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                    <tbody>
                        {{-- BB/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Berat Badan Menurut Umur
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['bb']['z_score']))
                                            {{ $interpretasiGizi['bb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['bb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['bb']['percentil']))
                                            {{ $interpretasiGizi['bb']['percentil'] == '-INF' ? '*' : $interpretasiGizi['bb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $interpretasiGizi['bb']['kategori'] ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['bb']['batas_normal']['bawah']) ? $interpretasiGizi['bb']['batas_normal']['bawah'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['bb']['batas_normal']['atas']) ? $interpretasiGizi['bb']['batas_normal']['atas'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        kg
                                    </p>
                                </div>
                            </td>
                        </tr>
                        {{-- TB/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Tinggi Badan Menurut Umur
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['tb']['z_score']))
                                            {{ $interpretasiGizi['tb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['tb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['tb']['percentil']))
                                            {{ $interpretasiGizi['tb']['percentil'] == '-INF' ? '*' : $interpretasiGizi['tb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $interpretasiGizi['tb']['kategori'] ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['tb']['batas_normal']['bawah']) ? $interpretasiGizi['tb']['batas_normal']['bawah'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['tb']['batas_normal']['atas']) ? $interpretasiGizi['tb']['batas_normal']['atas'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        cm
                                    </p>
                                </div>
                            </td>
                        </tr>
                        {{-- LK/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Lingkar Kepala Menurut Umur
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['lk']['z_score']))
                                            {{ $interpretasiGizi['lk']['z_score'] == '-INF' ? '*' : $interpretasiGizi['lk']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['lk']['percentil']))
                                            {{ $interpretasiGizi['lk']['percentil'] == '-INF' ? '*' : $interpretasiGizi['lk']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $interpretasiGizi['lk']['kategori'] ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['lk']['batas_normal']['bawah']) ? $interpretasiGizi['lk']['batas_normal']['bawah'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['lk']['batas_normal']['atas']) ? $interpretasiGizi['lk']['batas_normal']['atas'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        cm
                                    </p>
                                </div>
                            </td>
                        </tr>
                        {{-- BB/TB --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Berat Badan Menurut Tinggi Badan
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['bbtb']['z_score']))
                                            {{ $interpretasiGizi['bbtb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['bbtb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['bbtb']['percentil']))
                                            {{ $interpretasiGizi['bbtb']['percentil'] == '-INF' ? '*' : $interpretasiGizi['bbtb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $interpretasiGizi['bbtb']['kategori'] ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['bbtb']['batas_normal']['bawah']) ? $interpretasiGizi['bbtb']['batas_normal']['bawah'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['bbtb']['batas_normal']['atas']) ? $interpretasiGizi['bbtb']['batas_normal']['atas'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        kg
                                    </p>
                                </div>
                            </td>
                        </tr>
                        {{-- IMT/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Indeks Massa Tubuh Menurut Umur
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['imt']['z_score']))
                                            {{ $interpretasiGizi['imt']['z_score'] == '-INF' ? '*' : $interpretasiGizi['imt']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['imt']['percentil']))
                                            {{ $interpretasiGizi['imt']['percentil'] == '-INF' ? '*' : $interpretasiGizi['imt']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $interpretasiGizi['imt']['kategori'] ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['imt']['batas_setara']['bawah']) ? $interpretasiGizi['imt']['batas_setara']['bawah'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ isset($interpretasiGizi['imt']['batas_setara']['atas']) ? $interpretasiGizi['imt']['batas_setara']['atas'] : '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        kg
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Kenaikkan yg diharapkan --}}
                <table class="min-w-full col-span-1">
                    <thead>
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap" colspan="3">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-md">
                                                Kenaikan yang diharapkan
                                            </span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                    <tbody>
                        {{-- BB/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap" width="62.5%" >
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Berat Badan
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['kenaikan']['bb_bawah'], $interpretasiGizi['kenaikan']['bb_atas']))
                                            {{ $interpretasiGizi['kenaikan']['bb_bawah'] }} -
                                            {{ $interpretasiGizi['kenaikan']['bb_atas'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $interpretasiGizi['kenaikan']['bb_unit'] ?? '-' }}
                                    </p>
                                </div>
                            </td>
                        </tr>

                        {{-- TB/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Tinggi Badan
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['kenaikan']['tb_bawah'], $interpretasiGizi['kenaikan']['tb_atas']))
                                            {{ $interpretasiGizi['kenaikan']['tb_bawah'] }} -
                                            {{ $interpretasiGizi['kenaikan']['tb_atas'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $interpretasiGizi['kenaikan']['tb_unit'] ?? '-' }}
                                    </p>
                                </div>
                            </td>
                        </tr>

                        {{-- LK/U --}}
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Lingkar Kepala
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        @if (isset($interpretasiGizi['kenaikan']['lk_bawah'], $interpretasiGizi['kenaikan']['lk_atas']))
                                            {{ $interpretasiGizi['kenaikan']['lk_bawah'] }} -
                                            {{ $interpretasiGizi['kenaikan']['lk_atas'] }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        {{ $interpretasiGizi['kenaikan']['lk_unit'] ?? '-' }}
                                    </p>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr><td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr><td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-gray-700 text-theme-sm">
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Potensi Tinggi Genetik -->
        <div
            class="max-w-full overflow-x-auto custom-scrollbar p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 overflow-y-auto !pt-0 !mt-0">
            <table class="min-w-full col-span-2">
                <thead>
                    <thead class="border-gray-100 border-y bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 whitespace-nowrap" colspan="7">
                                <div class="flex items-center justify-center">
                                    <div>
                                        <span class="block font-medium text-gray-500 text-theme-md">
                                            Potensi Tinggi Genetik
                                        </span>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                <tbody>
                    {{-- BB/U --}}
                    <tr>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-gray-700 text-theme-sm">
                                    Tinggi Ayah (cm)
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $patient->tinggi_ayah ?? '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    cm
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                    </tr>
                    {{-- TB/U --}}
                    <tr>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-gray-700 text-theme-sm">
                                    Tinggi Ibu (cm)
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $patient->tinggi_ibu ?? '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    cm
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                    </tr>
                    {{-- LK/U --}}
                    <tr>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-gray-700 text-theme-sm">
                                    Tinggi Potensi Genetik (cm)
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tinggi_potensi_genetik']['tpg'] ?? '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    cm
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tinggi_potensi_genetik']['z_tpg'] ? 'Z = ' . round($interpretasiGizi['tinggi_potensi_genetik']['z_tpg'], 2) : '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_bawah'] ?? '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_atas'] ?? '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    cm
                                </p>
                            </div>
                        </td>
                    </tr>
                    {{-- Tinggi Potensi Genetik --}}
                    <tr>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-gray-700 text-theme-sm">
                                    Tinggi Potensi Genetik (cm) Usia {{ $interpretasiGizi['usia_bulan'] }} bl
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tbug']['nilai'] ?? '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    cm
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tbug']['batas']['bawah'] ?? '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tbug']['batas']['atas'] ?? '-' }}
                                </p>
                            </div>
                        </td>

                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    cm
                                </p>
                            </div>
                        </td>
                    </tr>
                    {{-- TPG --}}
                    <tr>
                        <td class="px-6 py-3 whitespace-nowrap" colspan="3">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tbug']['status'] ?? '-' }}
                                </p>
                            </div>
                        </td>

                        <td class="px-6 py-3 whitespace-nowrap" colspan="4">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tbug']['status_range'] ?? '-' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    {{-- Proyeksi Tinggi Akhir --}}
                    <tr>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-gray-700 text-theme-sm">
                                    Proyeksi Tinggi Akhir
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    {{ $interpretasiGizi['tbug']['proyeksi_tinggi_akhir'] ?? '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                    cm
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>

                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="text-gray-700 text-theme-sm">
                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Header Section -->
        <div class="px-4 border-b border-gray-200">
        </div>

        <!-- Laju Pertumbuhan -->
        <div class="max-w-full overflow-x-auto custom-scrollbar p-5 sm:p-6">
            <table class="min-w-full">
                <thead class="border-gray-100 border-y bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap" colspan="9">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-md">
                                        Laju Pertumbuhan
                                    </span>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        No
                                    </span>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        Tgl Periksa
                                    </span>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        Δ T
                                    </span>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        US
                                    </span>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        TB (cm)
                                    </span>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        Z
                                    </span>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        Δ Z
                                    </span>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        Laju Pertumbuhan<br>(cm/thn)
                                    </span>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        N LJUG<br>(cm/thn)
                                    </span>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div>
                                    <span class="block font-medium text-gray-500 text-theme-sm">
                                        TBUG
                                    </span>
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($interpretasiGizi['laju_pertumbuhan']['data_points']))
                        @php
                            // Extract data points for display in reverse order (newest first)
                            $dataPoints = array_reverse($interpretasiGizi['laju_pertumbuhan']['data_points']);
                            $growthMeasurements = $interpretasiGizi['laju_pertumbuhan']['pertumbuhan'] ?? [];
                            // Reverse growth measurements to match reversed data points
                            if (!empty($growthMeasurements)) {
                                $growthMeasurements = array_reverse($growthMeasurements);
                            }
                            $totalPoints = count($dataPoints);
                        @endphp

                        {{-- {{ dd($interpretasiGizi['laju_pertumbuhan']['data_points']) }} --}}

                        @foreach ($dataPoints as $index => $point)
                            @php
                                $rowNumber = $index + 1;
                                // Calculate the growth index - growth data is between points, so we need to map differently
                                // For the newest examination (index 0), we need growth data from the first element in the reversed growth array
                                // For the oldest examination (last index), there's no previous data point, so no growth data
                                $growthIndex = $index < $totalPoints - 1 ? $index : null;
                                $growth =
                                    $growthIndex !== null && isset($growthMeasurements[$growthIndex])
                                        ? $growthMeasurements[$growthIndex]
                                        : null;
                            @endphp

                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <span class="text-gray-700 text-theme-sm">{{ $rowNumber }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ \Carbon\Carbon::parse($point['tgl_periksa'])->translatedFormat('d M y') }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Cell for delta_t --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $growth && isset($growth['delta_t']) ? $growth['delta_t'] . ' hari' : '-' }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Usia sebenarnya --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $point['usia_sebenarnya'] ?? '-' }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Tinggi badan --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $point['tinggi_badan'] ?? '-' }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Z Score --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ isset($point['z_score']) ? round($point['z_score'], 2) : '-' }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Delta Z --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $growth && isset($growth['delta_z']) ? round($growth['delta_z'], 2) : '-' }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Laju Pertumbuhan Aktual --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $growth && isset($growth['aktual']['nilai']) ? $growth['aktual']['nilai'] : '-' }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Nilai Normal --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $growth && isset($growth['normal']['nilai_normal']) ? $growth['normal']['nilai_normal'] : '-' }}
                                        </p>
                                    </div>
                                </td>

                                {{-- TBUG --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $point['tbug'] ?? '-' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-700 text-theme-sm">
                                        Data laju pertumbuhan tidak tersedia. Pilih minimal 2 titik pemeriksaan untuk
                                        melihat
                                        laju pertumbuhan.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <!-- Header Section -->
        <div class="px-4 border-b border-gray-200">
        </div>
        <div class="max-w-full overflow-x-auto custom-scrollbar p-5 sm:p-6">
            <div class="">
                <h3 class="text-md font-semibold text-gray-800">Pemeriksa : {{ Auth::user()->name }}</h3>
                <p class="text-sm font-semibold text-gray-600 mt-5">Catatan :</p>
                <div class="text-sm text-gray-600 space-y-1">
                    <div class="flex gap-2">
                        <span class="w-5 text-right">*</span>
                        <span>: Nilai BB TB LK di luar batas wajar</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-5 text-right">**</span>
                        <span>: Tidak dinilai pada usia > 60 Bulan</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-5 text-right">***</span>
                        <span>: Tidak dinilai pada usia > 10 Tahun</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
