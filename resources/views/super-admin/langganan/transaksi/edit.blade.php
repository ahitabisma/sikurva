@extends('layouts.tailadmin')

@section('content')
    <div x-data="subscriptionApp()">
        <!-- Package Selection -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6" :class="isPaid && 'pointer-events-none opacity-50'">
            <template x-for="paket in packages" :key="paket.id">
                <div class="">
                    <div @click="!isPaid && selectPackage(paket)"
                        class="rounded-2xl border border-gray-200 bg-white p-3 md:p-3 transition-shadow cursor-pointer relative transform hover:-translate-y-1"
                        :class="{ 'ring-2 ring-blue-500 shadow-lg': selectedPackage?.id === paket.id }">
                        <div class="p-3">
                            <h3 class="text-lg font-semibold text-gray-900" x-text="paket.name"></h3>
                            <div class="mt-4 flex flex-col items-center justify-center text-center w-full">
                                <span class="text-3xl font-extrabold text-blue-600"
                                    x-text="'Rp ' + Number(paket.price).toLocaleString('id-ID')">
                                </span>
                                <span class="text-xl text-gray-500">
                                    <span x-text="paket.duration + ' ' + paket.duration_type"></span>
                                </span>
                            </div>
                            <ul class="mt-6 space-y-3 min-h-[100px]">
                                <template x-for="feature in JSON.parse(paket.description)" :key="feature">
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-teal-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <p class="ml-3 text-sm text-gray-700" x-text="feature"></p>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        <div class="py-4 px-6 border-t border-gray-200">
                            <div class="w-full py-2 px-4 rounded-md text-center font-medium transition-colors "
                                :class="selectedPackage?.id === paket.id ?
                                    'bg-blue-600 text-white' :
                                    'bg-white border border-blue-600 text-blue-600 hover:bg-blue-50'">
                                <span x-text="selectedPackage?.id === paket.id ? 'TERPILIH' : 'Pilih Paket'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        @if ($langganan->status === 'paid')
            <p class="mt-2 text-xs text-gray-500">Paket tidak dapat diubah karena status sudah 'Paid'.</p>
        @endif

        <!-- Form and Summary -->
        <div class="mt-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">
                <div class="md:col-span-2 rounded-2xl border border-gray-200 bg-white overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Edit Transaksi</h3>
                    </div>
                    <form action="{{ route('super-admin.langganan.transaksi.update', $langganan->id) }}" method="POST" class="p-6 space-y-6"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="subscription_id" x-bind:value="selectedPackage?.id">
                        
                        <!-- Display User or Instansi (Read-only) -->
                        <div class="w-full xl:w-full">
                            <x-input-label id="subscription_for" :value="'Transaksi Untuk: ' . ($langganan->user_id ? 'User' : 'Klinik')" />

                            <x-text-input id="subscription_for" type="text"
                                value="{{ $langganan->user_id ? $langganan->user_name : $langganan->instansi_name }}"
                                disabled />
                        </div>

                        <!-- Payment Details -->
                        <div class="w-full xl:w-full">
                            <x-input-label for="sender_name" :value="__('Nama Pengirim')" required />
                            <x-text-input id="sender_name" type="text" name="sender_name"
                                value="{{ old('sender_name', $langganan->sender_name) }}"
                                placeholder="Masukkan Nama Pengirim" required />
                            <x-input-error :messages="$errors->get('sender_name')" />
                        </div>

                        <div class="w-full xl:w-full">
                            <x-input-label for="account_number" :value="__('Nomor Rekening Pengirim')" required />
                            <x-text-input id="account_number" type="text" name="account_number"
                                value="{{ old('account_number', $langganan->account_number) }}"
                                placeholder="Masukkan Nomor Rekening Pengirim" required />
                            <x-input-error :messages="$errors->get('account_number')" />
                        </div>

                        <div class="w-full xl:w-full">
                            <x-input-label for="bank" :value="__('Bank')" required />
                            <x-text-input id="bank" type="text" name="bank"
                                value="{{ old('bank', $langganan->bank) }}" placeholder="Masukkan Bank" required />
                            <x-input-error :messages="$errors->get('bank')" />
                        </div>

                        <div class="w-full xl:w-full">
                            <x-input-label for="photo" :value="__('Upload Bukti Transfer')" />
                            <input type="file" name="photo" accept="image/*"
                                class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden" />
                            <p class="mt-1 text-xs text-gray-500">
                                Format yang didukung: JPG, PNG, atau JPEG. Maksimal 1MB. Biarkan kosong jika tidak ingin
                                mengubah.
                            </p>
                            <p class="mt-1 text-xs text-gray-700">Bukti saat ini: <a
                                    href="{{ Storage::url($langganan->photo) }}" target="_blank" class="text-blue-500 underline hover:text-blue-700">Lihat</a></p>
                            <x-input-error :messages="$errors->get('photo')" />
                        </div>

                        <!-- Status Selection -->
                        <div class="w-full xl:w-full">
                            <x-input-label for="status" :value="__('Status Transaksi')" required />
                            <div x-data="{ isOptionSelected: '{{ $langganan->status }}' !== '' }" class="relative z-20 bg-transparent">
                                <select name="status" id="status"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden disabled:bg-gray-100"
                                    :class="isOptionSelected && 'text-gray-800'" @change="isOptionSelected = true" required
                                    {{ $langganan->status === 'paid' ? 'disabled' : '' }}>
                                    <option value="pending" {{ $langganan->status === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="paid" {{ $langganan->status === 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="cancelled" {{ $langganan->status === 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                                <span
                                    class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </div>
                            @if ($langganan->status === 'paid')
                                <p class="mt-1 text-xs text-gray-500">Status 'Paid' tidak dapat diubah.</p>
                            @endif
                            <x-input-error :messages="$errors->get('status')" />
                        </div>

                        <!-- Bank Account Info -->
                        <div class="">
                            <h4 class="text-sm font-medium text-gray-900">Informasi Rekening Tujuan</h4>
                            <p class="text-xs text-gray-500 mb-3">
                                Silahkan transfer ke rekening berikut!
                            </p>
                            <template x-if="selectedBankAccount">
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Bank:</span>
                                        <span x-text="selectedBankAccount.bank"></span>
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Nama Rekening:</span>
                                        <span x-text="selectedBankAccount.accountName"></span>
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Nomor Rekening:</span>
                                        <span x-text="selectedBankAccount.accountNumber"></span>
                                    </p>
                                </div>
                            </template>
                        </div>

                        <button type="submit"
                            class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-blue-500 shadow-theme-xs hover:bg-blue-600 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Ringkasan Pesanan</h3>
                    </div>
                    <template x-if="!selectedPackage">
                        <div class="p-6">
                            <p class="text-gray-500 text-center">Silakan pilih paket langganan terlebih dahulu</p>
                        </div>
                    </template>
                    <template x-if="selectedPackage">
                        <div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Paket</span>
                                    <span class="font-medium" x-text="selectedPackage.name"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Durasi</span>
                                    <span x-text="selectedPackage.duration + ' ' + selectedPackage.duration_type"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Harga</span>
                                    <span>Rp <span
                                            x-text="Number(selectedPackage.price).toLocaleString('id-ID')"></span></span>
                                </div>
                                <div class="border-t border-gray-200 pt-4 flex justify-between">
                                    <span class="font-medium text-gray-900">Total Pembayaran</span>
                                    <span class="font-bold text-blue-600">
                                        Rp <span x-text="Number(selectedPackage.price).toLocaleString('id-ID')"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-gray-500 text-center">
                                    Harga sudah termasuk pajak dan biaya layanan
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function subscriptionApp() {
            return {
                selectedPackage: @json($selectedPaket),
                subscriptionFor: '{{ $langganan->user_id ? 'user' : 'instansi' }}',
                isPaid: '{{ $langganan->status === 'paid' }}' === '1',
                packages: @json($pakets ?? []),
                bankAccounts: [{
                        bank: 'Bank BCA',
                        accountName: 'PT Sikurva',
                        accountNumber: '8730456210'
                    },
                    {
                        bank: 'Bank Mandiri',
                        accountName: 'PT Sikurva',
                        accountNumber: '1370005612345'
                    },
                    {
                        bank: 'Bank BNI',
                        accountName: 'PT Sikurva',
                        accountNumber: '0246810121416'
                    }
                ],
                form: {
                    senderName: '{{ $langganan->sender_name }}',
                    senderAccountNumber: '{{ $langganan->account_number }}',
                    selectedBank: 'Bank BCA',
                    transferProof: null
                },
                get selectedBankAccount() {
                    return this.bankAccounts.find(account => account.bank === this.form.selectedBank);
                },
                selectPackage(paket) {
                    if (!this.isPaid) {
                        this.selectedPackage = paket;
                    }
                }
            }
        }
    </script>
@endsection
