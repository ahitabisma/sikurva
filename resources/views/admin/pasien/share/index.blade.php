@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6" x-data="{
        'sharePasienPoint': {{ $pointSettings->where('name', 'SHARE-PASIEN')->value('points') ?? 0 }},
    }">


        {{-- Shared --}}
        <div class="border border-gray-200 bg-white rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <form method="POST" action="{{ route('patient.share.store', $patient->id) }}" id="share-form-patient">
                    @csrf
                    <x-input-label for="email" :value="__('Email')" required />
                    <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')"
                        required autofocus autocomplete="email" placeholder="Masukkan Email Yang Akan Dituju" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    <x-primary-button class="mt-4" type="button" id="share-btn">
                        {{ __('Share') }}
                    </x-primary-button>
                </form>
            </div>

            <!-- Confirmation Modal -->
            <div id="share-confirmation-modal"
                class="hidden fixed inset-0 flex items-center justify-center p-4 bg-gray-400/50 backdrop-blur-lg"
                style="z-index: 99999 !important">
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
                                    Tindakan ini membutuhkan <strong class='text-blue-500' x-text="sharePasienPoint + ' point'"></strong>
                                    Apakah Anda yakin ingin melanjutkan?
                                </p>
                                <div class="flex gap-3">
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"
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

            <div class="max-w-full overflow-x-auto custom-scrollbar">
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
                                                {{ $share->shared_to_name }}
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
                                                url="{{ route('patient.share.stop', $share->id) }}" />
                                        @elseif($share->status === 'rejected')
                                            <x-modal-delete title="Hapus" textBtn="Hapus"
                                                message="Apakah Anda yakin ingin menghapuas?" confirmText="Ya"
                                                cancelText="Batal" url="{{ route('patient.share.stop', $share->id) }}" />
                                        @endif
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
                {{-- {!! $shares->links() !!} --}}
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shareForm = document.getElementById('share-form-patient');
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
    </script>
@endsection
