@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 lg:mb-7">
            Profile
        </h3>

        @role('admin')
            <div
                class="flex flex-col gap-10 md:gap-0 md:flex-row items-center justify-between p-5 mb-6 border border-gray-200 rounded-2xl lg:p-6">
                <div class="flex sm:flex-row flex-col items-center gap-5 w-full">
                    <div class="w-20 h-20 overflow-hidden border border-gray-200 rounded-full">
                        @if ($header)
                            <img src="{{ asset('img-public/header/' . $header) }}" alt="user" class="w-20 h-20 object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                    </div>

                    <div class="order-3 xl:order-2">
                        <h4 class="mb-2 text-lg font-semibold text-center text-gray-800 xl:text-left">
                            Header
                        </h4>
                        {{-- Update Header Point --}}
                        @if (Auth::user()->isSupportHeader())
                            <div>
                                <div class="mt-2 flex items-center gap-1 text-sm text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Akun support tidak diizinkan untuk mengubah header. Hubungi administrator untuk
                                        informasi lebih lanjut.</span>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('profile.update-header') }}" method="post" enctype="multipart/form-data"
                                class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-3" id="header-form">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <input type="file" name="header" accept="image/*"
                                        class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden">

                                    {{-- Notes --}}
                                    <div class="mt-1 w-full text-left">
                                        <p class="text-xs text-gray-500">
                                            Maksimal ukuran file 100 KB, format gambar: JPG, JPEG, PNG.<br>
                                            Ukuran gambar yang disarankan adalah ukuran
                                            gambar dengan rasio 1:1.
                                        </p>
                                    </div>

                                    <x-input-error class="mt-1" :messages="$errors->get('header')" />
                                </div>
                                <x-primary-button class="flex w-full !max-w-full xl:w-auto">
                                    {{ 'Simpan' }}
                                </x-primary-button>
                                @if ($header)
                                    <button type="button" onclick="confirmDeleteHeader()"
                                        class="flex items-center justify-center w-full xl:w-auto px-3 py-3 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out">
                                        <i class="fas fa-trash mr-1"></i> Hapus Header
                                    </button>
                                @endif
                            </form>
                        @endif
                    </div>
                </div>

                <div class="w-full md:w-1/5 text-center">
                    <p>Total Points </p>
                    <div class="text-center flex justify-center">
                        <a href="{{ route('aktivitas.index') }}"
                            class="flex items-center justify-center gap-1 rounded-full bg-slate-400 px-2.5 py-0.5 font-medium text-theme-sm text-white transition duration-300 ease-in-out hover:bg-slate-600 w-fit">
                            {{ $totalPoin ?? 0 }}
                            points
                        </a>
                    </div>
                    @if ($expiredAt)
                        <p class="text-xs text-gray-700 mt-1">Expired :
                            {{ \Carbon\Carbon::parse($expiredAt)->translatedFormat('d M Y') ?? '-' }}
                        </p>
                    @endif
                </div>
            </div>
        @endrole

        @if (Auth::user()->is_nakes && Auth::user()->instansi)
            <div class="p-5 mb-6 border border-gray-200 rounded-2xl lg:p-6">
                <div class="flex sm:flex-row flex-col items-center gap-5 w-full mb-4">
                    <div class="order-3 xl:order-2 w-full">
                        <h4 class="mb-2 text-md font-semibold text-gray-800">
                            Email Sender Display Name
                        </h4>

                        <form action="{{ route('profile.update-sender-name') }}" method="post"
                            class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-3">
                            @csrf
                            @method('PATCH')

                            <div class="w-full">
                                <x-text-input id="sender_name" class="block w-full" type="text" name="sender_name"
                                    :value="Auth::user()->instansi->sender_name ?? ''" placeholder="Masukkan Email Sender Display Name" />
                                <p class="text-xs text-gray-500 mt-1">
                                    Nama yang akan ditampilkan sebagai pengirim pada saat pengiriman email.
                                </p>
                                <x-input-error :messages="$errors->get('sender_name')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-2">
                                <x-primary-button class="flex w-full !max-w-full xl:w-auto">
                                    {{ __('Simpan') }}
                                </x-primary-button>

                                @if (Auth::user()->instansi && Auth::user()->instansi->sender_name)
                                    <button type="button" onclick="confirmDeleteSenderName()"
                                        class="flex items-center justify-center w-full xl:w-auto px-3 py-3 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Sender Name Confirmation Modal -->
            <div id="delete-sender-name-modal" x-cloak
                class="hidden fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
                style="z-index: 99999 !important">
                <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-lg">
                    <button onclick="closeDeleteSenderNameModal()"
                        class="absolute right-3 top-3 text-gray-400 hover:text-gray-700">
                        ✖
                    </button>

                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Hapus Email Sender Display Name</h4>
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus Email Sender Display Name? Nama
                        default akan
                        digunakan sebagai pengganti.</p>

                    <div class="flex justify-end mt-5">
                        <button onclick="closeDeleteSenderNameModal()"
                            class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <form action="{{ route('profile.delete-sender-name') }}" method="post" id="delete-sender-name-form"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="ml-3 px-4 py-2 text-sm text-white bg-red-500 rounded-lg hover:bg-red-600">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- Personal Information --}}
        <div class="p-5 mb-6 border border-gray-200 rounded-2xl lg:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 lg:mb-6">
                        Personal Information
                    </h4>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Nama
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ Auth::user()->name }}
                            </p>
                        </div>

                        @if (Auth::user()->getInstansi())
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500">
                                    Klinik
                                </p>
                                <p class="text-sm font-medium text-gray-800">
                                    {{ Auth::user()->getInstansi() }}
                                    @if (Auth::user()->getInstansiVerified())
                                        <i class="fa-solid fa-circle-check ml-1 text-green-500"></i>
                                    @else
                                        <i class="fa-solid fa-circle-xmark ml-1 text-red-500"></i>
                                    @endif
                                </p>
                            </div>
                        @endif

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Email address
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Phone
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ Auth::user()->phone }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Address
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ Auth::user()->address }}
                            </p>
                        </div>

                        @role('admin')
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500">
                                    Kode Referral
                                </p>
                                <p class="text-sm font-medium text-gray-800">
                                    {{ Auth::user()->referral_code ?? (Auth::user()->instansi->referral_code ?? '-') }}
                                </p>
                            </div>
                        @endrole
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}"
                    class="flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 lg:inline-flex lg:w-auto">
                    <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                            fill="" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        {{-- Shared --}}
        @if (Auth::user()->is_nakes)
            <div class="border border-gray-200 bg-white rounded-lg" x-data="{
                'collaboratorPoint': {{ $pointSettings->where('name', 'KOLABORASI')->value('points') ?? 0 }},
            }">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Share Data Pasien</h2>
                    <p class="text-sm text-gray-500">Share data pasien anda ke sesama Tenaga Kesehatan dengan jumlah
                        collaborator (maksimal 3)
                    </p>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Email Input Section -->
                        <form method="POST" action="{{ route('patient.collaborator.store') }}" id="share-form">
                            @csrf
                            <x-input-label for="email" :value="__('Email')" required />
                            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email"
                                :value="old('email')" required autocomplete="email"
                                placeholder="Masukkan Email Yang Akan Dituju" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />

                            <div class="flex items-center space-x-3 mt-4">
                                <x-primary-button class="min-w-fit" type="button" id="share-btn">
                                    {{ __('Tambah Collaborator') }}
                                </x-primary-button>

                                @if (session('error_collab'))
                                    <x-alert-error :title="session('error_collab')" />
                                @endif
                                @if (session('success_collab'))
                                    <x-alert-success :title="session('success_collab')" />
                                @endif
                            </div>
                        </form>

                        <!-- Confirmation Modal -->
                        <div id="share-confirmation-modal" x-cloak
                            class="hidden fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
                            style="z-index: 99999 !important" x-transition:enter="transition ease duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease duration-300" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0">
                            <div class="relative w-full max-w-md rounded-2xl bg-white p-4 shadow-lg">
                                <div class="p-3">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Share Data</h3>
                                    <div class="" id="consent-section">
                                        <div class="text-sm">
                                            <p class="font-medium mb-3">
                                                "Saya menyetujui agar data saya dapat di lihat dan di tambah oleh Alamat
                                                Email / User
                                                lainnya"
                                            </p>
                                            <p class="mb-4 text-sm text-gray-600">
                                                User penerima akan mendapat notifikasi dan harus menerima undangan
                                                Anda
                                            </p>
                                            <p class="mb-4 text-sm text-gray-600">
                                                Tindakan ini membutuhkan <strong class='text-blue-500'
                                                    x-text="collaboratorPoint + ' point'"></strong>
                                                Apakah Anda yakin ingin melanjutkan?
                                            </p>
                                            <div class="flex gap-3">
                                                <button
                                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"
                                                    id="confirm-add-btn">
                                                    I Agree & Send Invitation
                                                </button>
                                                <button
                                                    class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg"
                                                    id="cancel-btn">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Collaborators List -->
                        <div class="max-w-full overflow-x-auto custom-scrollbar mt-5">
                            <table class="min-w-full">
                                <!-- table header start -->
                                <thead class="border-gray-100 border-y bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <span class="block font-medium text-gray-500 text-theme-xs">
                                                        No
                                                    </span>
                                                </div>
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div>
                                                    <span class="block font-medium text-gray-500 text-theme-xs">
                                                        User
                                                    </span>
                                                </div>
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
                                                    Accepted At
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
                                    @forelse ($shares as $share)
                                        <tr>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center justify-center">
                                                    {{ ($shares->currentPage() - 1) * $shares->perPage() + $loop->iteration }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center justify-center">
                                                    <div>
                                                        <span class="block font-medium text-gray-700 text-theme-sm">
                                                            {{ $share->collaborator_name }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center justify-center">
                                                    <div>
                                                        @if ($share->status == 'accepted')
                                                            <p
                                                                class="rounded-full bg-green-50 px-2 py-0.5 text-theme-xs font-medium text-green-600">
                                                                {{ $share->status }}
                                                            </p>
                                                        @elseif($share->status == 'rejected')
                                                            <p
                                                                class="rounded-full bg-red-50 px-2 py-0.5 text-theme-xs font-medium text-red-600">
                                                                {{ $share->status }}
                                                            </p>
                                                        @elseif($share->status == 'pending')
                                                            <p
                                                                class="rounded-full bg-warning-50 px-2 py-0.5 text-theme-xs font-medium text-warning-600">
                                                                {{ $share->status }}
                                                            </p>
                                                        @else
                                                            <p>-</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center justify-center">
                                                    <div>
                                                        <span class="block font-medium text-gray-700 text-theme-sm">
                                                            {{ $share->accepted_at ? \Carbon\Carbon::parse($share->accepted_at)->format('d F Y H:i:s') : '-' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center justify-center space-x-2">
                                                    @if ($share->status === 'accepted')
                                                        <x-modal-delete title="Stop Share" textBtn="Stop Share"
                                                            message="Apakah Anda yakin ingin menghentikan share pasien ini?"
                                                            confirmText="Ya" cancelText="Batal"
                                                            url="{{ route('patient.collaborator.stop', $share->id) }}" />
                                                    @elseif($share->status === 'rejected')
                                                        <x-modal-delete title="Hapus" textBtn="Hapus"
                                                            message="Apakah Anda yakin ingin menghapuas?" confirmText="Ya"
                                                            cancelText="Batal"
                                                            url="{{ route('patient.collaborator.stop', $share->id) }}" />
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center justify-center">
                                                    <p class="text-gray-500 text-theme-xs">
                                                        Belum ada collaborator
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <!-- table body end -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Delete Header Confirmation Modal -->
        <div id="delete-header-modal" x-cloak
            class="hidden fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
            style="z-index: 99999 !important">
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-lg">
                <button onclick="closeDeleteHeaderModal()"
                    class="absolute right-3 top-3 text-gray-400 hover:text-gray-700">
                    ✖
                </button>

                <h4 class="text-lg font-semibold text-gray-800 mb-4">Hapus Header Image</h4>
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus header image? Tindakan ini tidak dapat
                    dibatalkan.</p>

                <div class="flex justify-end mt-5">
                    <button onclick="closeDeleteHeaderModal()"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    @if ($header)
                        <form action="{{ route('profile.delete-header') }}" method="post" id="delete-header-form"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="submitDeleteHeader()"
                                class="ml-3 px-4 py-2 text-sm text-white bg-red-500 rounded-lg hover:bg-red-600">
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shareForm = document.getElementById('share-form');
            const shareBtn = document.getElementById('share-btn');
            const modal = document.getElementById('share-confirmation-modal');
            const confirmBtn = document.getElementById('confirm-add-btn');
            const cancelBtn = document.getElementById('cancel-btn');

            // Prevent default form submission when Enter is pressed
            shareForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate form first
                if (shareForm.checkValidity()) {
                    modal.classList.remove('hidden');
                } else {
                    shareForm.reportValidity();
                }
            });

            // Show modal when share button is clicked
            shareBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Validate form first
                if (shareForm.checkValidity()) {
                    modal.classList.remove('hidden');
                } else {
                    shareForm.reportValidity();
                }
            });

            // Hide modal when cancel is clicked
            cancelBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            // Submit form when confirmed
            confirmBtn.addEventListener('click', function() {
                shareForm.submit();
            });

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        function confirmDeleteHeader() {
            document.getElementById('delete-header-modal').classList.remove('hidden');
        }

        function closeDeleteHeaderModal() {
            document.getElementById('delete-header-modal').classList.add('hidden');
        }

        function submitDeleteHeader() {
            document.getElementById('delete-header-form').submit();
        }

        function confirmDeleteSenderName() {
            document.getElementById('delete-sender-name-modal').classList.remove('hidden');
        }

        function closeDeleteSenderNameModal() {
            document.getElementById('delete-sender-name-modal').classList.add('hidden');
        }
    </script>
@endsection
