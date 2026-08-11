@extends('layouts.tailadmin')

@section('content')
    <div x-data="{ isModalOpen: false }">
        <div class="rounded-2xl border border-gray-200 bg-white">
            <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
                <form action="{{ route('super-admin.klinik.update', $klinik->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="-mx-2.5 flex flex-wrap gap-y-5">



                        <div class="w-full px-2.5 xl:w-full">
                            <x-input-label for="name" :value="__('Nama Klinik')" required />
                            <x-text-input id="name" placeholder="Masukkan Nama Klinik" name="name" required
                                :value="$klinik->name" />

                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <!-- Header Image Section -->
                        <div class="w-1/2 px-2.5 xl:w-1/2">
                            <div class="">
                                <div>
                                    <label for="header-image" class="mb-2 text-sm font-medium text-gray-700">
                                        Header Image
                                    </label>
                                    <div class="mt-1 mb-4 flex justify-center">
                                        <div
                                            class="w-32 h-32 overflow-hidden bg-gray-100 border border-gray-200 rounded-full">
                                            <img id="header-image-preview"
                                                src="{{ $klinik->header ? asset('img-public/header/' . $klinik->header) : '' }}"
                                                alt="Header Preview" class="w-full h-full object-cover " />
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <input type="file" name="header_image" id="header-image" accept="image/*"
                                            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden"
                                            onchange="previewImage()" />
                                        @if ($klinik->header)
                                            <button type="button" id="delete-header-btn"
                                                class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600"
                                                @click="isModalOpen = true">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        @endif
                                    </div>
                                    <input type="hidden" name="current_header_image" value="{{ $klinik->header ?? '' }}" />
                                    <input type="hidden" name="delete_header_image" id="delete-header-image"
                                        value="0" />
                                    <x-input-error :messages="$errors->get('header_image')" />
                                </div>
                            </div>
                        </div>

                        <!-- Verify Status Section -->
                        <div class="w-full px-2.5 xl:w-full">
                            <x-input-label for="verify" :value="__('Status Verifikasi')" required />
                            <div class="mt-2 flex items-center gap-6" x-data="{ verify: '{{ $klinik->is_verified }}' }">
                                <label for="verify_yes"
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" id="verify_yes" name="verify" value="1" class="sr-only"
                                            x-model="verify" required>
                                        <div :class="verify === '1' ? 'border-brand-500 bg-brand-500' :
                                            'bg-transparent border-gray-300'"
                                            class="hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                                            <span class="h-2 w-2 rounded-full bg-white"></span>
                                        </div>
                                    </div>
                                    Verify
                                </label>

                                <label for="verify_no"
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" id="verify_no" name="verify" value="0" class="sr-only"
                                            x-model="verify">
                                        <div :class="verify === '0' ? 'border-brand-500 bg-brand-500' :
                                            'bg-transparent border-gray-300'"
                                            class="hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                                            <span class="h-2 w-2 rounded-full bg-white"></span>
                                        </div>
                                    </div>
                                    Not Verify
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('verify')" class="mt-2" />
                        </div>

                        <div class="w-full px-2.5">
                            <div class="flex items-center gap-3 mt-1">
                                <button type="submit"
                                    class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                                    Simpan
                                </button>
                                <a href="{{ route('super-admin.klinik.index') }}"
                                    class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-cloak>
            <div x-show="isModalOpen"
                class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
                style="z-index: 99999 !important" x-transition:enter="transition ease duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">

                <div @click.outside="isModalOpen = false"
                    class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-lg">
                    <!-- Tombol Close -->
                    <button @click="isModalOpen = false" class="absolute right-3 top-3 text-gray-400 hover:text-gray-700">
                        ✖
                    </button>

                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Hapus Header Image</h4>
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus header image? Tindakan ini tidak
                        dapat
                        dibatalkan.</p>

                    <div class="flex justify-end mt-5">
                        <button @click="isModalOpen = false"
                            class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button @click="isModalOpen = false; $nextTick(() => { deleteHeaderImage() })"
                            class="ml-3 px-4 py-2 text-sm text-white bg-red-500 rounded-lg hover:bg-red-600">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function previewImage() {
            const input = document.getElementById('header-image');
            const preview = document.getElementById('header-image-preview');
            const deleteHeaderInput = document.getElementById('delete-header-image');

            // Reset delete flag if user uploads a new image
            deleteHeaderInput.value = "0";

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function deleteHeaderImage() {
            const preview = document.getElementById('header-image-preview');
            const deleteHeaderInput = document.getElementById('delete-header-image');
            const deleteButton = document.getElementById('delete-header-btn');

            // Set flag to delete the header
            deleteHeaderInput.value = "1";

            // Clear the preview
            preview.src = '';

            // Hide delete button
            deleteButton.style.display = 'none';

            // Close the modal properly by accessing the parent component
            const alpineRoot = document.querySelector('[x-data]').__x;
            if (alpineRoot && alpineRoot.$data) {
                alpineRoot.$data.isModalOpen = false;
            }
        }
    </script>
@endsection
