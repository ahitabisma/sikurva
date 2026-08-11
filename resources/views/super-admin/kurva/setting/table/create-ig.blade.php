@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('super-admin.kurva.setting.table.store-ig', $namaTabel) }}" method="POST">
                @csrf
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <!-- Jenis Kelamin -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" required />
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="w-full px-4 py-3 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg appearance-none h-11 bg-none shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10"
                                :class="isOptionSelected && 'text-gray-500'" @change="isOptionSelected = true">
                                <option value="" class="text-gray-500" {{ old('jenis_kelamin') ? '' : 'selected' }}>
                                    Pilih Jenis Kelamin
                                </option>
                                <option value="L" class="text-gray-500"
                                    {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" class="text-gray-500"
                                    {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan
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
                            :value="old($column)" />
                        <x-input-error :messages="$errors->get($column)" />
                    </div>

                    <!-- Days -->
                    @if (in_array($namaTabel, ['table9', 'table10', 'table11']))
                        <div class="w-full px-2.5 xl:w-1/2">
                            <x-input-label for="days" :value="__('Days')" required />
                            <x-text-input id="days" name="days" type="number" step="any" required
                                :value="old('days')" />
                            <x-input-error :messages="$errors->get('days')" />
                        </div>
                    @endif

                    <!-- z3Neg -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="z3neg" :value="__('z3Neg')" required />
                        <x-text-input id="z3neg" name="z3neg" type="number" step="any" required
                            :value="old('z3neg')" />
                        <x-input-error :messages="$errors->get('z3neg')" />
                    </div>

                    <!-- z2Neg -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="z2neg" :value="__('z2Neg')" required />
                        <x-text-input id="z2neg" name="z2neg" type="number" step="any" required
                            :value="old('z2neg')" />
                        <x-input-error :messages="$errors->get('z2neg')" />
                    </div>

                    <!-- z1Neg -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="z1neg" :value="__('z1Neg')" required />
                        <x-text-input id="z1neg" name="z1neg" type="number" step="any" required
                            :value="old('z1neg')" />
                        <x-input-error :messages="$errors->get('z1neg')" />
                    </div>

                    <!-- z0 -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="z0" :value="__('z0')" required />
                        <x-text-input id="z0" name="z0" type="number" step="any" required
                            :value="old('z0')" />
                        <x-input-error :messages="$errors->get('z0')" />
                    </div>

                    <!-- z1 -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="z1" :value="__('z1')" required />
                        <x-text-input id="z1" name="z1" type="number" step="any" required
                            :value="old('z1')" />
                        <x-input-error :messages="$errors->get('z1')" />
                    </div>

                    <!-- z2 -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="z2" :value="__('z2')" required />
                        <x-text-input id="z2" name="z2" type="number" step="any" required
                            :value="old('z2')" />
                        <x-input-error :messages="$errors->get('z2')" />
                    </div>

                    <!-- z3 -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="z3" :value="__('z3')" required />
                        <x-text-input id="z3" name="z3" type="number" step="any" required
                            :value="old('z3')" />
                        <x-input-error :messages="$errors->get('z3')" />
                    </div>

                    <!-- Buttons -->
                    <div class="w-full flex space-x-2 px-2.5">
                        <x-primary-button>Simpan</x-primary-button>
                        <x-cancel-button
                            url="{{ route('super-admin.kurva.setting.show', $namaTabel) }}">Cancel</x-cancel-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
