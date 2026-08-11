<div x-data="{ isModalOpen: false }">
    <!-- Tombol untuk membuka modal -->
    @if (empty($textBtn))
        <button type="button" @click="isModalOpen = true">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    @else
        <button type="button" @click="isModalOpen = true"
            class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-green-500 px-4 py-2.5 font-medium text-white w-full justify-center hover:bg-green-700 transition ease-in-out duration-300">
            {{ $textBtn }}
        </button>
    @endif

    <!-- Modal -->
    <div x-show="isModalOpen" x-cloak
        class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
        style="z-index: 99999 !important">

        <div @click.outside="isModalOpen = false" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-lg">

            <!-- Tombol Close -->
            <button @click="isModalOpen = false" class="absolute right-3 top-3 text-gray-400 hover:text-gray-700">
                ✖
            </button>

            <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ $title ?? 'Konfirmasi Penerimaan' }}</h4>
            <p class="text-sm text-gray-500">
                {{ $message ?? 'Apakah Anda yakin ingin menyetujui pembagian data pasien ini?' }}</p>

            <div class="flex justify-end mt-5">
                <button @click="isModalOpen = false"
                    class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    {{ $cancelText ?? 'Batal' }}
                </button>

                <form action="{{ $url }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="accepted">
                    <input type="hidden" name="accepted_at" value="{{ now() }}">
                    <button type="submit"
                        class="ml-3 px-4 py-2 text-sm text-white bg-green-500 rounded-lg hover:bg-green-600">
                        {{ $confirmText ?? 'Setuju' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
