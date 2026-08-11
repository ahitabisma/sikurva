<div x-data="{ isModalOpen: false }">
    <!-- Tombol untuk membuka modal -->
    @if (empty($textBtn))
        <button type="button" @click="isModalOpen = true">
            <x-svg-delete />
        </button>
    @else
        <button type="button" @click="isModalOpen = true"
            class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border  bg-red-500 px-4 py-2.5 font-medium text-white w-full justify-center hover:bg-red-700 transition ease-in-out duration-300">
            {{ $textBtn }}
        </button>
    @endif

    <!-- Modal -->
    <div x-show="isModalOpen" x-cloak
        class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
        style="z-index: 99999 !important" x-transition:enter="transition ease duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div @click.outside="isModalOpen = false" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-lg">

            <!-- Tombol Close -->
            <button @click="isModalOpen = false" class="absolute right-3 top-3 text-gray-400 hover:text-gray-700">
                ✖
            </button>

            <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h4>
            <p class="text-sm text-gray-500">{{ $message }}</p>

            <div class="flex justify-end mt-5">
                <button @click="isModalOpen = false"
                    class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    {{ $cancelText }}
                </button>

                <form action="{{ $url }}" method="POST">
                    @csrf
                    @if ($isDelete)
                        @method('DELETE')
                    @endif
                    <button type="submit"
                        class="ml-3 px-4 py-2 text-sm text-white bg-red-500 rounded-lg hover:bg-red-600">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
