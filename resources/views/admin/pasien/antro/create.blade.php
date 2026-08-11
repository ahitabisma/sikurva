@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('patient.antro.store', ['patientId' => $patient->id]) }}" method="POST"
                id="tambah-antro-form" x-data="{
                    isChecked: false,
                    tglPeriksa: '{{ old('tgl_periksa', now()->toDateString()) }}',
                    tglLahir: '{{ $patient->tgl_lahir }}',
                    calculateAgeInMonths() {
                        if (!this.tglPeriksa || !this.tglLahir) return 0;

                        const periksa = window.moment(this.tglPeriksa);
                        const lahir = window.moment(this.tglLahir);

                        // Hitung selisih dalam bulan
                        let months = periksa.diff(lahir, 'months');

                        // Tambahkan bulan ke tanggal lahir untuk cek apakah bulan terakhir sudah penuh
                        const adjustedLahir = lahir.clone().add(months, 'months');

                        // Jika tanggal pemeriksaan belum melewati tanggal lahir di bulan berikutnya, kurangi 1
                        if (periksa.isBefore(adjustedLahir, 'day')) {
                            months -= 1;
                        }

                        // Pastikan tidak negatif
                        return Math.max(months, 0);
                    },
                    calculateAgeInDays() {
                        if (!this.tglPeriksa || !this.tglLahir) return 0;

                        const periksa = window.moment(this.tglPeriksa);
                        const lahir = window.moment(this.tglLahir);

                        // Hitung selisih dalam hari
                        const days = periksa.diff(lahir, 'days');

                        // Pastikan tidak negatif
                        return Math.max(days, 0);
                    },
                    calculateAgeInMonthsAndDays() {
                        if (!this.tglPeriksa || !this.tglLahir) return { months: 0, days: 0 };

                        const periksa = window.moment(this.tglPeriksa);
                        const lahir = window.moment(this.tglLahir);

                        // Hitung selisih dalam bulan
                        let months = 0;
                        let tempDate = lahir.clone();

                        // Tambah bulan hingga mendekati atau sama dengan tanggal periksa
                        while (tempDate.add(1, 'months').isSameOrBefore(periksa, 'day')) {
                            months++;
                            tempDate = lahir.clone().add(months, 'months');
                        }

                        // Hitung sisa hari
                        const lastFullMonth = lahir.clone().add(months, 'months');
                        const days = periksa.diff(lastFullMonth, 'days');

                        return {
                            months: Math.max(months, 0),
                            days: Math.max(days, 0)
                        };
                    },
                    isAgeValid() {
                        return this.calculateAgeInMonths() <= 228 && this.calculateAgeInMonths() >= 0;
                    },

                    tambahAntroPoint: {{ $pointSettings->where('name', 'TAMBAH-ANTRO')->value('points') ?? 0 }}
                }">
                @csrf
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <div class="w-full px-2.5">
                        <h4 class="pb-4 text-base font-medium text-gray-800 border-b border-gray-200">
                            Tambah Data Antropometri {{ Auth::user()->is_nakes ? 'Pasien' : 'Anak' }} {{ $patient->nama }}
                        </h4>
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="tgl_periksa" :value="__('Tanggal Periksa')" required />
                        <x-date-input id="tgl_periksa" type="date" name="tgl_periksa" class="mt-1 block w-full"
                            x-model="tglPeriksa" x-bind:value="isAgeValid() ? tglPeriksa : ''" required />
                        <template x-if="!isAgeValid()">
                            <p class="mt-1 text-sm text-red-600">
                                Usia tidak valid (harus antara 0-228 bulan)
                            </p>
                        </template>
                        <x-input-error :messages="$errors->get('tgl_periksa')" class="mt-1" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="usia_bulan" :value="__('Usia (Bulan)')" required />
                        <x-text-input type="text"
                            x-bind:value="isAgeValid() ? calculateAgeInMonthsAndDays().months + ' bulan ' +
                                calculateAgeInMonthsAndDays().days + ' hari' : ''"
                            disabled class="disabled:bg-gray-100" />
                        <input type="hidden" name="usia_bulan" x-bind:value="isAgeValid() ? calculateAgeInMonths() : ''">
                        <p class="mt-1 text-xs text-gray-500">Maksimal usia 228 bulan</p>
                        <x-input-error :messages="$errors->get('usia_bulan')" class="mt-1" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="berat_badan" :value="__('Berat Badan (kg)')" />
                        <x-text-input id="berat_badan" type="number" name="berat_badan" class="mt-1 block w-full"
                            placeholder="Masukkan Berat Badan (kg)" step="0.1" value="{{ old('berat_badan') }}" />
                        <x-input-error :messages="$errors->get('berat_badan')" class="mt-2" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="tinggi_badan" :value="__('Tinggi Badan (cm)')" />
                        <x-text-input id="tinggi_badan" type="number" name="tinggi_badan" class="mt-1 block w-full"
                            placeholder="Masukkan Tinggi Badan (cm)" step="0.1" value="{{ old('tinggi_badan') }}" />
                        <x-input-error :messages="$errors->get('tinggi_badan')" class="mt-2" />
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="lingkar_kepala" :value="__('Lingkar Kepala (cm)')" />
                        <x-text-input id="lingkar_kepala" type="number" name="lingkar_kepala" class="mt-1 block w-full"
                            placeholder="Masukkan Lingkar Kepala" step="0.1" min="0" max="56"
                            value="{{ old('lingkar_kepala') }}" x-bind:disabled="calculateAgeInMonths() > 60"
                            class="disabled:bg-gray-100" />
                        <p class="mt-1 text-xs text-gray-500"
                            x-text="calculateAgeInMonths() > 60 ? 'Tidak dinilai untuk usia > 60 bulan' : 'Maksimum 56.0 cm'">
                        </p>
                        <x-input-error :messages="$errors->get('lingkar_kepala')" class="mt-2" />
                    </div>

                    @if (Auth::user()->is_nakes)
                        <div class="w-full px-2.5">
                            <x-input-label for="notes" :value="__('Catatan')" />
                            <x-textarea id="notes" name="notes" rows="3"
                                placeholder="Masukkan catatan tambahan (opsional)">{{ old('notes') }}</x-textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    @endif

                    {{-- <div x-data="{ notes: [{ content: '{{ old('notes.0.content', '') }}' }] }" class="w-full px-2.5">
                        <x-input-label for="notes" :value="__('Catatan')" />

                        <!-- Loop untuk Input Catatan -->
                        <template x-for="(note, index) in notes" x-bind:key="index">
                            <div class="flex flex-col md:flex-row md:items-center gap-2 mt-2">
                                <x-textarea x-bind:id="'notes-' + index" name="notes[][content]" rows="2"
                                    placeholder="Masukkan catatan tambahan (opsional)" x-model="note.content"
                                    class="flex-1"></x-textarea>

                                <!-- Tombol Hapus -->
                                <button type="button"
                                    class="flex-shrink-0 flex items-center justify-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-red-500 hover:bg-red-600"
                                    @click="notes.length > 1 ? notes.splice(index, 1) : note.content = ''">
                                    Hapus
                                </button>
                            </div>
                        </template>

                        <!-- Tombol Tambah -->
                        <button type="button"
                            class="mt-3 flex items-center justify-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-green-500 hover:bg-green-600"
                            @click="notes.push({ content: '' })">
                            Tambah Catatan
                        </button>

                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div> --}}

                    <div class="w-full px-2.5">
                        <div class="flex items-center gap-3 mt-6">
                            <x-primary-button type="button"
                                @click="openConfirmationModal('Tambah Antro', tambahAntroPoint, (skipConfirmation) => {
                                    // Add the skipConfirmation as a hidden field if it was checked
                                        if (skipConfirmation) {
                                            const skipInput = document.createElement('input');
                                            skipInput.type = 'hidden';
                                            skipInput.name = 'skip_confirmation';
                                            skipInput.value = '1';
                                            document.getElementById('tambah-antro-form').appendChild(skipInput);
                                        }
                                        // Then submit the form
                                        document.getElementById('tambah-antro-form').submit();
                                })">Tambah</x-primary-button>
                            <x-cancel-button
                                url="{{ route('patient.preview', ['id' => $patient->id]) }}">Cancel</x-cancel-button>
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
            initSafariCompatibleDatepicker('tgl_periksa', {}, 'Masukkan tanggal periksa');

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
