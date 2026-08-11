<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Terima kasih telah mendaftar! Sebelum mulai, silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda belum menerima emailnya, silahkan klik tombol di bawah ini!') }}
        <br><br>
        <strong>Catatan:</strong> Jangan lupa untuk memeriksa folder <strong>Spam</strong> atau <strong>Promosi</strong>
        jika email tidak ditemukan di kotak masuk Anda.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda gunakan saat mendaftar.') }}
            <br><br>
            <strong>Catatan:</strong> Silakan periksa juga folder <strong>Spam</strong> atau <strong>Promosi</strong>
            pada email Anda.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button type="submit"
                    class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-blue-500 shadow-theme-xs hover:bg-blue-600">
                    {{ __('Kirim Email Verifikasi Lagi') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
