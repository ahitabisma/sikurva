@extends('layouts.tailadmin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Autentikasi Dua Faktor</h2>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-700">
                    Autentikasi dua faktor menambahkan lapisan keamanan ekstra untuk akun Anda dengan mengharuskan tidak
                    hanya kata sandi tetapi juga kode verifikasi.
                </p>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Ikuti langkah-langkah berikut untuk mengatur:</h3>

                <ol class="space-y-3 list-decimal list-inside text-gray-700">
                    <li class="p-2 hover:bg-gray-50 rounded">Unduh dan instal aplikasi <span class="font-semibold">Google
                            Authenticator</span> di perangkat mobile Anda</li>
                    <li class="p-2 hover:bg-gray-50 rounded">Di aplikasi, ketuk <span class="font-semibold">ikon +</span>
                        untuk menambahkan akun baru</li>
                    <li class="p-2 hover:bg-gray-50 rounded">Pilih <span class="font-semibold">"Masukkan kunci
                            pengaturan"</span></li>
                    <li class="p-2 hover:bg-gray-50 rounded">Masukkan email Anda sebagai nama akun</li>
                    <li class="p-2 hover:bg-gray-50 rounded">Masukkan kunci rahasia ini:
                        <div
                            class="mt-2 p-4 bg-gray-100 border border-gray-300 rounded-md font-mono text-center select-all">
                            {{ $secret }}
                        </div>
                    </li>
                    <li class="p-2 hover:bg-gray-50 rounded">Pastikan <span class="font-semibold">"Berbasis waktu"</span>
                        dipilih dan ketuk Tambah</li>
                    <li class="p-2 hover:bg-gray-50 rounded">Masukkan kode 6 digit dari aplikasi di bawah</li>
                </ol>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-8">
                <p class="text-yellow-700">
                    <span class="font-bold">Penting:</span> Simpan kunci rahasia ini di tempat yang aman. Anda akan
                    membutuhkannya jika perlu memulihkan pengaturan autentikator.
                </p>
            </div>

            <form method="POST" action="{{ route('2fa.enable') }}" class="bg-gray-50 p-6 rounded-lg">
                @csrf
                <div class="mb-6">
                    <label for="one_time_password" class="block text-gray-700 text-sm font-bold mb-3">
                        Masukkan kode 6 digit dari Google Authenticator
                    </label>
                    <x-text-input id="one_time_password" name="one_time_password" type="text" class="mt-1 block w-full"
                        placeholder="000000" maxlength="6" required autofocus />
                </div>

                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <x-primary-button class="w-full min-w-full text-lg">Verifikasi & Aktifkan 2FA</x-primary-button>
            </form>
        </div>
    </div>
@endsection
