@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('super-admin.klinik.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="-mx-2.5 flex flex-wrap gap-y-5">

                    <div class="w-full px-2.5 xl:w-full">
                        <x-input-label for="nama" :value="__('Nama Klinik')" required />
                        <x-text-input id="nama" placeholder="Masukkan Nama Klinik" name="name" required
                            :value="old('name')" />

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
                                    <div class="w-32 h-32 overflow-hidden bg-gray-100 border border-gray-200 rounded-full">
                                        <img id="header-image-preview" src="" alt="Header image preview"
                                            class="w-full h-full object-cover" />
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 mb-3">
                                    <input type="file" name="header_image" id="header-image" accept="image/*"
                                        class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden"
                                        onchange="previewImage()" />
                                </div>
                                <x-input-error :messages="$errors->get('header_image')" />
                            </div>
                        </div>
                    </div>

                    <!-- Verify Status Section -->
                    <div class="w-full px-2.5 xl:w-full">
                        <x-input-label for="verify" :value="__('Status Verifikasi')" required />
                        <div class="mt-2 flex items-center gap-6" x-data="{ verify: '{{ old('verify', '') }}' }">
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
@endsection

@section('script')
    <script>
        function previewImage() {
            const input = document.getElementById('header-image');
            const preview = document.getElementById('header-image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
