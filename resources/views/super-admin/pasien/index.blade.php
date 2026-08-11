@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <x-tambah-button url="{{ route('super-admin.patient.create') }}" text="Tambah Pasien" />
                        <x-import-button url="{{ route('super-admin.patient.import') }}" text="Import Pasien" />
                        <x-export-button url="{{ route('super-admin.patient.export') }}" text="Export Pasien" />
                        <x-export-button url="{{ route('super-admin.patient.antro.export-all') }}" text="Export Antro All"
                            color="red" />
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <form method="GET" action="{{ route('super-admin.patient.index') }}"
                            class="flex flex-col gap-2 lg:flex-row">
                            <div class="relative">
                                <span class="absolute -translate-y-1/2 pointer-events-none top-1/2 left-4">
                                    <svg class="fill-gray-500" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                            fill=""></path>
                                    </svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                                    class="shadow-theme-xs focus:border-blue-300 focus:ring-blue-500/10 h-10 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px]">

                            </div>

                            <x-search-button>Cari</x-search-button>
                        </form>
                    </div>
                </div>

                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <!-- table header start -->
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                {{-- <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-xs">
                                                No
                                            </span>
                                        </div>
                                    </div>
                                </th> --}}
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <div>
                                            <span class="block font-medium text-gray-500 text-theme-xs">
                                                Kode MR
                                            </span>
                                        </div>
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
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Tanggal Lahir
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Usia Kehamilan (Minggu)
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Created By
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
                            @forelse ($patients as $patient)
                                <tr>
                                    {{-- <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            {{ ($patients->currentPage() - 1) * $patients->perPage() + $loop->iteration }}
                                        </div>
                                    </td> --}}
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <div>
                                                <span class="block font-medium text-gray-700 text-theme-sm">
                                                    {{ $patient->kode_lokal }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <a href="{{ route('super-admin.patient.preview', $patient->id) }}"
                                                class="flex items-center gap-3 hover:underline transition ease-in-out duration-300">
                                                <div
                                                    class="flex items-center justify-center w-5 h-5 rounded-full {{ $patient->jenis_kelamin === 'L' ? 'bg-blue-100' : 'bg-red-100' }}">
                                                    <span
                                                        class="text-xs font-semibold {{ $patient->jenis_kelamin === 'L' ? 'text-blue-500' : 'text-red-500' }}">
                                                        {{ $patient->jenis_kelamin }} </span>
                                                </div>
                                                <div>
                                                    <span class="text-theme-sm mb-0.5 block font-medium text-gray-700">
                                                        {{ $patient->nama }}
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ \Carbon\Carbon::parse($patient->tgl_lahir)->translatedFormat('d F y') }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center flex-col">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ $patient->usia_kehamilan_minggu }} minggu
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ $patient->created_by_name }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('super-admin.patient.edit', $patient->id) }}"
                                                type="button"><x-svg-edit /></a>
                                            <x-modal-delete title="Hapus User"
                                                message="Apakah Anda yakin ingin menghapus data user ini?"
                                                confirmText="Hapus" cancelText="Batal"
                                                url="{{ route('super-admin.patient.destroy', $patient->id) }}" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-500 text-theme-xs">
                                                Tidak ada data
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <!-- table body end -->
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    {!! $patients->links() !!}
                </div>
            </div>
            <!-- Table Four -->
        </div>
    </div>
@endsection
