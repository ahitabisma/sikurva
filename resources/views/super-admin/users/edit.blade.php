@extends('layouts.tailadmin')

@section('content')
    <div x-data="{ isNakes: {{ old('is_nakes', $user->is_nakes) }}, isActive: {{ old('status', $user->status) }} }" class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('super-admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <!-- Is Nakes Radio Button -->
                    @if ($user->is_nakes)
                        <div class="w-full mx-2.5" x-data="{ isNakes: '{{ old('is_nakes', $user->is_nakes) }}' }">
                            <!-- Institution Name (shown if is_nakes is '1') -->
                            <div class="mt-4 w-full xl:w-1/2">
                                <x-input-label for="instansi" :value="__('Nama Instansi')" required />
                                <x-text-input id="instansi" class="block mt-1 w-full" type="text" name="instansi"
                                    :value="old('instansi', $user->instansi?->name)" x-bind:required="isNakes == '1'" autocomplete="instansi"
                                    placeholder="Masukkan Nama Instansi" />
                                <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                            </div>
                        </div>
                    @endif

                    <!-- Input Nama -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="name" :value="__('Nama')" required />
                        <x-text-input id="name" type="text" name="name" required placeholder="Masukkan Nama"
                            :value="old('name', $user->name)" />
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <!-- Input Email -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="email" :value="__('Email')" required />
                        <x-text-input id="email" type="email" :value="$user->email" disabled readonly />
                        <p class="mt-1 text-sm text-gray-600">Email tidak dapat diubah</p>
                    </div>


                    <!-- Input No. Whatsapp -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="no_wa" :value="__('No. Whatsapp')" required />
                        <x-text-input id="no_wa" type="text" name="phone" required
                            placeholder="Masukkan No. Whatsapp" :value="old('phone', $user->phone)" />
                        <x-input-error :messages="$errors->get('phone')" />
                    </div>

                    <!-- Input Alamat -->
                    <div class="w-full px-2.5 xl:w-full">
                        <x-input-label for="alamat" :value="__('Alamat')" required />
                        <x-textarea placeholder="Masukkan Alamat" name="address"
                            required>{{ old('address', $user->address) }}</x-textarea>
                        <x-input-error :messages="$errors->get('address')" />
                    </div>

                    <!-- Input Password -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="password" :value="__('Password (kosongkan jika tidak ingin mengubah)')" />
                        <x-text-input id="password" type="password" name="password" placeholder="Masukkan Password Baru" />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <!-- Input Confirm Password -->
                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="Konfirmasi Password Baru" />
                        <x-input-error :messages="$errors->get('password_confirmation')" />
                    </div>

                    <!-- Referral Code (Display Only) -->
                    @if (!$user->is_nakes && $user->referral_code)
                        <div class="w-full px-2.5 xl:w-1/2">
                            <x-input-label for="referral_code_display" :value="__('Kode Referral')" />
                            <x-text-input id="referral_code_display" type="text" :value="$user->referral_code" disabled readonly />
                            <p class="mt-1 text-sm text-gray-600">Kode referral pengguna ini (tidak dapat diubah)</p>
                        </div>
                    @endif

                    <!-- Input Status -->
                    {{-- <div class="w-full mx-2.5" x-data="{ status: {{ old('status', $user->status) }} }">
                        <x-input-label :value="'Status?'" required />
                        <div class="mt-2">
                            <div class="flex items-center space-x-6">
                                <label
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" name="status" value="1" x-model="status" class="sr-only"
                                            required />
                                        <div class="hover:border-blue-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]"
                                            :class="status == '1' ? 'border-blue-500 bg-blue-500' :
                                                'bg-transparent border-gray-300'">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="status == '1' ? 'bg-white' : 'bg-white'"></span>
                                        </div>
                                    </div>
                                    {{ 'Ya' }}
                                </label>

                                <label
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none">
                                    <div class="relative">
                                        <input type="radio" name="status" value="0" x-model="status" class="sr-only"
                                            required />
                                        <div class="hover:border-blue-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]"
                                            :class="status == '0' ? 'border-blue-500 bg-blue-500' :
                                                'bg-transparent border-gray-300'">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="status == '0' ? 'bg-white' : 'bg-white'"></span>
                                        </div>
                                    </div>
                                    {{ 'Tidak' }}
                                </label>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div> --}}

                    <!-- Submit Button -->
                    <div class="w-full px-2.5">
                        <div class="flex items-center gap-3 mt-1">
                            <button type="submit"
                                class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                                Update
                            </button>
                            <a href="{{ route('super-admin.users.index') }}"
                                class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
