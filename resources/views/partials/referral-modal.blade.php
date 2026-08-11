{{-- Referral Modal --}}
{{-- <div x-show="isReferralModalOpen" x-cloak
    class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg" style="z-index: 99999;">
    <div class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
    <div @click.outside="isReferralModalOpen = false"
        class="relative w-full max-w-[584px] rounded-3xl bg-white p-6 lg:p-10">
        <!-- close btn -->
        <button @click="isReferralModalOpen = false"
            class="group absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-200 text-gray-500 transition-colors hover:bg-gray-300 hover:text-gray-500 sm:right-6 sm:top-6 sm:h-11 sm:w-11">
            <svg class="transition-colors fill-current group-hover:text-gray-600" width="24" height="24"
                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z">
                </path>
            </svg>
        </button>

        <form action="{{ route('referral.send') }}" method="POST" id="referralForm">
            @csrf
            <h4 class="mb-6 text-lg font-medium text-gray-800">
                Kirim Referral
            </h4>

            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    Bagikan eKurva.com dengan teman dan kolega Anda.
                </p>
            </div>

            <div class="mb-6">
                <x-input-label for="email" value="Email Penerima" required />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                    placeholder="Masukkan email penerima" required autofocus autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end w-full gap-3 mt-6">
                <button @click="isReferralModalOpen = false" type="button"
                    class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 sm:w-auto">
                    Batal
                </button>
                <x-primary-button class="min-w-fit">
                    Kirim Referral
                </x-primary-button>
            </div>
        </form>
    </div>
</div> --}}
{{-- End Referral Modal --}}
