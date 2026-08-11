@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('super-admin.kurva.setting.table.update', [$namaTabel, $data->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <!-- Jenis Kelamin -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" required />
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="w-full px-4 py-3 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg appearance-none h-11 bg-none shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10"
                                :class="isOptionSelected && 'text-gray-500'" @change="isOptionSelected = true">
                                <option value="" class="text-gray-500"
                                    {{ old('jenis_kelamin', $data->jenis_kelamin) ? '' : 'selected' }}>
                                    Pilih Jenis Kelamin
                                </option>
                                <option value="L" class="text-gray-500"
                                    {{ old('jenis_kelamin', $data->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" class="text-gray-500"
                                    {{ old('jenis_kelamin', $data->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan
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
                        <x-input-error :messages="$errors->get('jenis_kelamin')" />
                    </div>

                    <!-- Kolom Utama (day/length/month) -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label :for="$column" :value="__(ucfirst($column))" required />
                        <x-text-input :id="$column" name="{{ $column }}" type="number" step="any" required
                            :value="old($column, $data->$column)" />
                        <x-input-error :messages="$errors->get($column)" />
                    </div>

                    <!-- L -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="l" :value="__('L')" required />
                        <x-text-input id="l" name="l" type="number" step="any" required
                            :value="old('l', $data->l)" />
                        <x-input-error :messages="$errors->get('l')" />
                    </div>

                    <!-- M -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="m" :value="__('M')" required />
                        <x-text-input id="m" name="m" type="number" step="any" required
                            :value="old('m', $data->m)" />
                        <x-input-error :messages="$errors->get('m')" />
                    </div>

                    <!-- S -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="s" :value="__('S')" required />
                        <x-text-input id="s" name="s" type="number" step="any" required
                            :value="old('s', $data->s)" />
                        <x-input-error :messages="$errors->get('s')" />
                    </div>

                    <!-- SD4Neg -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sd4neg" :value="__('SD4Neg')" required />
                        <x-text-input id="sd4neg" name="sd4neg" type="number" step="any" required
                            :value="old('sd4neg', $data->sd4neg)" />
                        <x-input-error :messages="$errors->get('sd4neg')" />
                    </div>

                    <!-- SD3Neg -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sd3neg" :value="__('SD3Neg')" required />
                        <x-text-input id="sd3neg" name="sd3neg" type="number" step="any" required
                            :value="old('sd3neg', $data->sd3neg)" />
                        <x-input-error :messages="$errors->get('sd3neg')" />
                    </div>

                    <!-- SD2Neg -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sd2neg" :value="__('SD2Neg')" required />
                        <x-text-input id="sd2neg" name="sd2neg" type="number" step="any" required
                            :value="old('sd2neg', $data->sd2neg)" />
                        <x-input-error :messages="$errors->get('sd2neg')" />
                    </div>

                    <!-- SD1Neg -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sd1neg" :value="__('SD1Neg')" required />
                        <x-text-input id="sd1neg" name="sd1neg" type="number" step="any" required
                            :value="old('sd1neg', $data->sd1neg)" />
                        <x-input-error :messages="$errors->get('sd1neg')" />
                    </div>

                    <!-- SD0 -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sd0" :value="__('SD0')" required />
                        <x-text-input id="sd0" name="sd0" type="number" step="any" required
                            :value="old('sd0', $data->sd0)" />
                        <x-input-error :messages="$errors->get('sd0')" />
                    </div>

                    <!-- SD1 -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sd1" :value="__('SD1')" required />
                        <x-text-input id="sd1" name="sd1" type="number" step="any" required
                            :value="old('sd1', $data->sd1)" />
                        <x-input-error :messages="$errors->get('sd1')" />
                    </div>

                    <!-- SD2 -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sd2" :value="__('SD2')" required />
                        <x-text-input id="sd2" name="sd2" type="number" step="any" required
                            :value="old('sd2', $data->sd2)" />
                        <x-input-error :messages="$errors->get('sd2')" />
                    </div>

                    <!-- SD3 -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sd3" :value="__('SD3')" required />
                        <x-text-input id="sd3" name="sd3" type="number" step="any" required
                            :value="old('sd3', $data->sd3)" />
                        <x-input-error :messages="$errors->get('sd3')" />
                    </div>

                    <!-- SD4 -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="sd4" :value="__('SD4')" required />
                        <x-text-input id="sd4" name="sd4" type="number" step="any" required
                            :value="old('sd4', $data->sd4)" />
                        <x-input-error :messages="$errors->get('sd4')" />
                    </div>

                    @if ($namaTabel === 'table8')
                        <!-- Stdev -->
                        <div class="w-full px-2.5 xl:w-1/2">
                            <x-input-label for="stdev" :value="__('Stdev')" required />
                            <x-text-input id="stdev" name="stdev" type="number" step="any" required
                                :value="old('stdev', $data->stdev)" />
                            <x-input-error :messages="$errors->get('stdev')" />
                        </div>

                        <!-- SD5Neg -->
                        <div class="w-full px-2.5 xl:w-1/2">
                            <x-input-label for="sd5neg" :value="__('SD5Neg')" required />
                            <x-text-input id="sd5neg" name="sd5neg" type="number" step="any" required
                                :value="old('sd5neg', $data->sd5neg)" />
                            <x-input-error :messages="$errors->get('sd5neg')" />
                        </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex space-x-2 px-2.5">
                        <x-primary-button>Simpan</x-primary-button>
                        <x-cancel-button
                            url="{{ route('super-admin.kurva.setting.show', $namaTabel) }}">Cancel</x-cancel-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
