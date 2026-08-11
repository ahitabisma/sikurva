<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div>
        <div class="mb-1">
            <h1 class="mb-2 font-semibold text-gray-800 text-title-sm sm:text-title-md">
                Sign In
            </h1>
            <p class="text-sm text-gray-500">
                Silahkan masukkan email dan password untuk masuk
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
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-5">
                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" required />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan Email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                        <x-text-input id="captcha" class="block mt-1 w-full" type="text" name="captcha" required
                            autocomplete="captcha" placeholder="Masukkan CAPTCHA" />
                        <x-input-error :messages="$errors->get('captcha')" />
                    </div>
                    <!-- Checkbox -->
                    <div class="flex items-center justify-between">
                        <div x-data="{ checkboxToggle: false }">
                            <label for="checkboxLabelOne"
                                class="flex items-center text-sm font-normal text-gray-700 cursor-pointer select-none">
                                <div class="relative">
                                    <input type="checkbox" id="checkboxLabelOne" class="sr-only"
                                        @change="checkboxToggle = !checkboxToggle" name="remember" />
                                    <div :class="checkboxToggle ? 'border-brand-500 bg-blue-500' :
                                        'bg-transparent border-gray-300'"
                                        class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px]">
                                        <span :class="checkboxToggle ? '' : 'opacity-0'">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white"
                                                    stroke-width="1.94437" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                Remember Me
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-blue-500 hover:text-blue-600 hover:underline">Lupa
                            password?</a>
                    </div>
                    <!-- Button -->
                    <div>
                        <button
                            class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-blue-500 shadow-theme-xs hover:bg-blue-600">
                            Masuk
                        </button>
                    </div>
                </div>
            </form>
            <div class="mt-5">
                <p class="text-sm font-normal text-center text-gray-700 sm:text-start">
                    Belum punya akun?
                    <a href="{{ route('register') }}"
                        class="text-blue-500 hover:text-blue-600 hover:underline">Daftar</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeIcons = document.querySelectorAll('.eye-icon');

            togglePassword.addEventListener('click', function() {
                // Toggle password field type
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle eye icons
                eyeIcons.forEach(icon => {
                    icon.classList.toggle('hidden');
                });
            });
        });
    </script>
</x-guest-layout>
