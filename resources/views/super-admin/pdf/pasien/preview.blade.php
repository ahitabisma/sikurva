@extends('layouts.tailadmin')

@section('content')
    <div class="flex h-screen flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white xl:h-full xl:w-4/5">
        <div class="flex flex-col justify-between border-b border-gray-200 sm:flex-row">
            <div class="flex w-full items-center justify-between gap-3 px-4 py-4 sm:justify-normal">
                <a href="{{ url()->previous() }}"
                    class="flex h-10 w-full max-w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-100 hover:text-gray-800">
                    <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M1.91634 7.99899C1.91612 8.19115 1.98928 8.38337 2.13583 8.53001L6.13316 12.5301C6.42595 12.8231 6.90082 12.8233 7.19382 12.5305C7.48681 12.2377 7.48698 11.7629 7.19419 11.4699L4.47396 8.74772L13.3339 8.74772C13.7481 8.74772 14.0839 8.41194 14.0839 7.99772C14.0839 7.58351 13.7481 7.24772 13.3339 7.24772L4.47834 7.24772L7.19417 4.53016C7.48697 4.23718 7.48682 3.7623 7.19383 3.4695C6.90085 3.1767 6.42597 3.17685 6.13317 3.46984L2.17075 7.43478C2.01476 7.57222 1.91634 7.77347 1.91634 7.99772C1.91634 7.99815 1.91634 7.99857 1.91634 7.99899Z"
                            fill="#"></path>
                    </svg>
                </a>

                <div class="flex items-center gap-3">
                    <div class="flex gap-3">
                        <button
                            class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800">
                            Download
                        </button>
                        <button
                            class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800">
                            Push Email
                        </button>
                        <button
                            class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800">
                            Push Whatsapp
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====== Inbox Details Box Start -->
        <div class="h-screen overflow-y-auto">
            <div class="custom-scrollbar block h-full max-h-full overflow-auto p-5 xl:p-6">
                <div class="mb-9 flex items-center gap-3">
                    <div class="h-12 w-12 overflow-hidden rounded-full mt-3">
                        <img src="{{ asset('logo.png') }}" alt="user">
                    </div>

                    <div>
                        <span class="mb-0.5 block text-sm font-medium text-gray-800">
                            Sikurva
                        </span>
                        <span class="block text-theme-xs text-gray-500">
                            jswibisono@gmail.com
                        </span>
                    </div>
                </div>

                <div class="mb-7 text-sm text-gray-500">
                    <p class="mb-4">Laporan Pemeriksaan Pasien</p>

                    {{-- Data Pasien --}}
                    <p class="mb-4 text-sm text-gray-700 leading-relaxed">
                        <span class="font-semibold">Nama Pasien:</span> John Doe<br>
                        <span class="font-semibold">Tanggal Lahir:</span> 15 Januari 2020<br>
                        <span class="font-semibold">Jenis Kelamin:</span> Laki-laki
                    </p>

                    {{-- Grafik 1 --}}
                    <div>
                        <p class="mb-4">1. Grafik Berat Badan Menurut Umur Anak Laki-Laki 0-60 Bulan</p>
                        <div class="bg-sky-50" style="padding:20px; width: fit-content; border-radius: 20px;;">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>

                    {{-- Hasil Interpretasi --}}
                    <div class="mt-5">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Hasil Interpretasi</h3>
                            <p class="text-sm text-gray-600 mb-2">
                                Berdasarkan hasil pemeriksaan antropometri yang telah dilakukan, berikut adalah interpretasi
                                dari pengukuran:
                            </p>
                            <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                                <li>Berat badan sesuai dengan usia</li>
                                <li>Tinggi badan sesuai dengan usia</li>
                                <li>Indeks massa tubuh dalam kategori normal</li>
                                <li>Lingkar kepala sesuai dengan usia</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ====== Inbox Details Box End -->
    </div>
@endsection


@section('style')
    <style>
        canvas {
            height: 360px;
            width: fit-content;
            max-height: 360px;
            max-width: fit-content;
            background-color: white
        }
    </style>
@endsection

@section('script')
    <script>
        const labels = [
            '0', '2', '4', '6', '8', '10', '1 Tahun', '2', '4', '6', '8', '10', '2 Tahun',
            '2', '4', '6', '8', '10', '3 Tahun', '2', '4', '6', '8', '10', '4 Tahun',
            '2', '4', '6', '8', '10', '5 Tahun', ''
        ];

        const datasets = [{
                label: 'SD3neg',
                labelCanvas: '-3',
                data: [2.0, 2.8, 3.5, 4.2, 4.8, 5.3, 5.7, 6.1, 6.4, 6.7, 6.9, 7.1, 7.3, 7.5, 7.7, 7.8, 8.0, 8.1,
                    8.2, 8.3, 8.4, 8.5, 8.6, 8.6, 8.7, 8.8, 8.9, 8.9, 9.0, 9.1, 9.2
                ],
                borderColor: 'black',
                fill: false,
                tension: 0.4,
                borderWidth: 1,
                pointRadius: 0
            },
            {
                label: 'SD2neg',
                labelCanvas: '-2',
                data: [2.5, 3.4, 4.2, 4.9, 5.6, 6.2, 6.7, 7.1, 7.5, 7.8, 8.1, 8.3, 8.6, 8.8, 9.0, 9.2, 9.4, 9.6,
                    9.7, 9.9, 10.0, 10.2, 10.3, 10.4, 10.6, 10.7, 10.8, 10.9, 11.0, 11.1, 11.2
                ],
                borderColor: 'red',
                fill: false,
                tension: 0.4,
                borderWidth: 1,
                pointRadius: 0
            },
            {
                label: 'SD1neg',
                labelCanvas: '-1',
                data: [2.8, 3.9, 4.9, 5.8, 6.6, 7.3, 7.9, 8.4, 8.9, 9.3, 9.7, 10.0, 10.3, 10.6, 10.9, 11.1, 11.4,
                    11.6, 11.8, 12.0, 12.2, 12.4, 12.6, 12.7, 12.9, 13.0, 13.2, 13.3, 13.5, 13.6, 13.8
                ],
                borderColor: 'orange',
                fill: false,
                tension: 0.4,
                borderWidth: 1,
                pointRadius: 0,
                borderDash: [5, 5]
            },
            {
                label: 'SD0',
                labelCanvas: '0',
                data: [3.2, 4.5, 5.6, 6.6, 7.5, 8.2, 8.8, 9.3, 9.8, 10.2, 10.5, 10.9, 11.2, 11.5, 11.8, 12.0,
                    12.3, 12.5, 12.7, 12.9, 13.1, 13.3, 13.5, 13.7, 13.9, 14.1, 14.3, 14.5, 14.7, 14.8, 15.0
                ],
                borderColor: 'green',
                fill: false,
                tension: 0.4,
                borderWidth: 1,
                pointRadius: 0
            },

            {
                label: 'SD1',
                labelCanvas: '1',
                data: [3.6, 5.1, 6.4, 7.5, 8.5, 9.3, 10.0, 10.6, 11.2, 11.7, 12.1, 12.5, 12.9, 13.3, 13.6, 13.9,
                    14.2, 14.5, 14.8, 15.1, 15.3, 15.6, 15.8, 16.0, 16.3, 16.5, 16.7, 16.9, 17.1, 17.3, 17.5
                ],
                borderColor: 'orange',
                fill: false,
                tension: 0.4,
                borderWidth: 1,
                pointRadius: 0,
                borderDash: [5, 5]
            },
            {
                label: 'SD2',
                labelCanvas: '2',
                data: [4.0, 5.5, 6.8, 8.0, 9.0, 9.9, 10.6, 11.3, 11.9, 12.5, 13.0, 13.4, 13.8, 14.2, 14.6, 14.9,
                    15.3, 15.6, 15.9, 16.2, 16.5, 16.8, 17.0, 17.3, 17.5, 17.8, 18.0, 18.2, 18.4, 18.6, 18.8
                ],
                borderColor: 'red',
                fill: false,
                tension: 0.4,
                borderWidth: 1,
                pointRadius: 0
            },
            {
                label: 'SD3',
                labelCanvas: '3',
                data: [4.4, 6.0, 7.5, 8.9, 10.1, 11.1, 11.9, 12.6, 13.3, 13.9, 14.4, 14.9, 15.3, 15.7, 16.1,
                    16.5, 16.9, 17.2, 17.6, 17.9, 18.2, 18.5, 18.8, 19.1, 19.4, 19.7, 19.9, 20.2, 20.4,
                    20.6, 20.9
                ],
                borderColor: 'black',
                fill: false,
                tension: 0.4,
                borderWidth: 1,
                pointRadius: 0
            },
            {
                label: 'BB',
                labelCanvas: '',
                data: [3.5, 5.0, 6.2, 7.5, 8.3, 9.1, 10.0, 11.2, 12.5, 13.3, 14.1], // Contoh data per 3 bulan
                borderColor: 'purple',
                backgroundColor: 'red',
                pointRadius: 3,
                fill: false,
                borderWidth: 1,
            },
        ];

        // Plugin untuk menampilkan SD terakhir di ujung kanan grafik
        const zScoreLabelPlugin = {
            id: 'zScoreLabel',
            afterDatasetsDraw(chart) {
                const {
                    ctx,
                    data
                } = chart;
                ctx.save();
                ctx.font = '12px Arial';
                ctx.textAlign = 'left';
                ctx.textBaseline = 'middle';

                data.datasets.forEach((dataset, index) => {
                    const meta = chart.getDatasetMeta(index);
                    if (!meta.hidden) {
                        const lastPoint = meta.data[meta.data.length - 1]; // Titik terakhir
                        const zScoreLabel = dataset.labelCanvas; // Ambil SD sebagai label
                        if (lastPoint) {
                            ctx.fillStyle = dataset.borderColor;
                            ctx.fillText(zScoreLabel, lastPoint.x + 10, lastPoint.y);
                        }
                    }
                });
                ctx.restore();
            }
        };

        const footerPlugin = {
            id: 'footerPlugin',
            beforeDraw: (chart) => {
                const {
                    ctx,
                    chartArea
                } = chart;
                ctx.save();
                ctx.font = '12px Arial';
                ctx.fillStyle = 'black';
                ctx.textAlign = 'left';
                ctx.fillText('© Sikurva', chartArea.left, chartArea.bottom + 55); // Kiri
                ctx.textAlign = 'right';
                ctx.fillText('Sumber Data: WHO Growth Chart', chartArea.right, chartArea.bottom + 55); // Kanan
                ctx.restore();
            }
        };

        // Inisialisasi Chart
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Usia (Bulan & Tahun)'
                        },
                        grid: {
                            drawTicks: false,
                            color: (context) => {
                                return context.index % 6 === 0 ? 'black' :
                                    '#ccc'; // Garis Tahun lebih tebal (Hitam)
                            },
                            lineWidth: (context) => {
                                return context.index % 6 === 0 ? 0.5 : 0.5; // Lebih tebal di setiap 12 bulan
                            }
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Berat Badan (kg)'
                        },
                        min: 0,
                        max: 22,
                        ticks: {
                            stepSize: 2
                        }
                    },
                    y1: {
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Berat Badan (kg)'
                        },
                        min: 0,
                        max: 22,
                        ticks: {
                            stepSize: 2
                        }
                    }
                }
            },
            plugins: [zScoreLabelPlugin, footerPlugin] // Tambahkan plugin untuk menggambar label SD
        });
    </script>
@endsection
