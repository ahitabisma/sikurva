@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <!-- Table Header Start -->
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">No</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Table</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Judul</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Sumbu Y</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Min Y</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Max Y</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Mayor Y</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Minor Y</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Unit Y</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Sumbu X</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Min X</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Max X</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Mayor X</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Minor X</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Unit X</p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">Action</p>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <!-- Table Header End -->

                        <!-- Table Body Start -->
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tabel 1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Grafik Berat Badan Menurut Umur (X)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Berat (Kg)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">31</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Kg</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Umur (Tahun Bulan)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">60</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Bulan</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"><x-svg-edit /></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tabel 2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Grafik Panjang / Tinggi Badan Menurut Umur
                                            (X)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Panjang / Tinggi (Cm)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">40</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">125</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Cm</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Umur (Tahun Bulan)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">60</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Bulan</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"><x-svg-edit /></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">3</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tabel 3</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Grafik Lingkaran Kepala Menurut Umur (X)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Lingkaran Kepala (Cm)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">30</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">56</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0.5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Cm</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Umur (Tahun Bulan)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">60</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Bulan</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"><x-svg-edit /></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">4</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tabel 4</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Grafik Berat Badan Menurut Panjang / Tinggi
                                            (X)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Berat (Kg)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">34</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Kg</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Panjang / Tinggi (Cm)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">45</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">120</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Cm</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"><x-svg-edit /></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tabel 5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Grafik Indeks Massa Tubuh Menurut Umur (X)
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">IMT (Kg/m²)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">9</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">23</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0.2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Kg/m²</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Umur (Tahun Bulan)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">60</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Bulan</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"><x-svg-edit /></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">6</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tabel 6</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Grafik Berat Badan Menurut Umur (Y)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Berat (Kg)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">10</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">60</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Kg</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Umur (Tahun Bulan)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">10</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">3 bl</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1 bl</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tahun</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"><x-svg-edit /></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">7</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tabel 7</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Grafik Indeks Massa Tubuh Menurut Umur (Z)
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">IMT (Kg/m²)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">11</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">37</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">2</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0.5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Kg/m²</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Umur (Tahun Bulan)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">19</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">3 bl</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1 bl</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tahun</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"><x-svg-edit /></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">8</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tabel 8</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Grafik Tinggi Badan Menurut Umur (Z)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Tinggi (Cm)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">90</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">200</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">10</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">5</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Cm</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Umur (Tahun Bulan)</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">0</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">60</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">3</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">1</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">Bulan</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"><x-svg-edit /></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <!-- Table Body End -->
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <button
                            class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 sm:px-3.5">
                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z"
                                    fill=""></path>
                            </svg>
                            <span class="hidden sm:inline"> Previous </span>
                        </button>

                        <span class="block text-sm font-medium text-gray-700 sm:hidden">Page 1 of 1</span>

                        <ul class="hidden items-center gap-0.5 sm:flex">
                            <li>
                                <a href="#"
                                    class="bg-blue-500/[0.08] text-theme-sm text-blue-500 hover:bg-blue-500/[0.08] hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium">1</a>
                            </li>
                        </ul>

                        <button
                            class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 sm:px-3.5">
                            <span class="hidden sm:inline"> Next </span>
                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z"
                                    fill=""></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Table Four -->
        </div>
    </div>
@endsection
