@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="px-5 py-4 sm:px-6 sm:py-5">
            <div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 lg:mb-6">
                        Detail Pasien
                    </h4>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Nama
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $patient->nama }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Jenis Kelamin
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ gender($patient->jenis_kelamin) }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Tanggal Lahir
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ formatDateBirth($patient->tgl_lahir) }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Usia
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ calculateAge($patient->tgl_lahir) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center mt-5">
                    {{-- <x-tambah-button url="{{ route('patient.create') }}" text="Periksa" />
                    <x-import-button url="" text="Import" /> --}}
                    {{-- <x-back-button url="{{ route('super-admin.users.index') }}" text="Kembali" /> --}}
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <!-- table header start -->
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div x-data="{ checked: false }" class="flex items-center gap-3">
                                            <div @click="checked = !checked"
                                                class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px] bg-white border-gray-300"
                                                :class="checked ? 'border-blue-500 bg-blue-500' : 'bg-white border-gray-300'">
                                                <svg :class="checked ? 'block' : 'hidden'" width="14" height="14"
                                                    viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    class="hidden">
                                                    <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white"
                                                        stroke-width="1.94437" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block font-medium text-gray-500 text-theme-xs">
                                                    Tgl Periksa
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Berat Badan
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Tinggi Badan
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Lingkar Kepala
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

                            {{-- <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div x-data="{ checked: false }" class="flex items-center gap-3">
                                            <div @click="checked = !checked"
                                                class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px] bg-white border-gray-300"
                                                :class="checked ? 'border-blue-500 bg-blue-500' : 'bg-white border-gray-300'">
                                                <svg :class="checked ? 'block' : 'hidden'" width="14" height="14"
                                                    viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    class="hidden">
                                                    <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white"
                                                        stroke-width="1.94437" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block font-medium text-gray-700 text-theme-sm">
                                                    DE124321
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-[#fdf2fa]">
                                                <span class="text-xs font-semibold text-[#dd2590]"> KF </span>
                                            </div>
                                            <div>
                                                <span class="text-theme-sm mb-0.5 block font-medium text-gray-700">
                                                    Kierra Franci
                                                </span>
                                                <span class="text-gray-500 text-theme-sm">
                                                    kierra@gmail.com
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            Software License
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            $18,50.34
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            2024-06-15
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p
                                            class="bg-success-50 text-theme-xs text-success-600 rounded-full px-2 py-0.5 font-medium">
                                            Complete
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <svg class="cursor-pointer hover:fill-error-500 fill-gray-700" width="20"
                                            height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.54142 3.7915C6.54142 2.54886 7.54878 1.5415 8.79142 1.5415H11.2081C12.4507 1.5415 13.4581 2.54886 13.4581 3.7915V4.0415H15.6252H16.666C17.0802 4.0415 17.416 4.37729 17.416 4.7915C17.416 5.20572 17.0802 5.5415 16.666 5.5415H16.3752V8.24638V13.2464V16.2082C16.3752 17.4508 15.3678 18.4582 14.1252 18.4582H5.87516C4.63252 18.4582 3.62516 17.4508 3.62516 16.2082V13.2464V8.24638V5.5415H3.3335C2.91928 5.5415 2.5835 5.20572 2.5835 4.7915C2.5835 4.37729 2.91928 4.0415 3.3335 4.0415H4.37516H6.54142V3.7915ZM14.8752 13.2464V8.24638V5.5415H13.4581H12.7081H7.29142H6.54142H5.12516V8.24638V13.2464V16.2082C5.12516 16.6224 5.46095 16.9582 5.87516 16.9582H14.1252C14.5394 16.9582 14.8752 16.6224 14.8752 16.2082V13.2464ZM8.04142 4.0415H11.9581V3.7915C11.9581 3.37729 11.6223 3.0415 11.2081 3.0415H8.79142C8.37721 3.0415 8.04142 3.37729 8.04142 3.7915V4.0415ZM8.3335 7.99984C8.74771 7.99984 9.0835 8.33562 9.0835 8.74984V13.7498C9.0835 14.1641 8.74771 14.4998 8.3335 14.4998C7.91928 14.4998 7.5835 14.1641 7.5835 13.7498V8.74984C7.5835 8.33562 7.91928 7.99984 8.3335 7.99984ZM12.4168 8.74984C12.4168 8.33562 12.081 7.99984 11.6668 7.99984C11.2526 7.99984 10.9168 8.33562 10.9168 8.74984V13.7498C10.9168 14.1641 11.2526 14.4998 11.6668 14.4998C12.081 14.4998 12.4168 14.1641 12.4168 13.7498V8.74984Z"
                                                fill=""></path>
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div x-data="{ checked: false }" class="flex items-center gap-3">
                                            <div @click="checked = !checked"
                                                class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px] bg-white border-gray-300"
                                                :class="checked ? 'border-blue-500 bg-blue-500' : 'bg-white border-gray-300'">
                                                <svg :class="checked ? 'block' : 'hidden'" width="14" height="14"
                                                    viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    class="hidden">
                                                    <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white"
                                                        stroke-width="1.94437" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block font-medium text-gray-700 text-theme-sm">
                                                    DE124321
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f0f9ff]">
                                                <span class="text-xs font-semibold text-[#0086c9]"> EW </span>
                                            </div>
                                            <div>
                                                <span class="text-theme-sm mb-0.5 block font-medium text-gray-700">
                                                    Emerson Workman
                                                </span>
                                                <span class="text-gray-500 text-theme-sm">
                                                    emerson@gmail.com
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            Software License
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            $18,50.34
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            2024-06-15
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p
                                            class="bg-warning-50 text-theme-xs text-warning-600 rounded-full px-2 py-0.5 font-medium">
                                            Pending
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <svg class="cursor-pointer hover:fill-error-500 fill-gray-700" width="20"
                                            height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.54142 3.7915C6.54142 2.54886 7.54878 1.5415 8.79142 1.5415H11.2081C12.4507 1.5415 13.4581 2.54886 13.4581 3.7915V4.0415H15.6252H16.666C17.0802 4.0415 17.416 4.37729 17.416 4.7915C17.416 5.20572 17.0802 5.5415 16.666 5.5415H16.3752V8.24638V13.2464V16.2082C16.3752 17.4508 15.3678 18.4582 14.1252 18.4582H5.87516C4.63252 18.4582 3.62516 17.4508 3.62516 16.2082V13.2464V8.24638V5.5415H3.3335C2.91928 5.5415 2.5835 5.20572 2.5835 4.7915C2.5835 4.37729 2.91928 4.0415 3.3335 4.0415H4.37516H6.54142V3.7915ZM14.8752 13.2464V8.24638V5.5415H13.4581H12.7081H7.29142H6.54142H5.12516V8.24638V13.2464V16.2082C5.12516 16.6224 5.46095 16.9582 5.87516 16.9582H14.1252C14.5394 16.9582 14.8752 16.6224 14.8752 16.2082V13.2464ZM8.04142 4.0415H11.9581V3.7915C11.9581 3.37729 11.6223 3.0415 11.2081 3.0415H8.79142C8.37721 3.0415 8.04142 3.37729 8.04142 3.7915V4.0415ZM8.3335 7.99984C8.74771 7.99984 9.0835 8.33562 9.0835 8.74984V13.7498C9.0835 14.1641 8.74771 14.4998 8.3335 14.4998C7.91928 14.4998 7.5835 14.1641 7.5835 13.7498V8.74984C7.5835 8.33562 7.91928 7.99984 8.3335 7.99984ZM12.4168 8.74984C12.4168 8.33562 12.081 7.99984 11.6668 7.99984C11.2526 7.99984 10.9168 8.33562 10.9168 8.74984V13.7498C10.9168 14.1641 11.2526 14.4998 11.6668 14.4998C12.081 14.4998 12.4168 14.1641 12.4168 13.7498V8.74984Z"
                                                fill=""></path>
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div x-data="{ checked: false }" class="flex items-center gap-3">
                                            <div @click="checked = !checked"
                                                class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px] bg-white border-gray-300"
                                                :class="checked ? 'border-blue-500 bg-blue-500' : 'bg-white border-gray-300'">
                                                <svg :class="checked ? 'block' : 'hidden'" width="14" height="14"
                                                    viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    class="hidden">
                                                    <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white"
                                                        stroke-width="1.94437" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block font-medium text-gray-700 text-theme-sm">
                                                    DE124321
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-[#fff6ed]">
                                                <span class="text-xs font-semibold text-[#ec4a0a]"> CP </span>
                                            </div>
                                            <div>
                                                <span class="text-theme-sm mb-0.5 block font-medium text-gray-700">
                                                    Chance Philips
                                                </span>
                                                <span class="text-gray-500 text-theme-sm">
                                                    chance@gmail.com
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            Software License
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            $18,50.34
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            2024-06-15
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p
                                            class="bg-success-50 text-theme-xs text-success-600 rounded-full px-2 py-0.5 font-medium">
                                            Complete
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <svg class="cursor-pointer hover:fill-error-500 fill-gray-700" width="20"
                                            height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.54142 3.7915C6.54142 2.54886 7.54878 1.5415 8.79142 1.5415H11.2081C12.4507 1.5415 13.4581 2.54886 13.4581 3.7915V4.0415H15.6252H16.666C17.0802 4.0415 17.416 4.37729 17.416 4.7915C17.416 5.20572 17.0802 5.5415 16.666 5.5415H16.3752V8.24638V13.2464V16.2082C16.3752 17.4508 15.3678 18.4582 14.1252 18.4582H5.87516C4.63252 18.4582 3.62516 17.4508 3.62516 16.2082V13.2464V8.24638V5.5415H3.3335C2.91928 5.5415 2.5835 5.20572 2.5835 4.7915C2.5835 4.37729 2.91928 4.0415 3.3335 4.0415H4.37516H6.54142V3.7915ZM14.8752 13.2464V8.24638V5.5415H13.4581H12.7081H7.29142H6.54142H5.12516V8.24638V13.2464V16.2082C5.12516 16.6224 5.46095 16.9582 5.87516 16.9582H14.1252C14.5394 16.9582 14.8752 16.6224 14.8752 16.2082V13.2464ZM8.04142 4.0415H11.9581V3.7915C11.9581 3.37729 11.6223 3.0415 11.2081 3.0415H8.79142C8.37721 3.0415 8.04142 3.37729 8.04142 3.7915V4.0415ZM8.3335 7.99984C8.74771 7.99984 9.0835 8.33562 9.0835 8.74984V13.7498C9.0835 14.1641 8.74771 14.4998 8.3335 14.4998C7.91928 14.4998 7.5835 14.1641 7.5835 13.7498V8.74984C7.5835 8.33562 7.91928 7.99984 8.3335 7.99984ZM12.4168 8.74984C12.4168 8.33562 12.081 7.99984 11.6668 7.99984C11.2526 7.99984 10.9168 8.33562 10.9168 8.74984V13.7498C10.9168 14.1641 11.2526 14.4998 11.6668 14.4998C12.081 14.4998 12.4168 14.1641 12.4168 13.7498V8.74984Z"
                                                fill=""></path>
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div x-data="{ checked: false }" class="flex items-center gap-3">
                                            <div @click="checked = !checked"
                                                class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px] bg-white border-gray-300"
                                                :class="checked ? 'border-blue-500 bg-blue-500' : 'bg-white border-gray-300'">
                                                <svg :class="checked ? 'block' : 'hidden'" width="14" height="14"
                                                    viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    class="hidden">
                                                    <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white"
                                                        stroke-width="1.94437" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block font-medium text-gray-700 text-theme-sm">
                                                    DE124321
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex items-center justify-center w-10 h-10 rounded-full bg-success-50">
                                                <span class="text-xs font-semibold text-success-600">
                                                    TG
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-theme-sm mb-0.5 block font-medium text-gray-700">
                                                    Terry Geidt
                                                </span>
                                                <span class="text-gray-500 text-theme-sm">
                                                    terry@gmail.com
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            Software License
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            $18,50.34
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            2024-06-15
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p
                                            class="bg-success-50 text-theme-xs text-success-600 rounded-full px-2 py-0.5 font-medium">
                                            Complete
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <svg class="cursor-pointer hover:fill-error-500 fill-gray-700" width="20"
                                            height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.54142 3.7915C6.54142 2.54886 7.54878 1.5415 8.79142 1.5415H11.2081C12.4507 1.5415 13.4581 2.54886 13.4581 3.7915V4.0415H15.6252H16.666C17.0802 4.0415 17.416 4.37729 17.416 4.7915C17.416 5.20572 17.0802 5.5415 16.666 5.5415H16.3752V8.24638V13.2464V16.2082C16.3752 17.4508 15.3678 18.4582 14.1252 18.4582H5.87516C4.63252 18.4582 3.62516 17.4508 3.62516 16.2082V13.2464V8.24638V5.5415H3.3335C2.91928 5.5415 2.5835 5.20572 2.5835 4.7915C2.5835 4.37729 2.91928 4.0415 3.3335 4.0415H4.37516H6.54142V3.7915ZM14.8752 13.2464V8.24638V5.5415H13.4581H12.7081H7.29142H6.54142H5.12516V8.24638V13.2464V16.2082C5.12516 16.6224 5.46095 16.9582 5.87516 16.9582H14.1252C14.5394 16.9582 14.8752 16.6224 14.8752 16.2082V13.2464ZM8.04142 4.0415H11.9581V3.7915C11.9581 3.37729 11.6223 3.0415 11.2081 3.0415H8.79142C8.37721 3.0415 8.04142 3.37729 8.04142 3.7915V4.0415ZM8.3335 7.99984C8.74771 7.99984 9.0835 8.33562 9.0835 8.74984V13.7498C9.0835 14.1641 8.74771 14.4998 8.3335 14.4998C7.91928 14.4998 7.5835 14.1641 7.5835 13.7498V8.74984C7.5835 8.33562 7.91928 7.99984 8.3335 7.99984ZM12.4168 8.74984C12.4168 8.33562 12.081 7.99984 11.6668 7.99984C11.2526 7.99984 10.9168 8.33562 10.9168 8.74984V13.7498C10.9168 14.1641 11.2526 14.4998 11.6668 14.4998C12.081 14.4998 12.4168 14.1641 12.4168 13.7498V8.74984Z"
                                                fill=""></path>
                                        </svg>
                                    </div>
                                </td>
                            </tr> --}}
                        </tbody>
                        <!-- table body end -->
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

                        <span class="block text-sm font-medium text-gray-700 sm:hidden">
                            Page 1 of 10
                        </span>

                        <ul class="hidden items-center gap-0.5 sm:flex">
                            <li>
                                <a href="#"
                                    class="bg-blue-500/[0.08] text-theme-sm text-blue-500 hover:bg-blue-500/[0.08] hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium">
                                    1
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="text-theme-sm hover:bg-blue-500/[0.08] hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700">
                                    2
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="text-theme-sm hover:bg-blue-500/[0.08] hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700">
                                    3
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="text-theme-sm hover:bg-blue-500/[0.08] hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700">
                                    ...
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="text-theme-sm hover:bg-blue-500/[0.08] hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700">
                                    8
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="text-theme-sm hover:bg-blue-500/[0.08] hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700">
                                    9
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="text-theme-sm hover:bg-blue-500/[0.08] hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700">
                                    10
                                </a>
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
