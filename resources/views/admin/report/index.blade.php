@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white mt-5">
        <div class="flex sm:flex-row flex-col items-center gap-5 border-t border-gray-100 p-5 sm:p-6">
            <div class="w-20 h-20 overflow-hidden border border-gray-200 rounded-full">
                <img src="{{ asset('logo.png') }}" alt="user" class="w-20 h-20 object-cover">
            </div>
            <div class="order-3 xl:order-2">
                <h4 class="mb-2 text-lg font-semibold text-center text-gray-800 xl:text-left">
                    Header
                </h4>
                <div class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-3">
                    <input type="file"
                        class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden">

                    <x-primary-button class="w-full xl:w-auto">{{ __('Simpan') }}</x-primary-button>
                </div>
            </div>
        </div>
    </div>

    {{-- Last Activity --}}
    <div class="rounded-2xl border border-gray-200 bg-white mt-5">
        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h6 class="font-bold text-gray-800">Hasil Generate</h6>
                    </div>
                </div>

                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <!-- table header start -->
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            No
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Kode MR
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Pasien
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Jenis Kelamin
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Action
                                        </p>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <!-- table header end -->

                        <!-- table body start -->
                        <tbody class="divide-y divide-gray-100">
                            <tr class="border-b border-gray-100">
                                <td class="px-6 py-3 whitespace-nowrap">1</td>
                                <td class="px-6 py-3 whitespace-nowrap">KL-001</td>
                                <td class="px-6 py-3 whitespace-nowrap">Budi Santoso</td>
                                <td class="px-6 py-3 whitespace-nowrap">Laki-laki</td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <x-warning-button url="/patient/preview/1" text="Preview" />
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="px-6 py-3 whitespace-nowrap">2</td>
                                <td class="px-6 py-3 whitespace-nowrap">KL-002</td>
                                <td class="px-6 py-3 whitespace-nowrap">Siti Aminah</td>
                                <td class="px-6 py-3 whitespace-nowrap">Perempuan</td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <x-warning-button url="/patient/preview/1" text="Preview" />
                                    </div>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td colspan="6" class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">
                                            Tidak ada data
                                        </p>
                                    </div>
                                </td>
                            </tr> --}}
                        </tbody>
                        <!-- table body end -->
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                </div>
            </div>
            <!-- Table Four -->
        </div>
    </div>
    {{-- Last Activity --}}
@endsection
