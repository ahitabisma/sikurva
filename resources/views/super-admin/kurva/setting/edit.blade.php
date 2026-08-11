@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('super-admin.kurva.setting.update', $setting->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <!-- Nama Tabel -->
                    {{-- <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="nama_tabel" :value="__('Nama Tabel')" required />
                        <x-text-input id="nama_tabel" placeholder="Masukkan Nama Tabel" name="nama_tabel" required
                            :value="old('nama_tabel', $setting->nama_tabel)" />
                        <x-input-error :messages="$errors->get('nama_tabel')" />
                    </div> --}}

                    <!-- Nama -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="nama" :value="__('Nama')" required />
                        <x-text-input id="nama" placeholder="Masukkan Nama" name="nama" required
                            :value="old('nama', $setting->nama)" />
                        <x-input-error :messages="$errors->get('nama')" />
                    </div>

                    <!-- Judul -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="judul" :value="__('Judul')" required />
                        <x-text-input id="judul" placeholder="Masukkan Judul" name="judul" required
                            :value="old('judul', $setting->judul)" />
                        <x-input-error :messages="$errors->get('judul')" />
                    </div>

                    <!-- Ket Y -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="ket_y" :value="__('Keterangan Y')" required />
                        <x-text-input id="ket_y" placeholder="Masukkan Keterangan Y" name="ket_y" required
                            :value="old('ket_y', $setting->ket_y)" />
                        <x-input-error :messages="$errors->get('ket_y')" />
                    </div>

                    <!-- Y Min -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="y_min" :value="__('Y Min')" required />
                        <x-text-input id="y_min" type="number" step="0.01" placeholder="Masukkan Y Min"
                            name="y_min" required :value="old('y_min', $setting->y_min)" />
                        <x-input-error :messages="$errors->get('y_min')" />
                    </div>

                    <!-- Y Max -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="y_max" :value="__('Y Max')" required />
                        <x-text-input id="y_max" type="number" step="0.01" placeholder="Masukkan Y Max"
                            name="y_max" required :value="old('y_max', $setting->y_max)" />
                        <x-input-error :messages="$errors->get('y_max')" />
                    </div>

                    <!-- Y Mayor -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="y_mayor" :value="__('Y Mayor')" required />
                        <x-text-input id="y_mayor" type="number" step="0.01" placeholder="Masukkan Y Mayor"
                            name="y_mayor" required :value="old('y_mayor', $setting->y_mayor)" />
                        <x-input-error :messages="$errors->get('y_mayor')" />
                    </div>

                    <!-- Y Minor -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="y_minor" :value="__('Y Minor')" required />
                        <x-text-input id="y_minor" type="number" step="0.01" placeholder="Masukkan Y Minor"
                            name="y_minor" required :value="old('y_minor', $setting->y_minor)" />
                        <x-input-error :messages="$errors->get('y_minor')" />
                    </div>

                    <!-- Y Unit -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="y_unit" :value="__('Y Unit')" required />
                        <x-text-input id="y_unit" placeholder="Masukkan Y Unit" name="y_unit" required
                            :value="old('y_unit', $setting->y_unit)" />
                        <x-input-error :messages="$errors->get('y_unit')" />
                    </div>

                    <!-- Ket X -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="ket_x" :value="__('Keterangan X')" required />
                        <x-text-input id="ket_x" placeholder="Masukkan Keterangan X" name="ket_x" required
                            :value="old('ket_x', $setting->ket_x)" />
                        <x-input-error :messages="$errors->get('ket_x')" />
                    </div>

                    <!-- X Min -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="x_min" :value="__('X Min')" required />
                        <x-text-input id="x_min" type="number" step="0.01" placeholder="Masukkan X Min"
                            name="x_min" required :value="old('x_min', $setting->x_min)" />
                        <x-input-error :messages="$errors->get('x_min')" />
                    </div>

                    <!-- X Max -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="x_max" :value="__('X Max')" required />
                        <x-text-input id="x_max" type="number" step="0.01" placeholder="Masukkan X Max"
                            name="x_max" required :value="old('x_max', $setting->x_max)" />
                        <x-input-error :messages="$errors->get('x_max')" />
                    </div>

                    <!-- X Mayor -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="x_mayor" :value="__('X Mayor')" required />
                        <x-text-input id="x_mayor" type="number" step="0.01" placeholder="Masukkan X Mayor"
                            name="x_mayor" required :value="old('x_mayor', $setting->x_mayor)" />
                        <x-input-error :messages="$errors->get('x_mayor')" />
                    </div>

                    <!-- X Minor -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="x_minor" :value="__('X Minor')" required />
                        <x-text-input id="x_minor" type="number" step="0.01" placeholder="Masukkan X Minor"
                            name="x_minor" required :value="old('x_minor', $setting->x_minor)" />
                        <x-input-error :messages="$errors->get('x_minor')" />
                    </div>

                    <!-- X Unit -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="x_unit" :value="__('X Unit')" required />
                        <x-text-input id="x_unit" placeholder="Masukkan X Unit" name="x_unit" required
                            :value="old('x_unit', $setting->x_unit)" />
                        <x-input-error :messages="$errors->get('x_unit')" />
                    </div>

                    <!-- Sumbu Y -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sumbu_y" :value="__('Sumbu Y')" required />
                        <x-text-input id="sumbu_y" placeholder="Masukkan Sumbu Y" name="sumbu_y" required
                            :value="old('sumbu_y', $setting->sumbu_y)" />
                        <x-input-error :messages="$errors->get('sumbu_y')" />
                    </div>

                    <!-- Sumbu X -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sumbu_x" :value="__('Sumbu X')" required />
                        <x-text-input id="sumbu_x" placeholder="Masukkan Sumbu X" name="sumbu_x" required
                            :value="old('sumbu_x', $setting->sumbu_x)" />
                        <x-input-error :messages="$errors->get('sumbu_x')" />
                    </div>

                    <!-- Buttons -->
                    <div class="w-full px-2.5">
                        <div class="flex items-center gap-3 mt-1">
                            <button type="submit"
                                class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                                Simpan
                            </button>
                            <a href="{{ route('super-admin.kurva.setting.index') }}"
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
