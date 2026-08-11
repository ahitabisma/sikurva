<x-guest-layout>
    <div class="pb-10">
        <div class="mb-1 mt-5">
            <h1 class="mb-2 font-semibold text-gray-800 text-title-sm sm:text-title-md">
                Sign Up
            </h1>
            <p class="text-sm text-gray-500">
                Silahkan isi data berikut untuk mendaftar
            </p>
        </div>
        <div>
            <div class="relative py-3 sm:py-5">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    {{-- <span class="p-2 text-gray-400 bg-white sm:px-5 sm:py-2">Or</span> --}}
                </div>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="space-y-5">
                    <!-- Name -->
                    {{-- <div>
                        <x-input-label for="name" :value="__('Nama')" required />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan Nama" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div> --}}

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" required />

                        @php
                            $isEmail = request()->query('email') ? true : false;
                        @endphp

                        @if ($isEmail)
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email') ?? request()->query('email')" required autocomplete="username" placeholder="Masukkan Email"
                                readonly />
                        @else
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autocomplete="username" placeholder="Masukkan Email" />
                        @endif
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    {{-- <div class="mt-4">
                        <x-input-label for="phone" :value="__('No Whatsapp')" required />
                        <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone"
                            :value="old('phone')" required autocomplete="phone" placeholder="Masukkan Nomor Whatsapp" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        <p class="text-theme-xs text-gray-500 mt-1">
                            Gunakan format nomor whatsapp yang benar, contoh: 081234567890
                        </p>
                    </div> --}}

                    <!-- Address -->
                    {{-- <div class="mt-4">
                        <x-input-label for="address" :value="__('Alamat')" required />
                        <x-textarea id="address" class="block mt-1 w-full" name="address" required
                            autocomplete="address" placeholder="Masukkan Alamat">{{ old('address') }}</x-textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div> --}}

                    <!-- Is Nakes Radio Button -->
                    <div class="mt-4" x-data="{ isNakes: {{ old('is_nakes', '0') }} }">
                        <x-input-label :value="__('Apakah Anda Nakes?')" required />
                        <div class="mt-2">
                            <div class="flex items-center space-x-6">
                                <label
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" name="is_nakes" value="1" x-model="isNakes"
                                            class="sr-only" required />
                                        <div class="hover:border-blue-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]"
                                            :class="isNakes == '1' ? 'border-blue-500 bg-blue-500' :
                                                'bg-transparent border-gray-300'">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="isNakes == '1' ? 'bg-white' : 'bg-white'"></span>
                                        </div>
                                    </div>
                                    {{ __('Ya') }}
                                </label>

                                <label
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" name="is_nakes" value="0" x-model="isNakes"
                                            class="sr-only" required />
                                        <div class="hover:border-blue-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]"
                                            :class="isNakes == '0' ? 'border-blue-500 bg-blue-500' :
                                                'bg-transparent border-gray-300'">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="isNakes == '0' ? 'bg-white' : 'bg-white'"></span>
                                        </div>
                                    </div>
                                    {{ __('Tidak') }}
                                </label>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('is_nakes')" class="mt-2" />

                        <!-- Institution Name (shown if is_nakes is '1') -->
                        {{-- <div x-show="isNakes == '1' || isNakes == 1 || isNakes == true" class="mt-4 w-full xl:w-full">
                            <x-input-label for="instansi" :value="__('Nama Instansi')" required />
                            <x-text-input id="instansi" class="block mt-1 w-full" type="text" name="instansi"
                                :value="old('instansi')" x-bind:required="isNakes == '1'" autocomplete="instansi"
                                placeholder="Masukkan Nama Instansi" />
                            <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                        </div> --}}
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" required />
                        <div class="relative">
                            <x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password"
                                required autocomplete="current-password" placeholder="Masukkan Password" />
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 cursor-pointer">
                                <!-- Eye Icon (Password Hidden) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon show" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- Eye-Slash Icon (Password Visible) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon hidden" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" required />

                        <div class="relative">
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Ulangi Password Anda" />

                            <button type="button" id="togglePasswordConfirmation"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 cursor-pointer">
                                <!-- Eye Icon (Password Hidden) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon-confirmation show"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- Eye-Slash Icon (Password Visible) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon-confirmation hidden"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    {{-- Capctcha --}}
                    <div x-data="{ captchaSrc: '{{ captcha_src() }}' }" class="flex flex-col items-start space-y-4">
                        <!-- CAPTCHA Image and Refresh Button -->
                        <div class="flex items-center">
                            <!-- CAPTCHA Image -->
                            <img :src="captchaSrc" alt="captcha"
                                class="captcha-img w-36 h-16 border border-gray-300 rounded-md cursor-pointer hover:border-blue-500 transition-all"
                                @click="refreshCaptcha" />

                            <!-- Refresh Button -->
                            <button type="button"
                                class="ml-2 p-2 border border-gray-300 rounded-md bg-gray-100 hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                @click="refreshCaptcha">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <!-- CAPTCHA Input Field -->
                        <x-text-input id="captcha" class="block mt-1 w-full" type="text" name="captcha"
                            required autocomplete="captcha" placeholder="Masukkan CAPTCHA" />
                        <x-input-error :messages="$errors->get('captcha')" />
                    </div>

                    <div>
                        <x-input-label for="referral" :value="__('Referral')" />

                        @php
                            $isReferral = request()->query('referral') ? true : false;
                        @endphp

                        @if ($isReferral)
                            <x-text-input id="referral" class="block mt-1 w-full" type="text"
                                name="referral_code" :value="old('referral_code') ?? request()->query('referral')" autocomplete="referral"
                                placeholder="Masukkan Kode Referral (Opsional jika ada)" readonly />
                        @else
                            <x-text-input id="referral" class="block mt-1 w-full" type="text"
                                name="referral_code" :value="old('referral_code')" autocomplete="referral"
                                placeholder="Masukkan Kode Referral (Opsional jika ada)" />
                        @endif

                        <x-input-error :messages="$errors->get('referral_code')" class="mt-2" />
                    </div>

                    <!-- Terms Agreement Checkbox with Modal -->
                    {{-- <div x-data="{ checkboxToggle: false, isModalOpen: false, activeTab: 'sk' }">
                        <label for="terms"
                            class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none ">
                            <div class="relative">
                                <input type="checkbox" name="terms" id="terms" class="sr-only"
                                    @change="checkboxToggle = !checkboxToggle" required>
                                <div :class="checkboxToggle ? 'border-brand-500 bg-brand-500' :
                                    'bg-transparent border-gray-300 '"
                                    class="hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] border-brand-500 bg-brand-500">
                                    <span :class="checkboxToggle ? '' : 'opacity-0'" class="">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white"
                                                stroke-width="1.94437" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <span class="ml-2 text-sm text-gray-700">
                                Saya setuju dengan
                                <button type="button" @click="isModalOpen = true; activeTab = 'sk'"
                                    class="text-blue-500 hover:underline">
                                    Syarat dan Ketentuan
                                </button>
                                dan
                                <button type="button" @click="isModalOpen = true; activeTab = 'pp'"
                                    class="text-blue-500 hover:underline">
                                    Kebijakan Privasi
                                </button>
                                yang berlaku
                            </span>
                        </label>

                        <!-- Modal for Terms and Privacy Policy -->
                        <div x-show="isModalOpen"
                            class="fixed left-0 top-0 z-99999 flex h-screen w-full flex-col items-center justify-between overflow-y-auto overflow-x-hidden bg-white p-6 lg:p-10"
                            style="display: none;">
                            <!-- Close Button -->
                            <button @click="isModalOpen = false"
                                class="absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z"
                                        fill=""></path>
                                </svg>
                            </button>

                            <!-- Modal Content -->
                            <div class="w-full max-w-6xl">
                                <!-- Tabs -->
                                <div class="flex border-b border-gray-200 mb-4">
                                    <button @click="activeTab = 'sk'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'sk', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'sk' }"
                                        class="py-2 px-4 font-medium text-sm border-b-2 -mb-px">
                                        Syarat dan Ketentuan
                                    </button>
                                    <button @click="activeTab = 'pp'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'pp', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'pp' }"
                                        class="py-2 px-4 font-medium text-sm border-b-2 -mb-px">
                                        Kebijakan Privasi
                                    </button>
                                </div>

                                <!-- PDF Viewer -->
                                <div class="h-[70vh]">
                                    <!-- Syarat Ketentuan PDF -->
                                    <div x-show="activeTab === 'sk'" class="h-full">
                                        @if (isset($skFileUrl))
                                            <embed src="{{ $skFileUrl }}" type="application/pdf" width="100%"
                                                height="100%" />
                                        @else
                                            <div
                                                class="flex items-center justify-center h-full bg-gray-100 rounded-lg">
                                                <p class="text-gray-500">Dokumen Syarat dan Ketentuan belum
                                                    tersedia.
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Privacy Policy PDF -->
                                    <div x-show="activeTab === 'pp'" class="h-full">
                                        @if (isset($ppFileUrl))
                                            <embed src="{{ $ppFileUrl }}" type="application/pdf" width="100%"
                                                height="100%" />
                                        @else
                                            <div
                                                class="flex items-center justify-center h-full bg-gray-100 rounded-lg">
                                                <p class="text-gray-500">Dokumen Kebijakan Privasi belum tersedia.
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="mt-8 flex w-full items-center justify-end gap-3">
                                <button @click="isModalOpen = false" type="button"
                                    class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('terms')" class="mt-2" /> --}}

                    <!-- Button -->
                    <div class="space-y-4" x-data="{ isModalOpen: false, activeTab: 'sk' }">
                        <button
                            class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-blue-500 shadow-theme-xs hover:bg-blue-600">
                            Daftar
                        </button>

                        <div class="text-xs font-normal text-gray-700 text-justify">Dengan Mendaftar,
                            saya menyatakan telah membaca dan setuju dengan <button type="button"
                                @click="isModalOpen = true; activeTab = 'sk'" class="text-blue-500 hover:underline">
                                Syarat dan Ketentuan
                            </button> serta
                            <button type="button" @click="isModalOpen = true; activeTab = 'pp'"
                                class="text-blue-500 hover:underline">
                                Privacy Policy
                            </button> yang berlaku
                        </div>

                        <!-- Modal for Terms and Privacy Policy -->
                        <div x-show="isModalOpen"
                            class="fixed left-0 top-0 z-99999 flex h-screen w-full flex-col items-center justify-between overflow-y-auto overflow-x-hidden bg-white p-6 lg:p-10"
                            style="display: none;">
                            <!-- Close Button -->
                            <button @click="isModalOpen = false"
                                class="absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z"
                                        fill=""></path>
                                </svg>
                            </button>

                            <!-- Modal Content -->
                            <div class="w-full max-w-6xl">
                                <!-- Tabs -->
                                <div class="flex border-b border-gray-200 mb-4">
                                    <button @click="activeTab = 'sk'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'sk', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'sk' }"
                                        class="py-2 px-4 font-medium text-sm border-b-2 -mb-px">
                                        Syarat dan Ketentuan
                                    </button>
                                    <button @click="activeTab = 'pp'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'pp', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'pp' }"
                                        class="py-2 px-4 font-medium text-sm border-b-2 -mb-px">
                                        Kebijakan Privasi
                                    </button>
                                </div>

                                <!-- PDF Viewer -->
                                <div class="h-[70vh]">
                                    <!-- Syarat Ketentuan PDF -->
                                    <div x-show="activeTab === 'sk'" class="h-full">
                                        @if (isset($skFileUrl))
                                            <embed src="{{ $skFileUrl }}" type="application/pdf" width="100%"
                                                height="100%" />
                                        @else
                                            <div
                                                class="flex items-center justify-center h-full bg-gray-100 rounded-lg">
                                                <p class="text-gray-500">Dokumen Syarat dan Ketentuan belum
                                                    tersedia.
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Privacy Policy PDF -->
                                    <div x-show="activeTab === 'pp'" class="h-full">
                                        @if (isset($ppFileUrl))
                                            <embed src="{{ $ppFileUrl }}" type="application/pdf" width="100%"
                                                height="100%" />
                                        @else
                                            <div
                                                class="flex items-center justify-center h-full bg-gray-100 rounded-lg">
                                                <p class="text-gray-500">Dokumen Kebijakan Privasi belum tersedia.
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="mt-8 flex w-full items-center justify-end gap-3">
                                <button @click="isModalOpen = false" type="button"
                                    class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="mt-5">
                <p class="text-sm font-normal text-center text-gray-700 sm:text-start">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-600 hover:underline">Masuk</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeIcons = document.querySelectorAll('.eye-icon');
            const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const eyeIconsConfirmation = document.querySelectorAll('.eye-icon-confirmation');

            togglePassword.addEventListener('click', function() {
                // Toggle password field type
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle eye icons
                eyeIcons.forEach(icon => {
                    icon.classList.toggle('hidden');
                });
            });

            togglePasswordConfirmation.addEventListener('click', function() {
                // Toggle password field type
                const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmation.setAttribute('type', type);

                // Toggle eye icons
                eyeIconsConfirmation.forEach(icon => {
                    icon.classList.toggle('hidden');
                });
            });
        });
    </script>
</x-guest-layout>
