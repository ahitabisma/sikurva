@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            @php
                $nama =
                    is_array($setting) || $setting instanceof \Illuminate\Support\Collection
                        ? $setting->first()->name
                        : $setting->name;

                $showDuration = isset($showDuration)
                    ? $showDuration
                    : in_array(strtoupper($nama), ['REFERRER', 'REFERRAL', 'PENGGUNA-BARU']);
            @endphp

            <form action="{{ route('super-admin.langganan.setting.update', $nama) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <div class="w-full px-2.5">
                        <h4 class="pb-4 text-base font-medium text-gray-800 border-b border-gray-200">
                            {{ $nama }}
                        </h4>
                    </div>

                    @if (is_iterable($setting))
                        @foreach ($setting as $s)
                            <div class="w-full px-2.5 xl:w-full">
                                <x-input-label for="point_{{ $s->user_type ?? 'general' }}" :value="'Point ' . ($s->user_type ?? 'General')" required />
                                <div x-data="{ isOptionSelected: true }" class="relative z-20 bg-transparent">
                                    <x-text-input id="point_{{ $s->user_type ?? 'general' }}" type="number" min="0"
                                        name="point[{{ $s->user_type ?? 'general' }}]" required placeholder="Masukkan Point"
                                        :value="$s->points"
                                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
                                </div>
                                <x-input-error :messages="$errors->get('point.' . ($s->user_type ?? 'general'))" />
                                <input type="hidden" name="id[{{ $s->user_type ?? 'general' }}]"
                                    value="{{ $s->id }}">
                                <input type="hidden" name="user_type[{{ $s->user_type ?? 'general' }}]"
                                    value="{{ $s->user_type }}">
                            </div>

                            @if ($showDuration)
                                <div class="flex flex-wrap w-full">
                                    <div class="w-1/2 px-2.5">
                                        <x-input-label for="duration_{{ $s->user_type ?? 'general' }}" :value="'Durasi'"
                                            required />
                                        <div class="relative z-20 bg-transparent">
                                            <x-text-input id="duration_{{ $s->user_type ?? 'general' }}" type="number"
                                                min="1" name="duration[{{ $s->user_type ?? 'general' }}]" required
                                                placeholder="Durasi" :value="$s->duration"
                                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
                                        </div>
                                        <x-input-error :messages="$errors->get('duration.' . ($s->user_type ?? 'general'))" />
                                    </div>
                                    <div class="w-1/2 px-2.5">
                                        <x-input-label for="duration_type_{{ $s->user_type ?? 'general' }}"
                                            :value="'Jenis Durasi'" required />
                                        <div x-data="{ isOptionSelected: true }" class="relative z-20 bg-transparent">
                                            <select id="duration_type_{{ $s->user_type ?? 'general' }}"
                                                name="duration_type[{{ $s->user_type ?? 'general' }}]"
                                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                                :class="isOptionSelected && 'text-gray-800'"
                                                @change="isOptionSelected = true" required>
                                                <option value="hari" {{ $s->duration_type == 'hari' ? 'selected' : '' }}>
                                                    Hari</option>
                                                <option value="bulan"
                                                    {{ $s->duration_type == 'bulan' ? 'selected' : '' }}>Bulan</option>
                                                <option value="tahun"
                                                    {{ $s->duration_type == 'tahun' ? 'selected' : '' }}>Tahun</option>
                                            </select>
                                            <span
                                                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700">
                                                <svg class="stroke-current" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    </path>
                                                </svg>
                                            </span>
                                        </div>
                                        <x-input-error :messages="$errors->get('duration_type.' . ($s->user_type ?? 'general'))" />
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="w-full px-2.5 xl:w-full">
                            <x-input-label for="point" :value="'Point'" required />
                            <div x-data="{ isOptionSelected: true }" class="relative z-20 bg-transparent">
                                <x-text-input id="point" type="number" min="0" name="point" required
                                    placeholder="Masukkan Point" :value="$setting->points"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
                            </div>
                            <x-input-error :messages="$errors->get('point')" />
                            <input type="hidden" name="id" value="{{ $setting->id }}">
                            <input type="hidden" name="user_type" value="{{ $setting->user_type }}">
                        </div>

                        @if ($showDuration)
                            <div class="flex flex-wrap w-full">
                                <div class="w-1/2 px-2.5">
                                    <x-input-label for="duration" :value="'Durasi'" required />
                                    <div class="relative z-20 bg-transparent">
                                        <x-text-input id="duration" type="number" min="1" name="duration" required
                                            placeholder="Durasi" :value="$setting->duration"
                                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
                                    </div>
                                    <x-input-error :messages="$errors->get('duration')" />
                                </div>
                                <div class="w-1/2 px-2.5">
                                    <x-input-label for="duration_type" :value="'Jenis Durasi'" required />
                                    <div x-data="{ isOptionSelected: true }" class="relative z-20 bg-transparent">
                                        <select id="duration_type" name="duration_type"
                                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                            :class="isOptionSelected && 'text-gray-800'" @change="isOptionSelected = true"
                                            required>
                                            <option value="hari"
                                                {{ $setting->duration_type == 'hari' ? 'selected' : '' }}>Hari</option>
                                            <option value="bulan"
                                                {{ $setting->duration_type == 'bulan' ? 'selected' : '' }}>Bulan</option>
                                            <option value="tahun"
                                                {{ $setting->duration_type == 'tahun' ? 'selected' : '' }}>Tahun</option>
                                        </select>
                                        <span
                                            class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700">
                                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                            </svg>
                                        </span>
                                    </div>
                                    <x-input-error :messages="$errors->get('duration_type')" />
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="w-full px-2.5">
                        <div class="flex items-center gap-3 mt-1">
                            <button type="submit"
                                class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                                Simpan
                            </button>
                            <a href="{{ route('super-admin.langganan.setting.index') }}"
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
