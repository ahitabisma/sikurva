@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('super-admin.patient.store') }}" method="POST" id="tambah-pasien-form"
                x-data="{
                    tambahPasienPoint: {{ $pointSettings->where('name', 'TAMBAH-PASIEN')->value('points') ?? 0 }}
                }">
                @csrf
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <div class="w-full px-2.5">
                        <h4 class="pb-4 text-base font-medium text-gray-800 border-b border-gray-200">
                            Tambah Data Pasien
                        </h4>
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="kode_lokal" :value="__('Kode MR')" />
                        <div class="mt-1 flex">
                            {{-- <span
                                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg">
                                {{ $kode_dokter }}
                            </span> --}}
                            <x-text-input id="kode_lokal" type="text" name="kode_lokal"
                                class="rounded block w-full disabled:bg-gray-100" placeholder="Masukkan Kode MR" />
                        </div>
                        {{-- <p class="mt-1 text-xs text-gray-500">Kode MR tergenerate otomatis.
                        </p> --}}
                        <x-input-error :messages="$errors->get('kode_lokal')" class="mt-2" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="nama" :value="__('Nama')" required />
                        <x-text-input id="nama" type="text" name="nama" class="mt-1 block w-full"
                            placeholder="Masukkan Nama {{ Auth::user()->is_nakes ? 'Pasien' : 'Anak' }}"
                            value="{{ old('nama') }}" required />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" required />
                        <div class="mt-2 flex items-center gap-6" x-data="{ gender: '{{ old('jenis_kelamin', '') }}' }">
                            <label for="jenis_kelamin_l"
                                class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                <div class="relative">
                                    <input type="radio" id="jenis_kelamin_l" name="jenis_kelamin" value="L"
                                        class="sr-only" x-model="gender" required>
                                    <div :class="gender === 'L' ? 'border-brand-500 bg-brand-500' :
                                        'bg-transparent border-gray-300'"
                                        class="hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                                        <span class="h-2 w-2 rounded-full bg-white"></span>
                                    </div>
                                </div>
                                Laki-laki
                            </label>

                            <label for="jenis_kelamin_p"
                                class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                <div class="relative">
                                    <input type="radio" id="jenis_kelamin_p" name="jenis_kelamin" value="P"
                                        class="sr-only" x-model="gender">
                                    <div :class="gender === 'P' ? 'border-brand-500 bg-brand-500' :
                                        'bg-transparent border-gray-300'"
                                        class="hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                                        <span class="h-2 w-2 rounded-full bg-white"></span>
                                    </div>
                                </div>
                                Perempuan
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="tgl_lahir" :value="__('Tanggal Lahir')" required />
                        <x-date-input id="tgl_lahir" type="date" name="tgl_lahir" class="mt-1 block w-full"
                            value="{{ old('tgl_lahir') }}" required />
                        <x-input-error :messages="$errors->get('tgl_lahir')" class="mt-2" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="usia_kehamilan_minggu" :value="__('Usia Kehamilan (Minggu)')" required />
                        <x-text-input id="usia_kehamilan_minggu" type="number" name="usia_kehamilan_minggu"
                            class="mt-1 block w-full" min="27" max="40"
                            value="{{ old('usia_kehamilan_minggu', 40) }}"
                            placeholder="Masukkan Usia Kehamilan (27-40 Minggu)" required />
                        <x-input-error :messages="$errors->get('usia_kehamilan_minggu')" class="mt-2" />
                    </div>

                    <div class="w-full px-2.5">
                        <div class="flex flex-col lg:flex-row gap-5">
                            <div class="w-full lg:w-1/2">
                                <x-input-label for="tinggi_ayah" :value="__('Tinggi Ayah (cm)')" />
                                <x-text-input id="tinggi_ayah" type="number" name="tinggi_ayah" class="mt-1 block w-full"
                                    placeholder="Masukkan Tinggi Ayah" value="{{ old('tinggi_ayah') }}" />
                                <x-input-error :messages="$errors->get('tinggi_ayah')" class="mt-2" />
                            </div>

                            <div class="w-full lg:w-1/2">
                                <x-input-label for="tinggi_ibu" :value="__('Tinggi Ibu (cm)')" />
                                <x-text-input id="tinggi_ibu" type="number" name="tinggi_ibu" class="mt-1 block w-full"
                                    placeholder="Masukkan Tinggi Ibu" value="{{ old('tinggi_ibu') }}" />
                                <x-input-error :messages="$errors->get('tinggi_ibu')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" class="mt-1 block w-full"
                            placeholder="Masukkan Email {{ Auth::user()->is_nakes ? 'Pasien' : 'Anak' }}"
                            value="{{ old('email') }}" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="no_wa" :value="__('No. Whatsapp')" />
                        <div class="mt-1 flex">
                            <span
                                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg">
                                +62
                            </span>
                            <x-text-input id="no_wa" type="text" name="no_wa"
                                class="rounded-l-none block w-full" placeholder="81234567890" maxlength="15"
                                value="{{ old('no_wa') }}" />
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Masukkan nomor tanpa awalan 0, contoh: 81234567890</p>
                        <x-input-error :messages="$errors->get('no_wa')" class="mt-2" />
                    </div>

                    <div class="w-full px-2.5">
                        <div class="flex items-center gap-3 mt-6">
                            <x-primary-button>Tambah</x-primary-button>
                            <x-cancel-button url="{{ route('super-admin.patient.index') }}">Cancel</x-cancel-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('script')
    <script src="{{ asset('js/init-date-picker.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize your datepickers for various form inputs

            // Basic usage with default options
            initSafariCompatibleDatepicker('tgl_lahir', {}, 'Masukkan tanggal lahir');

            // Example with custom options (you can uncomment and modify these as needed)
            // initSafariCompatibleDatepicker('tgl_periksa', {
            //     minDate: "2020-01-01",
            //     maxDate: "today"
            // }, 'Masukkan tanggal periksa');

            // Example for another field with different placeholder
            // initSafariCompatibleDatepicker('tgl_kunjungan', {}, 'Masukkan tanggal kunjungan');
        });
    </script>
@endsection
