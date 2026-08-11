
@extends('layouts.tailadmin')

@section('content')
    <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5" x-data="apiSettings">
        <form action="{{ route('super-admin.setting.api.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="pb-5 mb-5 border-b border-gray-200">
                <button type="submit"
                    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan
                </button>
            </div>

            <!-- Midtrans API Settings -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Midtrans Payment Gateway</h2>
                <div class="space-y-4">
                    @foreach ($apiSettings->filter(function ($setting) {
            return strpos($setting->key, 'MIDTRANS_') === 0;
        }) as $setting)
                        <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="input-{{ $setting->key }}"
                                            class="text-sm font-medium block">{{ $setting->key }}</label>
                                        @if ($setting->is_encrypted)
                                            <span
                                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Encrypted
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 relative">
                                        <input type="password" id="input-{{ $setting->key }}" name="{{ $setting->key }}"
                                            value="{{ $setting->value }}"
                                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10"
                                            placeholder="Enter {{ $setting->key }}">
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex gap-2">
                                            <button type="button" class="toggle-password text-gray-500 hover:text-gray-700"
                                                data-target="input-{{ $setting->key }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            {{-- <a href="{{ route('super-admin.setting.api.delete', $setting->key) }}"
                                                class="text-red-500 hover:text-red-700"
                                                onclick="return confirm('Are you sure you want to clear this API key?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </a> --}}
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        @if ($setting->key == 'MIDTRANS_MERCHANT_ID')
                                            Your Midtrans Merchant ID from your account dashboard
                                        @elseif ($setting->key == 'MIDTRANS_CLIENT_KEY')
                                            Client key for client-side integration (visible to customers)
                                        @elseif ($setting->key == 'MIDTRANS_SERVER_KEY')
                                            Server key for secure backend API calls (keep this private)
                                        @endif
                                    </p>
                                    @error($setting->key)
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- WhatsApp API Settings -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">WhatsApp API</h2>
                <div class="space-y-4">
                    @foreach ($apiSettings->filter(function ($setting) {
            return strpos($setting->key, 'WHATSAPP_') === 0;
        }) as $setting)
                        <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="input-{{ $setting->key }}"
                                            class="text-sm font-medium block">{{ $setting->key }}</label>
                                        @if ($setting->is_encrypted)
                                            <span
                                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Encrypted
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 relative">
                                        <input type="password" id="input-{{ $setting->key }}" name="{{ $setting->key }}"
                                            value="{{ $setting->value }}"
                                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10"
                                            placeholder="Enter {{ $setting->key }}">
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex gap-2">
                                            <button type="button" class="toggle-password text-gray-500 hover:text-gray-700"
                                                data-target="input-{{ $setting->key }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            {{-- <a href="{{ route('super-admin.setting.api.delete', $setting->key) }}"
                                                class="text-red-500 hover:text-red-700"
                                                onclick="return confirm('Are you sure you want to clear this API key?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </a> --}}
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        @if ($setting->key == 'WHATSAPP_API_KEY')
                                            WhatsApp Business API key for authentication
                                        @elseif ($setting->key == 'WHATSAPP_API_TOKEN')
                                            Access token for WhatsApp API requests
                                        @elseif ($setting->key == 'WHATSAPP_WEBHOOK_SECRET')
                                            Secret for verifying incoming webhook events from WhatsApp
                                        @endif
                                    </p>
                                    @error($setting->key)
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Information Panel -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-800">API Configuration Security</h5>
                        <p class="text-sm text-gray-500">All sensitive API keys (tokens, secrets, etc.) are automatically
                            encrypted when stored in the database.</p>
                        <p class="text-sm text-gray-500 mt-2">
                            For more information about Midtrans, please visit
                            <a href="https://docs.midtrans.com/" target="_blank"
                                class="text-blue-600 hover:text-blue-800 hover:underline">
                                Midtrans Documentation
                            </a>.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('apiSettings', () => ({
                init() {
                    // Set up event listeners for toggle password buttons
                    document.querySelectorAll('.toggle-password').forEach(button => {
                        button.addEventListener('click', function() {
                            const inputId = this.getAttribute('data-target');
                            const input = document.getElementById(inputId);

                            if (input.type === 'password') {
                                input.type = 'text';
                                this.innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                `;
                            } else {
                                input.type = 'password';
                                this.innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                `;
                            }
                        });
                    });
                }
            }));
        });
    </script>
@endsection
