{{-- Kode Lokal --}}
{{-- <section x-data="{ isDisabled: true }">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Kode MR') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Setting Kode MR untuk Pasien') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update-kode-lokal') }}" class="my-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="kode_mr" :value="__('Kode MR')" />
                <x-text-input id="kode_mr" name="kode_mr" type="text" class="mt-1 block w-full disabled:bg-gray-100"
                    :value="old('kode_mr', $kode_lokal)" required autofocus autocomplete="kode_mr" maxlength="3"
                    x-bind:disabled="isDisabled" />
                <x-input-error class="mt-2" :messages="$errors->get('kode_mr')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button x-show="isDisabled" type="button" @click="isDisabled = !isDisabled"
                class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-orange-500 shadow-theme-xs hover:bg-orange-600 max-w-min"
                x-text="isDisabled ? 'Ubah' : 'Batal'">
            </button>

            <x-primary-button x-show="!isDisabled">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated-kode-lokal')
                <x-alert-success title="Kode MR berhasil diperbarui!" />
            @endif
        </div>
    </form>
</section> --}}

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" :value="__('Nama')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                    required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div>
                <x-input-label for="phone" :value="__('No Whatsapp')" />
                <x-text-input id="phone" name="phone" type="number" class="mt-1 block w-full" :value="old('phone', $user->phone)"
                    required autofocus autocomplete="phone" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            @role('admin')
                @if (Auth::user()->is_nakes)
                    <div>
                        <x-input-label for="instansi" :value="__('Instansi')" />
                        <x-text-input id="instansi" name="instansi" type="text" class="mt-1 block w-full"
                            :value="old('instansi', $user->getInstansi())" required autofocus autocomplete="instansi"
                            placeholder="Masukkan Nama Instansi" />
                        <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                        {{-- @if ($user->getInstansiVerified())
                    <x-text-input id="instansi" name="instansi" type="text" class="mt-1 block w-full"
                            :value="old('instansi', $user->getInstansi())" required autofocus autocomplete="instansi" />
                            <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                    @else
                        <p id="instansi"
                            class="h-11 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10  read-only:bg-gray-100 read-only:cursor-not-allowed mt-1 block w-full disabled:bg-gray-100">
                            {{ $user->getInstansi() }}</p>

                            <p class="text-theme-xs text-gray-500 mt-1">
                                Untuk mendaftarkan Klinik/ RS / Puskesmas, silahkan request email ke <span
                                class="font-bold">cs.ptekai@gmail.com</span>
                    @endif --}}
                    </div>
                @endif
            @endrole
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Address -->
            <div>
                <x-input-label for="address" :value="__('Alamat')" />
                <x-textarea id="address" class="block mt-1 w-full" name="address" required autocomplete="address"
                    placeholder="Masukkan Alamat">{{ $user->address }}</x-textarea>
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <x-alert-success title="Profile Updated" />
            @endif
            @if (session('error'))
                <x-alert-success :title="session('error')" />
            @endif
        </div>
    </form>
</section>
