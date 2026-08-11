@extends('layouts.tailadmin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Verifikasi Autentikasi Dua Faktor</h2>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-700">
                    Silakan masukkan kode 6 digit dari aplikasi Google Authenticator untuk melanjutkan.
                </p>
            </div>

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
                    {{ session('info') }}
                </div>
            @endif

            <form method="POST" action="{{ route('2fa.validate') }}" class="bg-gray-50 p-6 rounded-lg">
                @csrf
                <div class="mb-6">
                    <label for="one_time_password" class="block text-gray-700 text-sm font-bold mb-3">
                        Kode Autentikasi
                    </label>
                    <div class="flex">
                        <x-text-input id="one_time_password" name="one_time_password" type="text"
                            class="mt-1 block w-full" placeholder="000000" maxlength="6" required autofocus />
                    </div>
                    <p class="text-gray-500 text-xs mt-2">Masukkan kode 6 digit dari aplikasi Google Authenticator Anda</p>
                </div>

                <x-primary-button class="w-full min-w-full text-lg">Verifikasi</x-primary-button>
            </form>
        </div>
    </div>
@endsection
