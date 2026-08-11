@extends('layouts.tailadmin')

@section('content')
    <div x-data="adsHeaderSettings" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- SECTION 1: HEADER IMAGE -->
        <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5">
            <form action="{{ route('super-admin.landing-page.ads-header.update-header') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Header dengan Tombol -->
                <div class="pb-5 mb-5 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-800">Header</h1>
                    <div>
                        <button type="submit"
                            class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                            Simpan Header
                        </button>
                    </div>
                </div>

                <!-- Header Image Upload & Preview Section -->
                <div>
                    <label for="header-image" class="mb-2 text-sm font-medium text-gray-700">
                        Header Image
                    </label>
                    <div class="mt-2">
                        <input type="file" name="header_image" id="header-image" accept="image/*"
                            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden"
                            @change="handleHeaderImageUpload($event)" />
                    </div>

                    <x-input-error class="mt-1" :messages="$errors->get('header_image')" />

                    {{-- Notes --}}
                    <div class="mt-1 w-full text-left">
                        <p class="text-xs text-gray-500">
                            Maksimal ukuran file 100 KB, format gambar: JPG, JPEG, PNG.<br>
                            Ukuran gambar yang disarankan adalah ukuran
                            gambar dengan rasio 1:1.
                        </p>
                    </div>

                    <!-- Image Preview -->
                    <div class="mt-4" x-show="headerImage">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview Header:</p>
                        <div
                            class="relative w-full h-40 bg-gray-100 rounded-lg overflow-hidden flex justify-center items-center">
                            <div class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center">
                                <img :src="headerImage" alt="Header Preview" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- SECTION 2: ADS IMAGES -->
        <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5">
            <form action="{{ route('super-admin.landing-page.ads-header.update-ads') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Header dengan Tombol -->
                <div class="pb-5 mb-5 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-800">Ads</h1>
                    <div>
                        <button type="submit"
                            class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                            Simpan Ads
                        </button>
                    </div>
                </div>

                <!-- Ads Image Upload & Preview Section -->
                <div>
                    <label for="ads-image" class="mb-2 text-sm font-medium text-gray-700">
                        Ads Image
                    </label>
                    <div class="mt-2">
                        <input type="file" name="ads_image" id="ads-image" accept="image/*"
                            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden"
                            @change="handleAdsImageUpload($event)" />
                    </div>

                    <x-input-error class="mt-1" :messages="$errors->get('ads_image')" />

                    {{-- Notes --}}
                    <div class="mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal ukuran file 500 KB, format gambar: JPG, JPEG, PNG.<br>
                            Ukuran gambar yang disarankan: 1920 x 1280 px, 1500 x 1000 px, atau ukuran
                            lain dengan rasio 3:2 seperti 1024 x 683 px.
                        </p>
                    </div>

                    <!-- Image Preview -->
                    <div class="mt-4" x-show="adsImage">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview Ads:</p>
                        <div class="relative w-full h-40 bg-gray-100 rounded-lg overflow-hidden">
                            <img :src="adsImage" alt="Ads Preview" class="w-full h-full object-contain" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5 flex items-center justify-between p-5 bg-white border border-gray-200 rounded-2xl lg:p-6">
        <div class="flex sm:flex-row flex-col items-center gap-5 w-full">
            <div class="w-20 h-20 overflow-hidden border border-gray-200 rounded-full">
                <img src="{{ asset('img-public/header/' . $headerWebsite) }}" alt="user" class="w-20 h-20 object-cover">
            </div>


            <div class="order-3 xl:order-2">
                <h4 class="mb-2 text-lg font-semibold text-center text-gray-800 xl:text-left">
                    Logo General Untuk Website
                </h4>
                <form action="{{ route('profile.update-header') }}" method="post" enctype="multipart/form-data"
                    class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-3">
                    @csrf
                    @method('PATCH')

                    <div>

                        <input type="file" name="header"
                            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden"
                            accept="image/*">

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

                    <x-primary-button class="w-full xl:w-auto">{{ 'Simpan' }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('errors', @json($errors->messages()));

            Alpine.data('adsHeaderSettings', () => ({
                // Header data
                headerImage: @json($header['image'] ?? ''),
                headerImagePath: @json($header['image_path'] ?? ''),

                // Ads data
                adsImage: @json($ads['image'] ?? ''),
                adsImagePath: @json($ads['image_path'] ?? ''),

                isUploading: false,
                notification: {
                    type: "{{ session('success') ? 'success' : (session('error') ? 'error' : null) }}",
                    message: "{{ session('success') ?? (session('error') ?? null) }}"
                },

                // Header methods
                handleHeaderImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.isUploading = true;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.headerImage = e.target.result;
                            this.isUploading = false;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                // Ads methods
                handleAdsImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.isUploading = true;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.adsImage = e.target.result;
                            this.isUploading = false;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }));
        });
    </script>
@endsection
