@extends('layouts.tailadmin')

@section('content')
    <div x-data="{ isNakes: {{ old('is_nakes', 0) }}, isActive: {{ old('status', 1) }} }" class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('super-admin.users.store') }}" method="POST">
                @csrf
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <!-- Is Nakes Radio Button -->
                    <div class="w-full mx-2.5" x-data="{ isNakes: '{{ old('is_nakes') }}' }">
                        <x-input-label :value="'Apakah Anda Nakes?'" required />
                        <div class="mt-2">
                            <div class="flex items-center space-x-6">
                                <label
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" name="is_nakes" value="1" x-model="isNakes"
                                            class="sr-only" required {{ old('is_nakes') == '1' ? 'checked' : '' }} />
                                        <div class="hover:border-blue-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]"
                                            :class="isNakes == '1' ? 'border-blue-500 bg-blue-500' :
                                                'bg-transparent border-gray-300'">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="isNakes == '1' ? 'bg-white' : 'bg-white'"></span>
                                        </div>
                                    </div>
                                    {{ 'Ya' }}
                                </label>

                                <label
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" name="is_nakes" value="0" x-model="isNakes"
                                            class="sr-only" required {{ old('is_nakes', '0') == '0' ? 'checked' : '' }} />
                                        <div class="hover:border-blue-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]"
                                            :class="isNakes == '0' ? 'border-blue-500 bg-blue-500' :
                                                'bg-transparent border-gray-300'">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="isNakes == '0' ? 'bg-white' : 'bg-white'"></span>
                                        </div>
                                    </div>
                                    {{ 'Tidak' }}
                                </label>
                            </div>
                        </div>

                        <!-- filepath: d:\laragon\www\closing\ekurva\resources\views\super-admin\users\create.blade.php -->
                        <!-- Institution Name (shown if is_nakes is '1') -->
                        <div x-show="isNakes == '1' || isNakes == 1 || isNakes == true" class="mt-4 w-full xl:w-1/2">
                            <x-input-label for="instansi" :value="'Nama Instansi'" required />
                            <x-text-input id="instansi" class="block mt-1 w-full" type="text" name="instansi"
                                :value="old('instansi')" x-bind:required="isNakes == '1'" autocomplete="instansi"
                                placeholder="Masukkan Nama Instansi" />
                            <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                            {{-- <x-input-label for="instansi" :value="'Nama Instansi'" required />
                            <div x-data="{
                                open: false,
                                search: '',
                                selectedInstansi: '{{ old('instansi') }}',
                                customInstansi: '{{ old('custom_instansi') }}',
                                showCustomInput: {{ old('instansi') == 'lain-lain' ? 'true' : 'false' }},
                                isLoading: false,
                                results: [],
                                select(id, name) {
                                    this.selectedInstansi = id;
                                    this.search = name;
                                    this.showCustomInput = id === 'lain-lain';
                                    this.open = false;
                                },
                                fetchResults() {
                                    this.isLoading = true;
                                    fetch(`/kliniks/search?q=${encodeURIComponent(this.search)}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            this.results = data;
                                            this.isLoading = false;
                                        })
                                        .catch(error => {
                                            console.error('Error fetching data:', error);
                                            this.isLoading = false;
                                        });
                                }
                            }" @click.away="open = false" class="relative">
                                <!-- Hidden input field for the selected instansi ID -->
                                <input type="hidden" name="instansi" x-bind:value="selectedInstansi">

                                <!-- Search input -->
                                <div class="relative">
                                    <input type="text" x-model="search" @focus="open = true" @input="fetchResults()"
                                        placeholder="Cari instansi..."
                                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                        x-bind:required="isNakes == '1'">
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700">
                                        <svg x-show="!isLoading" class="stroke-current" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <svg x-show="isLoading" class="animate-spin h-5 w-5"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </span>
                                </div>

                                <!-- Dropdown with results -->
                                <div x-show="open"
                                    class="absolute z-30 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                    <div class="py-1">
                                        <template x-for="result in results" :key="result.id">
                                            <a @click.prevent="select(result.id, result.name)"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                                                x-text="result.name"></a>
                                        </template>

                                        <!-- Option for "Lain-lain" -->
                                        <a @click.prevent="select('lain-lain', 'Lain-lain')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">Lain-lain</a>

                                        <!-- No results message -->
                                        <div x-show="results.length === 0 && !isLoading && search !== ''"
                                            class="px-4 py-2 text-sm text-gray-500">
                                            Tidak ditemukan, silakan pilih "Lain-lain"
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <p class="text-theme-xs text-gray-500 mt-1">
                                Untuk mendaftarkan Klinik/ RS / Puskesmas, silahkan request email ke <span
                                    class="font-bold">cs.ptekai@gmail.com</span>
                            </p> --}}
                        </div>

                        <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                        <x-input-error :messages="$errors->get('is_nakes')" class="mt-2" />
                    </div>

                    <!-- Input Nama -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="name" :value="'Nama'" required />
                        <x-text-input id="name" type="text" name="name" required placeholder="Masukkan Nama"
                            :value="old('name')" />
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <!-- Input Email -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="email" :value="'Email'" required />
                        <x-text-input id="email" type="email" name="email" required placeholder="Masukkan Email"
                            :value="old('email')" />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <!-- Input No. Whatsapp -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="no_wa" :value="'No. Whatsapp'" required />
                        <x-text-input id="no_wa" type="text" name="phone" required
                            placeholder="Masukkan No. Whatsapp" :value="old('phone')" />
                        <x-input-error :messages="$errors->get('phone')" />
                    </div>

                    <!-- Input Alamat -->
                    <div class="w-full px-2.5 xl:w-full">
                        <x-input-label for="alamat" :value="'Alamat'" required />
                        <x-textarea placeholder="Masukkan Alamat" name="address" required>{{ old('address') }}</x-textarea>
                        <x-input-error :messages="$errors->get('address')" />
                    </div>

                    <!-- Input Password -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="password" :value="'Password'" required />
                        <x-text-input id="password" type="password" name="password" required
                            placeholder="Masukkan Password" />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <!-- Input Confirm Password -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="password_confirmation" :value="'Confirm Password'" required />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                            placeholder="Masukkan Password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" />
                    </div>

                    <!-- Input Referral -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="referral" :value="'Referral'" />
                        <x-text-input id="referral" type="text" name="referral_code"
                            placeholder="Masukkan Kode Referral (Opsional)" :value="old('referral_code')" />
                        <x-input-error :messages="$errors->get('referral_code')" />
                    </div>

                    <!-- Input Status -->
                    <!-- Input Status -->
                    {{-- <div class="w-full mx-2.5" x-data="{ status: {{ old('status', '0') }} }">
                        <x-input-label :value="'Status?'" required />
                        <div class="mt-2">
                            <div class="flex items-center space-x-6">
                                <label
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" name="status" value="1" x-model="status"
                                            class="sr-only" required />
                                        <div class="hover:border-blue-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]"
                                            :class="status == '1' ? 'border-blue-500 bg-blue-500' :
                                                'bg-transparent border-gray-300'">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="status == '1' ? 'bg-white' : 'bg-white'"></span>
                                        </div>
                                    </div>
                                    {{ 'Ya' }}
                                </label>

                                <label
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" name="status" value="0" x-model="status"
                                            class="sr-only" required />
                                        <div class="hover:border-blue-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]"
                                            :class="status == '0' ? 'border-blue-500 bg-blue-500' :
                                                'bg-transparent border-gray-300'">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="status == '0' ? 'bg-white' : 'bg-white'"></span>
                                        </div>
                                    </div>
                                    {{ 'Tidak' }}
                                </label>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div> --}}

                    <!-- Submit Button -->
                    <div class="w-full px-2.5">
                        <div class="flex items-center gap-3 mt-1">
                            <button type="submit"
                                class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                                Simpan
                            </button>
                            <a href="{{ route('super-admin.users.index') }}"
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
