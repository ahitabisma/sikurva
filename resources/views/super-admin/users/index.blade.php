@extends('layouts.tailadmin')

@section('content')
    <div x-data="addPointsModal()" class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <x-tambah-button url="{{ route('super-admin.users.create') }}" text="Tambah Pengguna" />
                        <x-export-button url="{{ route('super-admin.users.export') }}" text="Export All Users"
                            color="orange" />
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <form method="GET" action="{{ route('super-admin.users.index') }}"
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
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            No
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            User
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Total Pasien
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Total Generate
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Poin
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Status
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Support Header
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
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-theme-xs  rounded-full px-2 py-0.5 font-medium">
                                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex items-center justify-center w-5 h-5 rounded-full {{ $user->is_nakes ? 'bg-blue-100' : 'bg-red-100' }}">
                                                    <span
                                                        class="text-xs font-semibold {{ $user->is_nakes ? 'text-blue-500' : 'text-red-500' }}">
                                                        {{ $user->is_nakes ? 'N' : 'A' }} </span>
                                                </div>
                                                <div>
                                                    <span class="text-theme-sm mb-0.5 block font-medium text-gray-700 ">
                                                        @if ($user->is_nakes)
                                                            {{ $user->name . ' (' . ($user->instansi_name ?? '') . ')' }}
                                                        @else
                                                            {{ $user->name }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ $user->total_patients ?? 0 }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                @if ($user->is_nakes)
                                                    {{ $user->total_download_instansi ?? 0 }}
                                                @else
                                                    {{ $user->total_download_user ?? 0 }}
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="flex items-center justify-center">
                                                <button
                                                    @click="openModal({{ $user->id }}, '{{ $user->name }}', {{ $user->is_nakes ? 'true' : 'false' }})"
                                                    class="text-blue-500 hover:text-blue-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>

                                                <p class="text-gray-700 text-theme-sm mr-2">
                                                    @if ($user->is_nakes)
                                                        {{ number_format($user->total_active_points_instansi) ?? 0 }}
                                                    @else
                                                        {{ number_format($user->total_active_points_user) ?? 0 }}
                                                    @endif
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-gray-700 text-theme-sm mr-2">
                                                    @if ($user->is_nakes)
                                                        {{ $user->instansi_latest_expired ? '(' . \Carbon\Carbon::parse($user->instansi_latest_expired)->translatedFormat('d M y') . ')' : '' }}
                                                    @else
                                                        {{ $user->user_latest_expired ? '(' . \Carbon\Carbon::parse($user->user_latest_expired)->translatedFormat('d M y') . ')' : '' }}
                                                    @endif
                                                </p>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            @if ($user->is_nakes)
                                                <p
                                                    class="{{ $user->point_status_instansi === 'Aktif' ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }} text-theme-xs  rounded-full px-2 py-0.5 font-medium">
                                                    {{ $user->point_status_instansi === 'Aktif' ? 'Aktif' : 'Tidak Aktif' }}
                                                </p>
                                            @else
                                                <p
                                                    class="{{ $user->point_status_user === 'Aktif' ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }} text-theme-xs  rounded-full px-2 py-0.5 font-medium">
                                                    {{ $user->point_status_user === 'Aktif' ? 'Aktif' : 'Tidak Aktif' }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                    <!-- Replace the existing toggle form with this fixed version -->
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <form
                                                action="{{ route('super-admin.users.update-is-support-header', $user->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="is_nakes"
                                                    value="{{ $user->is_nakes ? 'true' : 'false' }}">

                                                <!-- Fix the status value calculation -->
                                                <input type="hidden" name="status"
                                                    value="{{ $user->is_nakes ? ($user->instansi_is_support_header ? '0' : '1') : ($user->is_support_header ? '0' : '1') }}">

                                                <button type="submit"
                                                    class="relative inline-flex h-6 w-11 items-center rounded-full
                {{ $user->is_nakes ? ($user->instansi_is_support_header ? 'bg-brand-500' : 'bg-gray-200') : ($user->is_support_header ? 'bg-brand-500' : 'bg-gray-200') }}">
                                                    <span class="sr-only">Toggle support header</span>
                                                    <span
                                                        class="shadow-theme-sm absolute {{ $user->is_nakes ? ($user->instansi_is_support_header ? 'translate-x-6' : 'translate-x-1') : ($user->is_support_header ? 'translate-x-6' : 'translate-x-1') }}
                    inline-block h-4 w-4 transform rounded-full bg-white transition duration-300"></span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('super-admin.users.edit', $user->id) }}"><x-svg-edit /></a>
                                            <x-modal-delete title="Hapus User"
                                                message="Apakah Anda yakin ingin menghapus data user ini?"
                                                confirmText="Hapus" cancelText="Batal"
                                                url="{{ route('super-admin.users.destroy', $user->id) }}" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-3 whitespace-nowrap">
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
                    {!! $users->links() !!}
                </div>
            </div>
            <!-- Table Four -->
        </div>

        <!-- filepath: c:\laragon\www\work\ekurva\resources\views\super-admin\users\index.blade.php -->
        <!-- Simplified Add Points Modal -->
        <div x-cloak x-show="modalOpen"
            class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
            style="z-index: 99999 !important">
            <div class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
            <div @click.outside="modalOpen = false"
                class="relative w-full max-w-[584px] rounded-3xl bg-white p-6 lg:p-10">
                <!-- close btn -->
                <button @click="modalOpen = false"
                    class="group absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-200 text-gray-500 transition-colors hover:bg-gray-300 hover:text-gray-500 sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                    <svg class="transition-colors fill-current group-hover:text-gray-600" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z">
                    </svg>
                </button>

                <form action="{{ route('super-admin.users.add-points') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" x-bind:value="userId">
                    <input type="hidden" name="is_nakes" x-bind:value="isNakes">

                    <h4 class="mb-6 text-lg font-medium text-gray-800">
                        Tambah Point Bonus
                    </h4>

                    <div class="mb-6">
                        <p class="text-gray-600 mb-4">
                            Tambahkan point secara manual ke <span x-text="userName" class="font-semibold"></span>
                        </p>
                    </div>

                    <div class="mb-6">
                        <label for="points" class="block mb-2 text-sm font-medium text-gray-700">Jumlah Point</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input type="number" id="points" name="points" x-model="points" min="1"
                                required
                                class="mt-1 block w-full pl-10 rounded-lg border border-gray-300 shadow-sm py-3 focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Point akan ditambahkan sebagai bonus</p>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Keterangan</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <textarea id="description" name="description" x-model="description" rows="3" required
                                class="mt-1 block w-full pl-10 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Masukkan alasan penambahan point"></textarea>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="duration" class="block mb-2 text-sm font-medium text-gray-700">Masa Berlaku</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="number" id="duration" name="duration" x-model="duration" min="1"
                                    required
                                    class="mt-1 block w-full pl-10 rounded-lg border border-gray-300 shadow-sm py-3 focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <select id="durationType" name="duration_type" x-model="durationType" required
                                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm py-3 focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="bulan">Bulan</option>
                                    <option value="tahun">Tahun</option>
                                </select>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Masa berlaku point sebelum kadaluarsa</p>
                    </div>

                    <div class="flex items-center justify-end w-full gap-3 mt-6">
                        <button @click.prevent="modalOpen = false" type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 sm:w-auto">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none sm:w-auto">
                            Tambah Point
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function addPointsModal() {
            return {
                modalOpen: false,
                userId: null,
                userName: '',
                isNakes: false,
                points: '',
                description: '',
                duration: '1',
                durationType: 'bulan',

                openModal(id, name, isNakes) {
                    this.modalOpen = true;
                    this.userId = id;
                    this.userName = name;
                    this.isNakes = isNakes;
                    this.points = '';
                    this.description = '';
                    this.duration = '30';
                    this.durationType = 'bulan';
                    document.body.style.overflow = 'hidden';
                },

                closeModal() {
                    this.modalOpen = false;
                    document.body.style.overflow = '';
                }
            }
        }
    </script>
@endsection
