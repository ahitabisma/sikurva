@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('super-admin.langganan.paket.store') }}" method="POST" x-data="{ statusSelected: false, durationTypeSelected: false }">
                @csrf
                <div class="-mx-2.5 flex flex-wrap gap-y-5">

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="paket" :value="__('Nama Paket')" required />
                        <x-text-input id="paket" type="text" name="name" required
                            placeholder="Masukkan Nama Paket" />
                        <x-input-error :messages="$errors->get('name')" />
                    </div>
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="point" :value="__('Jumlah Point')" required />
                        <x-text-input id="point" type="number" min="0" name="point" required
                            placeholder="Masukkan Jumlah Point" />
                        <x-input-error :messages="$errors->get('point')" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="durasi" :value="__('Durasi')" required />
                        <x-text-input id="durasi" type="number" min="1" name="duration" required
                            placeholder="Masukkan Durasi" />
                        <x-input-error :messages="$errors->get('duration')" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="status" :value="__('Status Paket')" required />
                        <div class="relative z-20 bg-transparent">
                            <select required
                                class="w-full px-4 py-3 text-sm text-gray-500 bg-transparent border border-gray-300 rounded-lg appearance-none h-11 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10"
                                :class="durationTypeSelected && 'text-gray-500'" @change="durationTypeSelected = true"
                                name="duration_type">
                                <option value="bulan" class="text-gray-500">
                                    Bulan
                                </option>
                                <option value="tahun" class="text-gray-500">
                                    Tahun
                                </option>
                            </select>
                            <span class="absolute z-30 text-gray-500 -translate-y-1/2 right-4 top-1/2">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('duration_type')" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="harga" :value="__('Harga')" required />
                        <x-text-input id="harga" type="number" min="1" name="price" required
                            placeholder="Masukkan Harga (Rp)" />
                        <x-input-error :messages="$errors->get('price')" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="status" :value="__('Status Paket')" required />
                        <x-toggle-switch name="status" labelActive="Aktif" labelInactive="Tidak Aktif"
                            :active="old('status', true)" />
                        <x-input-error :messages="$errors->get('status')" />
                    </div>

                    <div x-data="{ description: [''] }" class="w-full px-2.5 xl:w-3/4">
                        <x-input-label for="deskripsi" :value="__('Deskripsi')" required />

                        <!-- Loop untuk Input Deskripsi -->
                        <template x-for="(desc, index) in description" :key="index">
                            <div class="flex items-center gap-2 mt-2">
                                <x-text-input type="text" name="description[]" x-model="description[index]"
                                    placeholder="Masukkan Deskripsi" required class="flex-1" />

                                <!-- Tombol Hapus -->
                                <button type="button"
                                    class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-red-500 hover:bg-red-600"
                                    @click="description.length > 1 ? description.splice(index, 1) : null">
                                    Hapus
                                </button>
                            </div>
                        </template>

                        <!-- Tombol Tambah -->
                        <button type="button"
                            class="mt-3 flex items-center justify-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-green-500 hover:bg-green-600"
                            @click="description.push('')">
                            Tambah Deskripsi
                        </button>

                        <x-input-error :messages="$errors->get('description')" />
                    </div>

                    <div class="w-full px-2.5">
                        <div class="flex items-center gap-3">
                            <button type="submit"
                                class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                                Simpan
                            </button>
                            <a href="{{ route('super-admin.langganan.paket.index') }}"
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
