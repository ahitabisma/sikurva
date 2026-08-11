<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* background: #f1f3f5; */
            background: #ffffff;
            font-size: 12px !important;
            /* margin: 0;
            padding: 10px;
            margin: 3px;
            border-radius: 12px;
            border: 1px solid #dee2e6; */
            /* box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); */
        }

        .page {
            background: #ffffff;
            /* padding: 15px 15px; */
            /* Reduced from 20px 20px */
            /* margin-bottom: 50px; */
            position: relative;
            page-break-inside: avoid;
            /* margin-bottom: 30px; */
        }

        .pdf-header {
            width: 100%;
            margin-bottom: 15px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1976d2, #0d47a1);
            color: white;
            text-align: center;
            line-height: 60px;
            font-size: 36px;
            font-weight: bold;
            margin: 0 auto;
            display: block;
        }

        .header-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: contain;
            background-color: #ffffff;
            border: 2px solid #1976d2;
            margin: 0 auto;
            display: block;
        }

        .institution-name {
            font-size: 19px;
            font-weight: bold;
            color: #1976d2;
            margin: 0 0 5px 0;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .institution-address {
            font-size: 16px;
            font-weight: bold;
            color: #555555;
            margin: 0;
            text-align: left;
        }

        .account-badge {
            display: inline-block;
            background: #1976d2;
            color: white;
            padding: 5px 10px;
            margin: 0;
            /* Removed side margin to ensure proper centering */
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 0.5px;
            border: 1px solid #0d47a1;
        }

        .header-divider {
            height: 1px;
            background: rgb(130, 130, 130);
            margin-top: 8px;
            border-radius: 1.5px;
        }

        .logo-column {
            width: 25%;
            text-align: center;
            vertical-align: middle;
            padding-right: 15px;
        }

        .institution-column {
            width: 75%;
            text-align: left;
            vertical-align: middle;
        }

        .logo-cell {
            width: 8%;
            vertical-align: middle;
            padding-right: 10px;
        }

        .logo-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1976d2, #0d47a1);
            color: white;
            text-align: center;
            line-height: 60px;
            /* Centers the text vertically */
            font-size: 36px;
            font-weight: bold;
            margin: 0 auto;
            /* Centers the logo horizontally */
            display: block;
        }

        .header-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: contain;
            background-color: #ffffff;
            border: 2px solid #1976d2;
            margin: 0 auto;
            /* Centers the logo horizontally */
            display: block;
        }

        .info-cell {
            width: 65%;
            vertical-align: middle;
            padding-left: 5px;
        }

        .account-cell {
            width: 20%;
            text-align: right;
            vertical-align: middle;
        }

        .account-role {
            display: inline-block;
        }

        .header-divider {
            height: 1px;
            background: rgb(130, 130, 130);
            margin-top: 8px;
            border-radius: 1.5px;
        }

        /* First page specific styling */
        .first-page-content {
            font-size: 10px !important;
        }

        .first-page-content .header {
            margin-bottom: 5px;
            /* Reduced from 10px */
        }

        .first-page-content .table-container {
            padding: 5px;
            /* Reduced from 15px */
        }

        .first-page-content table.assessment-table {
            margin-bottom: 5px;
            /* Reduced from 20px */
        }

        .first-page-content table.assessment-table th {
            padding: 3px 0;
            /* Reduced from 12px 15px */
            font-size: 12px;
            /* Reduced from 14px */
        }

        .first-page-content table.assessment-table td {
            padding: 1px 0;
            /* Reduced from 10px 15px */
            font-size: 12px;
            /* Reduced from 14px */
        }

        .first-page-content .notes-section {
            padding: 0 5px;
            /* Reduced from 15px */
        }

        .first-page-content .notes-section p {
            font-size: 10px !important;
            /* Reduced from 14px */
            /* margin-bottom: 5px !important; */
            /* Reduced from 10px */
        }

        .first-page-content .note-item {
            font-size: 10px;
            /* Reduced from 14px */
            margin-bottom: 2px;
            /* Reduced from 5px */
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .clinic-info {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            text-align: center;
        }

        .clinic-logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .clinic-text h1 {
            font-size: 22px;
            margin: 5px 0;
            color: #1565c0;
        }

        .clinic-text p {
            font-size: 13px;
            margin: 4px 0;
            color: #555;
        }


        .footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            /* margin-top: 10px; */
            /* background: rgb(130, 130, 130);
            margin-top: 8px;
            border-radius: 1.5px; */
        }

        /* Add to the existing <style> section */

        /* First page footer specific styles */
        .first-page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: auto;
            margin: 0 0 5px 0;
            padding: 5px 0 0 0;
            font-size: 12px;
            color: #000;
            text-align: center;
            /* border-top: 1px solid #dee2e6; */
        }

        .footer-disclaimer {
            font-style: italic;
            margin-bottom: 4px;
            font-size: 10px;
            color: #555;
            text-align: left;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .footer-left {
            width: 33%;
            text-align: left;
        }

        .footer-center {
            width: 33%;
            text-align: center;
            font-weight: bold;
        }

        .footer-right {
            width: 33%;
            text-align: right;
            font-size: 10px;
            font-style: italic;
        }

        .footer-logo {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            object-fit: contain;
            background-color: #ffffff;
        }

        .footer-page-number {
            font-size: 10px;
            text-align: center;
        }

        .biodata {
            margin-top: 20px;
        }

        .biodata h2 {
            color: #343a40;
        }

        .biodata table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .biodata td {
            padding: 8px 10px;
            border-bottom: 1px solid #e9ecef;
            font-size: 15px;
        }

        .image-container {
            padding-top: 70px;
        }

        .image-container img {
            width: 100%;
            height: auto;
            max-height: 70vh;
            border-radius: 5px;
        }

        .image-container-ads img {
            width: 90%;
            height: auto;
            max-width: 100%;
            max-height: 60vh;
            border-radius: 5px;
            display: block;
            overflow: hidden;
        }

        /* Add specific styles for ads images */
        .image-container-ads img.ads-image {
            max-width: 100%;
            /* Reduced from 80% */
            max-height: 95vh;
            margin: 0 auto;
            display: block;
            /* Important for proper centering */
            object-fit: contain;
        }

        .content-block {
            page-break-inside: avoid;
        }

        .clinic-wrapper {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .clinic-logo-wrapper,
        .clinic-title-wrapper {
            display: table-cell;
            vertical-align: middle;
        }

        .clinic-logo-wrapper {
            width: 48%;
            text-align: right;
            padding-right: 10px;
        }

        .clinic-title-wrapper {
            width: 60%;
            text-align: left;
            padding-left: 10px;
        }

        .clinic-logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .clinic-title-wrapper h1 {
            font-size: 22px;
            margin: 0;
            color: #1565c0;
        }

        /* Assessment page styles */
        .assessment-page {
            background: #ffffff;
            padding: 20px;
            page-break-inside: avoid;
        }

        .section-header {
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 15px;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .patient-info {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            padding: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .info-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .info-item {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #444;
        }

        .table-container {
            padding: 5px 0;
            overflow-x: auto;
        }

        table.assessment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.assessment-table thead {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
        }

        table.assessment-table th {
            padding: 5px 15px;
            font-size: 12px;
            font-weight: 300;
            color: #555;
            text-align: center;
        }

        table.assessment-table td {
            padding: 10px 15px;
            font-size: 12px;
            color: #333;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
        }

        table.assessment-table td:first-child {
            text-align: left;
        }

        .text-sm {
            font-size: 12px;
        }

        .text-md {
            font-size: 16px;
        }

        .font-semibold {
            font-weight: 600;
        }

        .notes-section {
            padding: 0 5px;
        }

        .notes-list {
            margin-top: 10px;
        }

        .note-item {
            display: flex;
            gap: 10px;
            margin-bottom: 5px;
            font-size: 12px;
            color: #555;
        }

        .note-marker {
            width: 15px;
            text-align: right;
        }

        /* Utility classes */
        .space-y-1>*+* {
            margin-top: 5px;
        }

        .mb-5 {
            margin-bottom: 20px;
        }

        /* Patient info table styles */
        .patient-info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            /* Reduced from 15px 0 */
        }

        .patient-info-table td {
            padding: 0 3px;
            /* Reduced from 8px 6px */
            font-size: 12px;
            /* Reduced from 14px */
            color: #444;
            vertical-align: top;
        }

        .info-label {
            color: black;
            width: 100;
            /* Reduced from 120px */
        }

        .info-label.second-col {
            width: 150;
            /* Reduced from 120px */
        }

        .patient-info-section {
            padding-right: 5px;
            /* Reduced from 10px */
        }

        .patient-info-section:nth-child(1) {
            width: 38%;
        }

        .patient-info-section:nth-child(2) {
            width: 38%;
        }

        .patient-info-section:nth-child(3) {
            width: 24%;
        }


        /* Combined interpretation table */
        .combined-table-container {
            display: flex;
            gap: 5px;
            /* Reduced from 15px to save space */
        }

        .combined-table-container .assessment-table {
            flex: 1;
        }

        .combined-table-container .assessment-table:first-child {
            flex: 2;
            /* Changed from 3 to 2 for a 66:33 ratio */
        }

        /* Add to your existing styles */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: auto;
            margin: 0 0 8px 0;
            padding: 5px 0 0 0;
            font-size: 12px;
            color: #555;
            border-color: 1px solid rgb(130, 130, 130);
            border-radius: 1.5px;
        }

        /* Add styles for page numbers */
        @page {
            margin: 20px 20px 0 20px;
            counter-increment: page;
        }

        body {
            counter-reset: page {{ $totalPages ?? 1 }};
        }

        /* For DOMPDF page counting */
        .pagenum:after {
            content: attr(data-page);
        }

        .pagetotal:after {
            content: attr(data-total);
        }
    </style>
</head>

<body>
    {{-- First page with patient information --}}
    <div class="page">
        <div class="content-block first-page-content">
            <div class="pdf-header">
                <table class="header-table">
                    <tr>
                        <!-- Left column: Logo and Institution -->
                        @if (Auth::user()->is_nakes && Auth::user()->instansi->header && !Auth::user()->instansi->is_support_header)
                            <td style="width: 80%; text-align: left; vertical-align: middle;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="width: 70px; text-align: center; vertical-align: middle;">
                                            @if (isset($logo) && !empty($logo))
                                                <img src="{{ public_path('logo.png') }}"
                                                    class="header-logo" alt="Sikurva.com">
                                            @else
                                                <div class="logo-placeholder">Sikurva.com</div>
                                            @endif
                                        </td>
                                        <td style="padding-left: 10px; vertical-align: middle;">
                                            <h1 class="institution-name">
                                                {{ Auth::user()->is_nakes ? strtoupper(Auth::user()->instansi->name) : '' }}
                                            </h1>
                                            <p class="institution-address">
                                                {{ strtoupper(Auth::user()->address) ?? '-' }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Right column: Account Information (centered) -->
                            <td style="width: 20%; text-align: center; vertical-align: middle;">
                                <div class="account-badge" style="display: inline-block; float: none;">
                                    <span
                                        class="account-role">{{ strtoupper(Auth::user()->name) ?? 'SUPER ADMIN' }}</span>
                                </div>
                            </td>
                        @elseif(!Auth::user()->is_nakes && !Auth::user()->is_support_header)
                            <td style="width: 80%; text-align: left; vertical-align: middle;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="width: 70px; text-align: center; vertical-align: middle;">
                                            @if (isset($logo) && !empty($logo))
                                                <img src="{{ public_path('logo.png') }}"
                                                    class="header-logo" alt="Sikurva.com">
                                            @else
                                                <div class="logo-placeholder">Sikurva.com</div>
                                            @endif
                                        </td>
                                        <td style="padding-left: 10px; vertical-align: middle;">
                                            <p class="institution-address">
                                                @if (Auth::user()->header && !Auth::user()->is_support_header)
                                                    {{ strtoupper(Auth::user()->address) ?? '-' }}
                                                @else
                                                    {{ $pdfSetting->isNotEmpty() ? strtoupper($pdfSetting->where('key', 'alamat')->first()->value) ?? '-' : '' }}
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Right column: Account Information (centered) -->
                            <td style="width: 20%; text-align: center; vertical-align: middle;">
                                <div class="account-badge" style="display: inline-block; float: none;">
                                    @if (Auth::user()->header && !Auth::user()->is_support_header)
                                        <span
                                            class="account-role">{{ strtoupper(Auth::user()->name) ?? 'SUPER ADMIN' }}</span>
                                    @else
                                        {{ strtoupper($superAdmin->name) ?? 'SUPER ADMIN' }}
                                    @endif
                                </div>
                            </td>
                        @else
                            <td style="width: 80%; text-align: left; vertical-align: middle;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="width: 70px; text-align: center; vertical-align: middle;">
                                            @if (isset($logo) && !empty($logo))
                                                <img src="{{ public_path('logo.png') }}"
                                                    class="header-logo" alt="Logo">
                                            @else
                                                <div class="logo-placeholder">Logo</div>
                                            @endif
                                        </td>
                                        <td style="padding-left: 10px; vertical-align: middle;">
                                            <h1 class="institution-name">
                                                {{ $pdfSetting->isNotEmpty() ? strtoupper($pdfSetting->where('key', 'nama_instansi')->first()->value) ?? 'Sikurva' : 'Sikurva' }}
                                            </h1>
                                            <p class="institution-address">
                                                {{ $pdfSetting->isNotEmpty() ? strtoupper($pdfSetting->where('key', 'alamat')->first()->value) ?? '-' : '' }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Right column: Account Information (centered) -->
                            <td style="width: 20%; text-align: center; vertical-align: middle;">
                                <div class="account-badge" style="display: inline-block; float: none;">
                                    <span
                                        class="account-role">{{ strtoupper($superAdmin->name) ?? 'SUPER ADMIN' }}</span>
                                </div>
                            </td>
                        @endif
                    </tr>
                </table>
                <div class="header-divider"></div>
            </div>

            <!-- Patient Information as Table -->
            <table class="patient-info-table">
                <tr>
                    <!-- Column 1: Basic Patient Info -->
                    <td class="patient-info-section">
                        <table>
                            <tr>
                                <td class="info-label">Nama Anak</td>
                                <td>: {{ $patient->nama }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Jenis Kelamin</td>
                                <td>: {{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Tanggal Lahir</td>
                                <td>: {{ \Carbon\Carbon::parse($patient->tgl_lahir)->translatedFormat('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Tanggal Periksa</td>
                                <td>: {{ \Carbon\Carbon::parse($latestAntro->tgl_periksa)->translatedFormat('d M Y') }}
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- Column 2: Age Information -->
                    <td class="patient-info-section">
                        <table>
                            <tr>
                                <td class="info-label second-col">Usia Kehamilan</td>
                                <td>: {{ $patient->usia_kehamilan_minggu }} mg</td>
                            </tr>
                            <tr>
                                <td class="info-label second-col">Usia Kronologis</td>
                                <td>:
                                    @php
                                        // Only calculate age conversion once per row
                                        [$tahun, $bulan, $hari] = convertDaysToYear(
                                            $latestAntro->tgl_periksa ?? now(),
                                            $latestAntro->total_usia_hari ?? 0,
                                        );
                                    @endphp
                                    {{ $tahun }} th {{ $bulan }} bl {{ $hari }} hr
                                </td>
                            </tr>
                            <tr>
                                <td class="info-label second-col">Usia Koreksi</td>
                                <td>:
                                    @if ($latestAntro->usia_koreksi_total_hari && $latestAntro->usia_koreksi_total_hari != 0)
                                        @php
                                            // Only calculate correction age when needed
                                            [$tahunKoreksi, $bulanKoreksi, $hariKoreksi] = convertDaysToYear(
                                                $latestAntro->tgl_periksa ?? now(),
                                                $latestAntro->usia_koreksi_total_hari,
                                            );
                                        @endphp
                                        {{ $tahunKoreksi }} th {{ $bulanKoreksi }} bl {{ $hariKoreksi }} hr
                                    @elseif(
                                        $latestAntro->total_usia_hari == 0 ||
                                            $latestAntro->usia_koreksi_total_hari == 0 ||
                                            is_null($latestAntro->usia_koreksi_total_hari))
                                        0
                                    @else
                                        0
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="info-label second-col">Usia Paska Menstruasi</td>
                                <td>:
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
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Combined Interpretation Table -->
            {{-- Untuk Awam --}}
            @if (!Auth::user()->is_nakes && Auth::user()->hasRole('admin'))
                {{-- Untuk prematur --}}
                @if (
                    $patient->usia_kehamilan_minggu <= 36 &&
                        $patient->usia_kehamilan_minggu >= 27 &&
                        $latestAntro->total_usia_hari <= 730 &&
                        $latestAntro->usia_gestasi_total_hari != null &&
                        $latestAntro->usia_gestasi_minggu < 64)
                    <div class="table-container">
                        <table class="assessment-table">
                            <thead>
                                <tr>
                                    <th style="width:30%;" colspan="2">Intepretasi Gizi</th>
                                    <th style="width:70%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Berat Badan Menurut Umur</td>
                                    <td style="text-align: center;">
                                        {{ $latestAntro->berat_badan ? $latestAntro->berat_badan . ' kg' : '-' }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Tinggi Badan Menurut Umur</td>
                                    <td style="text-align: center;">
                                        {{ $latestAntro->tinggi_badan ? $latestAntro->tinggi_badan . ' cm' : '-' }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Lingkaran Kepala Menurut Umur</td>
                                    <td style="text-align: center;">
                                        {{ $latestAntro->lingkar_kepala ? $latestAntro->lingkar_kepala . ' cm' : '-' }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Potensi Tinggi Genetik -->
                    <div class="table-container">
                        <table class="assessment-table" style="float: left; margin-right: 1%;">
                            <thead>
                                <tr>
                                    <th style="width:30%;" colspan="2">Interpretasi Tinggi</th>
                                    <th style="width:70%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tinggi Ayah</td>
                                    <td style="text-align: center;">
                                        {{ $patient->tinggi_ayah ? $patient->tinggi_ayah . ' cm' : '-' }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Tinggi Ibu</td>
                                    <td style="text-align: center;">
                                        {{ $patient->tinggi_ibu ? $patient->tinggi_ibu . ' cm' : '-' }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Mid Parental Height</td>
                                    <td style="text-align: center;">
                                        @if (isset($interpretasiGizi['tinggi_potensi_genetik']['tpg']))
                                            {{ $interpretasiGizi['tinggi_potensi_genetik']['tpg'] }} cm /
                                            {{ $interpretasiGizi['tinggi_potensi_genetik']['z_tpg'] ? 'Z = ' . round($interpretasiGizi['tinggi_potensi_genetik']['z_tpg'], 2) : '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Tinggi Potensi Genetik</td>
                                    <td>
                                        @if (isset($interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_bawah']) &&
                                                isset($interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_atas']))
                                            {{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_bawah'] }}
                                            -
                                            {{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_atas'] }}
                                            cm
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="notes-section">
                        <p style="font-size: 10px; font-weight: 600; color: #555; margin-bottom: 3px;">Catatan :</p>

                        <table style="width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 8px;">
                            <tr>
                                <td style="width: 3%; vertical-align: top;">*</td>
                                <td style="width: 45%; vertical-align: top;">: Nilai BB TB LK di luar batas wajar</td>
                                <td style="width: 6%; vertical-align: top;">Dicatat oleh</td>
                                <td style="width: 30%; vertical-align: top;">: {{ $patient->created_by_name }}</td>
                            </tr>
                            <tr>
                                <td style="width: 3%; vertical-align: top;">**</td>
                                <td style="width: 45%; vertical-align: top;">: Tidak dinilai pada usia > 60 Bulan</td>
                                <td style="width: 6%; vertical-align: top;">Dikirim oleh</td>
                                <td style="width: 30%; vertical-align: top;">: {{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">***</td>
                                <td style="vertical-align: top;">: Tidak dinilai pada usia > 10 Tahun</td>
                            </tr>
                        </table>
                    </div>
                @else
                    {{-- Normal --}}
                    <div class="table-container">
                        <table class="assessment-table">
                            <thead>
                                <tr>
                                    <th style="width:35%;" colspan="2">Intepretasi Gizi</th>
                                    <th style="width:20%;">Status Gizi</th>
                                    <th style="width:45%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Berat Badan Menurut Umur</td>
                                    <td style="text-align: center;">
                                        {{ $latestAntro->berat_badan ? $latestAntro->berat_badan . ' kg' : '-' }}</td>
                                    <td>{{ $interpretasiGizi['bb']['kategori'] ?? '-' }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Tinggi Badan Menurut Umur</td>
                                    <td style="text-align: center;">
                                        {{ $latestAntro->tinggi_badan ? $latestAntro->tinggi_badan . ' cm' : '-' }}
                                    </td>
                                    <td>{{ $interpretasiGizi['tb']['kategori'] ?? '-' }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Lingkaran Kepala Menurut Umur</td>
                                    <td style="text-align: center;">
                                        {{ $latestAntro->lingkar_kepala ? $latestAntro->lingkar_kepala . ' cm' : '-' }}
                                    </td>
                                    <td>{{ $interpretasiGizi['lk']['kategori'] ?? '-' }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Berat Badan Menurut Tinggi</td>
                                    <td></td>
                                    <td>{{ $interpretasiGizi['bbtb']['kategori'] ?? '-' }}</td>
                                    <td></td>
                                </tr>
                                <!-- IMT/U -->
                                <tr>
                                    <td>Indeks Massa Tubuh Menurut Umur</td>
                                    <td>
                                        @if ($latestAntro && $latestAntro->imt)
                                            {{ $latestAntro->imt }} kg/cm<sup>2</sup>
                                        @endif
                                    </td>
                                    <td>{{ $interpretasiGizi['imt']['kategori'] ?? '-' }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Potensi Tinggi Genetik -->
                    <div class="table-container">
                        <table class="assessment-table" style="float: left; margin-right: 1%;">
                            <thead>
                                <tr>
                                    <th style="width:30%;" colspan="2">Interpretasi Tinggi</th>
                                    <th style="width:70%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tinggi Ayah</td>
                                    <td style="text-align: center;">
                                        {{ $patient->tinggi_ayah ? $patient->tinggi_ayah . ' cm' : '-' }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Tinggi Ibu</td>
                                    <td style="text-align: center;">
                                        {{ $patient->tinggi_ibu ? $patient->tinggi_ibu . ' cm' : '-' }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Mid Parental Height</td>
                                    <td style="text-align: center;">
                                        @if (isset($interpretasiGizi['tinggi_potensi_genetik']['tpg']))
                                            {{ $interpretasiGizi['tinggi_potensi_genetik']['tpg'] }} cm /
                                            {{ $interpretasiGizi['tinggi_potensi_genetik']['z_tpg'] ? 'Z = ' . round($interpretasiGizi['tinggi_potensi_genetik']['z_tpg'], 2) : '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Tinggi Potensi Genetik</td>
                                    <td>
                                        @if (isset($interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_bawah']) &&
                                                isset($interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_atas']))
                                            {{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_bawah'] }}
                                            -
                                            {{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_atas'] }}
                                            cm
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="notes-section">
                        <p style="font-size: 10px; font-weight: 600; color: #555; margin-bottom: 3px;">Catatan :</p>

                        <table style="width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 8px;">
                            <tr>
                                <td style="width: 3%; vertical-align: top;">*</td>
                                <td style="width: 45%; vertical-align: top;">: Nilai BB TB LK di luar batas wajar</td>
                                <td style="width: 6%; vertical-align: top;">Dicatat oleh</td>
                                <td style="width: 30%; vertical-align: top;">: {{ $patient->created_by_name }}</td>
                            </tr>
                            <tr>
                                <td style="width: 3%; vertical-align: top;">**</td>
                                <td style="width: 45%; vertical-align: top;">: Tidak dinilai pada usia > 60 Bulan</td>
                                <td style="width: 6%; vertical-align: top;">Dikirim oleh</td>
                                <td style="width: 30%; vertical-align: top;">: {{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">***</td>
                                <td style="vertical-align: top;">: Tidak dinilai pada usia > 10 Tahun</td>
                            </tr>
                        </table>
                    </div>
                @endif
            @else
                {{-- Untuk Nakes dan super admin --}}
                <div class="table-container">
                    <table class="assessment-table">
                        <thead>
                            <tr>
                                @if (
                                    $patient->usia_kehamilan_minggu <= 36 &&
                                        $patient->usia_kehamilan_minggu >= 27 &&
                                        $latestAntro->total_usia_hari <= 730 &&
                                        $latestAntro->usia_gestasi_total_hari != null &&
                                        $latestAntro->usia_gestasi_minggu < 64)
                                    {{-- Header for premature cases --}}
                                    <th style="width: 20%;" colspan="2">Intepretasi Gizi PMA</th>
                                    <th style="width: 10%;">Z-Score</th>
                                    <th style="width: 25%;" colspan="3">Percentil</th>
                                    <th style="width: 30%;" colspan="2">Kenaikan per minggu</th>
                                @else
                                    {{-- Header for standard cases --}}
                                    <th style="width: 35%;" colspan="2">Intepretasi Gizi</th>
                                    <th style="width: 7%;">Z-Score</th>
                                    <th style="width: 7%;">Percentil</th>
                                    <th style="width: 15%;">Status Gizi</th>
                                    <th style="width: 10%;">Nilai Normal</th>
                                    <th style="width: 25%;">Kenaikan</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (
                                $patient->usia_kehamilan_minggu <= 36 &&
                                    $patient->usia_kehamilan_minggu >= 27 &&
                                    $latestAntro->total_usia_hari <= 730 &&
                                    $latestAntro->usia_gestasi_total_hari != null &&
                                    $latestAntro->usia_gestasi_minggu < 64)
                                {{-- Content for premature cases --}}
                                <!-- BB/U -->
                                <tr>
                                    <td>Berat Badan Menurut Umur</td>
                                    <td style="text-align: center;">{{ $latestAntro->berat_badan ?? '-' }}</td>
                                    <td style="text-align: center;">kg</td>
                                    <td style="text-align: center;">
                                        @if (isset($interpretasiGizi['bb']['z_score']))
                                            {{ $interpretasiGizi['bb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['bb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: center;" colspan="3">
                                        @if (isset($interpretasiGizi['bb']['percentil']))
                                            {{ $interpretasiGizi['bb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: center;" colspan="2">
                                        @if (isset($interpretasiGizi['kenaikan_per_minggu'], $interpretasiGizi['kenaikan_per_minggu']['bb']))
                                            {{ round($interpretasiGizi['kenaikan_per_minggu']['bb'], 2) }} kg
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <!-- TB/U -->
                                <tr>
                                    <td>Tinggi Badan Menurut Umur</td>
                                    <td style="text-align: center;">{{ $latestAntro->tinggi_badan ?? '-' }}</td>
                                    <td style="text-align: center;">cm</td>
                                    <td style="text-align: center;">
                                        @if (isset($interpretasiGizi['tb']['z_score']))
                                            {{ $interpretasiGizi['tb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['tb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: center;" colspan="3">
                                        @if (isset($interpretasiGizi['tb']['percentil']))
                                            {{ $interpretasiGizi['tb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: center;" colspan="2">
                                        @if (isset($interpretasiGizi['kenaikan_per_minggu'], $interpretasiGizi['kenaikan_per_minggu']['tb']))
                                            {{ round($interpretasiGizi['kenaikan_per_minggu']['tb'], 2) }} cm
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <!-- LK/U -->
                                <tr>
                                    <td>Lingkaran Kepala Menurut Umur</td>
                                    <td style="text-align: center;">{{ $latestAntro->lingkar_kepala ?? '-' }}</td>
                                    <td style="text-align: center;">cm</td>
                                    <td style="text-align: center;">
                                        @if (isset($interpretasiGizi['lk']['z_score']))
                                            {{ $interpretasiGizi['lk']['z_score'] == '-INF' ? '*' : $interpretasiGizi['lk']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: center;" colspan="3">
                                        @if (isset($interpretasiGizi['lk']['percentil']))
                                            {{ $interpretasiGizi['lk']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: center;" colspan="2">
                                        @if (isset($interpretasiGizi['kenaikan_per_minggu'], $interpretasiGizi['kenaikan_per_minggu']['lk']))
                                            {{ round($interpretasiGizi['kenaikan_per_minggu']['lk'], 2) }} cm
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <!-- BB/TB -->
                                <tr>
                                    <td>Berat Badan Menurut Panjang Badan</td>
                                    <td style="text-align: center;"></td>
                                    <td style="text-align: center;"></td>
                                    <td style="text-align: center;">
                                        @if (isset($interpretasiGizi['bbtb']['z_score']))
                                            {{ $interpretasiGizi['bbtb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['bbtb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: center;" colspan="3">
                                        @if (isset($interpretasiGizi['bbtb']['percentil']))
                                            {{ $interpretasiGizi['bbtb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: center;" colspan="2">
                                    </td>
                                </tr>
                            @else
                                {{-- Content for standard cases --}}
                                <!-- BB/U -->
                                <tr>
                                    <td>Berat Badan Menurut Umur</td>
                                    <td>{{ $latestAntro && $latestAntro->berat_badan ? $latestAntro->berat_badan . ' kg' : '-' }}
                                    </td>
                                    <td>
                                        @if (isset($interpretasiGizi['bb']['z_score']))
                                            {{ $interpretasiGizi['bb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['bb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($interpretasiGizi['bb']['percentil']))
                                            {{ $interpretasiGizi['bb']['percentil'] == '-INF' ? '*' : $interpretasiGizi['bb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $interpretasiGizi['bb']['kategori'] ?? '-' }}</td>
                                    <td>
                                        @if (isset($interpretasiGizi['bb']['batas_normal']['bawah'], $interpretasiGizi['bb']['batas_normal']['atas']))
                                            {{ $interpretasiGizi['bb']['batas_normal']['bawah'] }} -
                                            {{ $interpretasiGizi['bb']['batas_normal']['atas'] }} kg
                                        @else
                                            -
                                        @endif
                                    <td>
                                        @if (isset($interpretasiGizi['kenaikan']['bb_bawah'], $interpretasiGizi['kenaikan']['bb_atas']))
                                            {{ $interpretasiGizi['kenaikan']['bb_bawah'] }} -
                                            {{ $interpretasiGizi['kenaikan']['bb_atas'] }}
                                            {{ $interpretasiGizi['kenaikan']['bb_unit'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <!-- TB/U -->
                                <tr>
                                    <td>Tinggi Badan Menurut Umur</td>
                                    <td>{{ $latestAntro && $latestAntro->tinggi_badan ? $latestAntro->tinggi_badan . ' cm' : '-' }}
                                    </td>
                                    <td>
                                        @if (isset($interpretasiGizi['tb']['z_score']))
                                            {{ $interpretasiGizi['tb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['tb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($interpretasiGizi['tb']['percentil']))
                                            {{ $interpretasiGizi['tb']['percentil'] == '-INF' ? '*' : $interpretasiGizi['tb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $interpretasiGizi['tb']['kategori'] ?? '-' }}</td>
                                    <td>
                                        @if (isset($interpretasiGizi['tb']['batas_normal']['bawah'], $interpretasiGizi['tb']['batas_normal']['atas']))
                                            {{ $interpretasiGizi['tb']['batas_normal']['bawah'] }} -
                                            {{ $interpretasiGizi['tb']['batas_normal']['atas'] }} cm
                                        @else
                                            -
                                        @endif
                                    <td>
                                        @if (isset($interpretasiGizi['kenaikan']['tb_bawah'], $interpretasiGizi['kenaikan']['tb_atas']))
                                            {{ $interpretasiGizi['kenaikan']['tb_bawah'] }} -
                                            {{ $interpretasiGizi['kenaikan']['tb_atas'] }}
                                            {{ $interpretasiGizi['kenaikan']['tb_unit'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <!-- LK/U -->
                                <tr>
                                    <td>Lingkaran Kepala Menurut Umur</td>
                                    <td>{{ $latestAntro && $latestAntro->lingkar_kepala ? $latestAntro->lingkar_kepala . ' cm' : '-' }}
                                    </td>
                                    <td>
                                        @if (isset($interpretasiGizi['lk']['z_score']))
                                            {{ $interpretasiGizi['lk']['z_score'] == '-INF' ? '*' : $interpretasiGizi['lk']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($interpretasiGizi['lk']['percentil']))
                                            {{ $interpretasiGizi['lk']['percentil'] == '-INF' ? '*' : $interpretasiGizi['lk']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $interpretasiGizi['lk']['kategori'] ?? '-' }}</td>
                                    <td>
                                        @if (isset($interpretasiGizi['lk']['batas_normal']['bawah'], $interpretasiGizi['lk']['batas_normal']['atas']))
                                            {{ $interpretasiGizi['lk']['batas_normal']['bawah'] }} -
                                            {{ $interpretasiGizi['lk']['batas_normal']['atas'] }} cm
                                        @else
                                            -
                                        @endif
                                    <td>
                                        @if (isset($interpretasiGizi['kenaikan']['lk_bawah'], $interpretasiGizi['kenaikan']['lk_atas']))
                                            {{ $interpretasiGizi['kenaikan']['lk_bawah'] }} -
                                            {{ $interpretasiGizi['kenaikan']['lk_atas'] }}
                                            {{ $interpretasiGizi['kenaikan']['lk_unit'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <!-- BB/TB -->
                                <tr>
                                    <td>Berat Badan Menurut Tinggi</td>
                                    <td></td>
                                    <td>
                                        @if (isset($interpretasiGizi['bbtb']['z_score']))
                                            {{ $interpretasiGizi['bbtb']['z_score'] == '-INF' ? '*' : $interpretasiGizi['bbtb']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($interpretasiGizi['bbtb']['percentil']))
                                            {{ $interpretasiGizi['bbtb']['percentil'] == '-INF' ? '*' : $interpretasiGizi['bbtb']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $interpretasiGizi['bbtb']['kategori'] ?? '-' }}</td>
                                    <td>
                                        @if (isset($interpretasiGizi['bbtb']['batas_normal']['bawah'], $interpretasiGizi['bbtb']['batas_normal']['atas']))
                                            {{ $interpretasiGizi['bbtb']['batas_normal']['bawah'] }} -
                                            {{ $interpretasiGizi['bbtb']['batas_normal']['atas'] }} kg
                                        @else
                                            -
                                        @endif
                                    <td>
                                        @if (isset($interpretasiGizi['kenaikan']['bbtb_bawah'], $interpretasiGizi['kenaikan']['bbtb_atas']))
                                            {{ $interpretasiGizi['kenaikan']['bbtb_bawah'] }} -
                                            {{ $interpretasiGizi['kenaikan']['bbtb_atas'] }}
                                            {{ $interpretasiGizi['kenaikan']['bbtb_unit'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <!-- IMT/U -->
                                <tr>
                                    <td>Indeks Massa Tubuh Menurut Umur</td>
                                    <td>
                                        @if ($latestAntro && $latestAntro->imt)
                                            {{ $latestAntro->imt }} kg/cm<sup>2</sup>
                                        @endif
                                    </td>

                                    <td>
                                        @if (isset($interpretasiGizi['imt']['z_score']))
                                            {{ $interpretasiGizi['imt']['z_score'] == '-INF' ? '*' : $interpretasiGizi['imt']['z_score'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($interpretasiGizi['imt']['percentil']))
                                            {{ $interpretasiGizi['imt']['percentil'] == '-INF' ? '*' : $interpretasiGizi['imt']['percentil'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $interpretasiGizi['imt']['kategori'] ?? '-' }}</td>
                                    <td>
                                        @if (isset($interpretasiGizi['imt']['batas_setara']['bawah'], $interpretasiGizi['imt']['batas_setara']['atas']))
                                            {{ $interpretasiGizi['imt']['batas_setara']['bawah'] }} -
                                            {{ $interpretasiGizi['imt']['batas_setara']['atas'] }} kg
                                        @else
                                            -
                                        @endif
                                    <td>
                                        @if (isset($interpretasiGizi['kenaikan']['imt_bawah'], $interpretasiGizi['kenaikan']['imt_atas']))
                                            {{ $interpretasiGizi['kenaikan']['imt_bawah'] }} -
                                            {{ $interpretasiGizi['kenaikan']['imt_atas'] }}
                                            {{ $interpretasiGizi['kenaikan']['imt_unit'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>

                            @endif


                            {{-- Berat badan ideal --}}
                            <tr>
                                <td>Berat Badan Ideal</td>
                                <td>
                                    {{ isset($interpretasiGizi['bbtb']) && isset($interpretasiGizi['bbtb']['bb_ideal']) ? $interpretasiGizi['bbtb']['bb_ideal'] . ' kg' : '*' }}
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            {{-- Usia berat --}}
                            <tr>
                                <td>Usia Berat (WA)</td>
                                <td>
                                    @if (isset($interpretasiGizi['weight_age']) && $interpretasiGizi['weight_age'])
                                        {{ $interpretasiGizi['weight_age'] }} bl
                                    @else
                                        -
                                    @endif
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            {{-- Usia tinggi --}}
                            <tr>
                                <td>Usia Tinggi (HA)</td>
                                <td>
                                    @if (isset($interpretasiGizi['height_age']) && $interpretasiGizi['height_age'])
                                        {{ $interpretasiGizi['height_age'] }} bl
                                    @else
                                        -
                                    @endif
                                </td>
                                <td colspan="5"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Potensi Tinggi Genetik -->
                <div class="table-container" style="overflow: hidden;">
                    <table class="assessment-table" style="width: 25%; float: left; margin-right: 1%;">
                        <thead>
                            <tr>
                                <th colspan="2">Interpretasi Tinggi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tinggi Ayah</td>
                                <td style="text-align: center;">
                                    {{ $patient->tinggi_ayah ? $patient->tinggi_ayah . ' cm' : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Tinggi Ibu</td>
                                <td style="text-align: center;">
                                    {{ $patient->tinggi_ibu ? $patient->tinggi_ibu . ' cm' : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Mid Parental Height</td>
                                <td style="text-align: center;">
                                    @if (isset($interpretasiGizi['tinggi_potensi_genetik']['tpg']))
                                        {{ $interpretasiGizi['tinggi_potensi_genetik']['tpg'] }} cm /
                                        {{ $interpretasiGizi['tinggi_potensi_genetik']['z_tpg'] ? 'Z = ' . round($interpretasiGizi['tinggi_potensi_genetik']['z_tpg'], 2) : '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Tinggi Potensi Genetik</td>
                                <td>
                                    @if (isset($interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_bawah']) &&
                                            isset($interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_atas']))
                                        {{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_bawah'] }} -
                                        {{ $interpretasiGizi['tinggi_potensi_genetik']['tinggi_perkiraan_atas'] }} cm
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>MPH Umur {{ $interpretasiGizi['usia_bulan'] }} bl</td>
                                <td style="text-align: center;">
                                    @if (isset($interpretasiGizi['tbug']['nilai']))
                                        {{ $interpretasiGizi['tbug']['nilai'] }} cm /
                                        {{ $interpretasiGizi['tinggi_potensi_genetik']['z_tpg'] ? 'Z = ' . round($interpretasiGizi['tinggi_potensi_genetik']['z_tpg'], 2) : '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>TPG Umur {{ $interpretasiGizi['usia_bulan'] }} bl</td>
                                <td style="text-align: center;">
                                    @if (isset($interpretasiGizi['tbug']['batas']['bawah']) && isset($interpretasiGizi['tbug']['batas']['atas']))
                                        {{ $interpretasiGizi['tbug']['batas']['bawah'] }} -
                                        {{ $interpretasiGizi['tbug']['batas']['atas'] }} cm
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Projected Height</td>
                                <td style="text-align: center;">
                                    @if (isset($interpretasiGizi['tbug']['proyeksi_tinggi_akhir']) && $interpretasiGizi['tbug']['proyeksi_tinggi_akhir'])
                                        {{ $interpretasiGizi['tbug']['proyeksi_tinggi_akhir'] }} cm
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="assessment-table" style="width: 74%; float: left; margin-right: 1%;">
                        <thead>
                            <tr>
                                <th colspan="10">Laju Pertumbuhan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center;">No</td>
                                <td style="text-align: center;">Tgl Periksa</td>
                                <td style="text-align: center;">Usia</td>
                                <td style="text-align: center;">Tinggi</td>
                                <td style="text-align: center;">Z-Score</td>
                                <td style="text-align: center;">Delta Z</td>
                                <td style="text-align: center;">Laju Pertumbuhan
                                <td style="text-align: center;">Normal~</td>
                                <td style="text-align: center;">TBUG~~</td>
                            </tr>
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
                                        <td style="text-align: center;">{{ $rowNumber }}</td>
                                        <td style="text-align: center;">
                                            {{ \Carbon\Carbon::parse($point['tgl_periksa'])->translatedFormat('d M y') ?? '-' }}
                                        </td>
                                        <td style="text-align: center;">{{ $point['usia_sebenarnya'] ?? '-' }}</td>
                                        <td style="text-align: center;">
                                            {{ isset($point['tinggi_badan']) ? $point['tinggi_badan'] . ' cm' : '-' }}
                                        </td>
                                        <td style="text-align: center;">
                                            {{ $point['z_score'] ? round($point['z_score'], 2) : '-' }}</td>
                                        <td style="text-align: center;">
                                            {{ $growth && isset($growth['delta_z']) ? round($growth['delta_z'], 2) : '-' }}
                                        </td>
                                        <td style="text-align: center;">
                                            {{ $growth && isset($growth['aktual']['nilai']) ? $growth['aktual']['nilai'] . ' cm/thn' : '-' }}
                                        </td>
                                        <td style="text-align: center;">
                                            {{ $growth && isset($growth['normal']['nilai_normal']) ? $growth['normal']['nilai_normal'] . ' cm/thn' : '-' }}
                                        </td>
                                        <td style="text-align: center;">{{ $point['tbug'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" style="text-align: center;">
                                        Data laju pertumbuhan tidak tersedia. Pilih minimal 2 titik pemeriksaan untuk
                                        melihat laju pertumbuhan.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <div style="clear: both;"></div>
                </div>

                <!-- filepath: d:\laragon\www\closing\ekurva\resources\views\pdf\kurva.blade.php -->

                <!-- Footer -->
                <div class="notes-section">
                    <p style="font-size: 10px; font-weight: 600; color: #555; margin-bottom: 3px;">Catatan :</p>

                    <table style="width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 8px;">
                        <tr>
                            <td style="width: 3%; vertical-align: top;">*</td>
                            <td style="width: 29%; vertical-align: top;">: Nilai BB TB LK di luar batas wajar</td>
                            <td style="width: 3%; vertical-align: top;">~</td>
                            <td style="width: 29%; vertical-align: top;">: Nilai Normal Laju Pertumbuhan Sesuai Usia
                                dan
                                Genetik</td>
                            <td style="width: 6%; vertical-align: top;">Dicatat oleh</td>
                            <td style="width: 30%; vertical-align: top;">: {{ $patient->created_by_name }}</td>
                        </tr>
                        <tr>
                            <td style="width: 3%; vertical-align: top;">**</td>
                            <td style="width: 29%; vertical-align: top;">: Tidak dinilai pada usia > 60 Bulan</td>
                            <td style="width: 3%; vertical-align: top;">~~</td>
                            <td style="width: 29%; vertical-align: top;">: Tinggi Badan Sesuai Usia dan Genetik</td>
                            <td style="width: 6%; vertical-align: top;">Dikirim oleh</td>
                            <td style="width: 30%; vertical-align: top;">: {{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">***</td>
                            <td style="vertical-align: top;">: Tidak dinilai pada usia > 10 Tahun</td>
                        </tr>
                    </table>
                </div>
            @endif

            <?php
            // Calculate total pages - first page + image pages
            $isAntros = count($antros) > 0;
            $plusNumber = $isAntros ? 2 : 1;
            $totalPages = $plusNumber + count($images);
            ?>
            <div class="page-footer first-page-footer">

                {{-- Additional Footer --}}
                <div class="footer-disclaimer">
                    Laporan ini tidak dimaksudkan untuk menggantikan pemeriksaan secara klinis dan nasehat medis oleh
                    tenaga kesehatan profesional.
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 20%; text-align: left; vertical-align: middle;">
                            @if ($superAdmin && $superAdmin->header)
                                <img src="{{ public_path('logo.png') }}"
                                    class="footer-logo" alt="Sikurva.com">
                            @else
                                <div class="logo-placeholder">Sikurva.com</div>
                            @endif
                        </td>
                        <td style="width: 50%; text-align: center; vertical-align: middle; ">
                            Powered by Sikurva.com {{ \Illuminate\Support\Str::title($patient->nama) }}
                            ({{ \Carbon\Carbon::parse($patient->tgl_lahir)->translatedFormat('d M y') }}) Printed on
                            {{ \Carbon\Carbon::now()->format('d M Y') }}

                            Page <span class="pagenum" data-page="1" data-total="{{ $totalPages }}"></span> /
                            <span class="pagetotal" data-total="{{ $totalPages }}"></span>
                        </td>
                        <td style="width: 25%; text-align: right; vertical-align: middle; font-style: italic;">
                            The Smartest Growth Chart Platform of its kind.
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    @if (count($antros) > 0)
        <div style="page-break-before: always;"></div>
        <div class="page">
            <!-- Notes -->
            <div class="">
                <table class="assessment-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Tgl Periksa</th>
                            <th width="75%">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($antros as $antro)
                            <tr>
                                <td style="text-align: center;">{{ $loop->iteration }}</td>
                                <td style="text-align: center;">
                                    {{ \Carbon\Carbon::parse($antro->tgl_periksa)->translatedFormat('d M y') ?? '-' }}
                                    <br>
                                    <span style="font-size: 8 px;">({{ $antro->created_by }})</span>
                                </td>
                                @php
                                    $notesContent = $antro->notes ?? '-';
                                @endphp
                                <td style="text-align: justify;">{!! nl2br(e($notesContent)) !!}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center;">
                                    Tidak ada catatan yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="page-footer">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 20%; text-align: left; vertical-align: middle;">
                            @if ($superAdmin && $superAdmin->header)
                                <img src="{{ public_path('logo.png') }}"
                                    class="footer-logo" alt="Sikurva.com">
                            @else
                                <div class="logo-placeholder">Sikurva.com</div>
                            @endif
                        </td>
                        <td style="width: 50%; text-align: center; vertical-align: middle; ">
                            Powered by Sikurva.com {{ \Illuminate\Support\Str::title($patient->nama) }}
                            ({{ \Carbon\Carbon::parse($patient->tgl_lahir)->translatedFormat('d M y') }}) Printed on
                            {{ \Carbon\Carbon::now()->format('d M Y') }}

                            Page <span class="pagenum" data-page="2" data-total="{{ $totalPages }}"></span> /
                            <span class="pagetotal" data-total="{{ $totalPages }}"></span>
                        </td>
                        <td style="width: 25%; text-align: right; vertical-align: middle; font-style: italic;">
                            The Smartest Growth Chart Platform of its kind.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="page-break-after: always;"></div>
    @endif
    {{-- Halaman Gambar-gambar --}}
    @php
        $nomor = 1;
    @endphp
    @foreach ($images as $index => $img)
        @php
            // Check if this is the ads image
            $isAdsImage = false;
            if (strpos($img, 'ads_') !== false || strpos($img, 'lp-setting/ads_') !== false) {
                $isAdsImage = true;
                $tableKey = null;
            } else {
                // Extract the table number from image filename using regex
                preg_match('/chart-\d+-table(\d+)\.png/', $img, $matches);
                $tableNumber = isset($matches[1]) ? $matches[1] : null;
                $tableKey = "table{$tableNumber}";
            }
        @endphp
        <div class="page">
            <div class="content-block">
                @if (!$isAdsImage)
                    <p style="margin: 20px 0 0 0; padding: 0; font-weight: bold; font-size: 14px;">
                        {{ $nomor++ }}.
                        {{ isset($kurvaTableSettings[$tableKey]) ? $kurvaTableSettings[$tableKey]->judul : '-' }} Anak
                        {{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </p>
                    <div class="image-container">
                        <img src="{{ $img }}" alt="Chart" class="chart-image">
                    </div>
                @else
                    <div style="text-align: center; width: 100%;">
                        <div class="image-container-ads"
                            style="display: flex; justify-content: center; align-items: center; max-height: 60vh;">
                            <img src="{{ $img }}" alt="Advertisement" class="ads-image">
                        </div>
                    </div>
                @endif
                <div class="page-footer first-page-footer">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 20%; text-align: left; vertical-align: middle;">
                                @if ($superAdmin && $superAdmin->header)
                                    <img src="{{ public_path('logo.png') }}"
                                        class="footer-logo" alt="Sikurva.com">
                                @else
                                    <div class="logo-placeholder">Sikurva.com</div>
                                @endif
                            </td>
                            <td style="width: 50%; text-align: center; vertical-align: middle; ">
                                Powered by Sikurva.com {{ \Illuminate\Support\Str::title($patient->nama) }}
                                ({{ \Carbon\Carbon::parse($patient->tgl_lahir)->translatedFormat('d M y') }})
                                Printed on
                                {{ \Carbon\Carbon::now()->format('d M Y') }}

                                Page <span class="pagenum" data-page="{{ $loop->iteration + $plusNumber }}"
                                    data-total="{{ count($images) + $plusNumber }}"></span>
                                /
                                <span class="pagetotal" data-total="{{ count($images) + $plusNumber }}"></span>
                            </td>
                            <td style="width: 25%; text-align: right; vertical-align: middle; font-style: italic;">
                                The Smartest Growth Chart Platform of its kind.
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>
