@extends('layouts.tailadmin')

@section('content')
    <div x-data="subscriptionApp()">
        <!-- Package Selection -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">
            <template x-for="paket in packages" :key="paket.id">
                <div class="">
                    <div @click="selectPackage(paket)"
                        class="rounded-2xl border border-gray-200 bg-white p-3 md:p-3 transition-shadow cursor-pointer relative transform hover:-translate-y-1"
                        :class="{ 'ring-2 ring-blue-500 shadow-lg': selectedPackage?.id === paket.id }">
                        <div class="p-3">
                            <h3 class="text-lg font-semibold text-gray-900" x-text="paket.name"></h3>
                            <div class="mt-4 flex flex-col items-center justify-center text-center w-full">
                                <span class="text-3xl font-extrabold text-blue-600"
                                    x-text="'Rp ' + Number(paket.price).toLocaleString('id-ID')">
                                </span>
                                <span class="text-xl text-gray-500">
                                    <span
                                        x-text="paket.point + ' point' + ' / ' + paket.duration + ' ' + paket.duration_type"></span>
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

        <!-- Form and Summary -->
        <div class="mt-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">
                <div class="md:col-span-2 rounded-2xl border border-gray-200 bg-white overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Detail Pembayaran</h3>
                    </div>
                    <form action="{{ route('super-admin.langganan.transaksi.store') }}" method="POST" class="p-6 space-y-6"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="subscription_id" x-bind:value="selectedPackage?.id">

                        <!-- User Selection -->
                        <div class="w-full xl:w-full" x-data="searchUserByEmail()">
                            <x-input-label for="user_search" :value="__('User')" required />
                            <div x-data="searchUserByEmail()" class="relative">
                                <!-- Hidden input to store the actual user_id value -->
                                <input type="hidden" name="user_id" x-bind:value="selectedUserId"
                                    x-bind:required="subscriptionFor === 'user'">

                                <!-- Search input -->
                                <div class="relative">
                                    <input type="text" id="user_search" x-model="search" x-on:input="fetchResults()"
                                        x-on:focus="open = true" x-on:click.away="open = false"
                                        placeholder="Search user by email..."
                                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">

                                    <!-- Loading spinner -->
                                    <div x-show="isLoading" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Results dropdown -->
                                <div x-show="open && search.trim().length >= 3"
                                    class="absolute z-50 mt-1 w-full rounded-lg bg-white shadow-lg border border-gray-200 max-h-52 overflow-y-auto">
                                    <div x-show="errorMessage" class="p-3 text-sm text-red-500" x-text="errorMessage"></div>
                                    <div class="overflow-y-auto max-h-48">
                                        <template x-for="user in results" :key="user.id">
                                            <div @click="select(user.id, user.email)"
                                                class="cursor-pointer p-3 hover:bg-blue-50 text-sm border-b border-gray-100 flex justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        :class="`flex items-center justify-center w-5 h-5 rounded-full ${user.is_nakes == 1 || user.is_nakes === true || user.is_nakes === '1' ? 'bg-blue-100' : 'bg-red-100'}`">
                                                        <span
                                                            :class="`text-xs font-semibold ${user.is_nakes == 1 || user.is_nakes === true || user.is_nakes === '1' ? 'text-blue-500' : 'text-red-500'}`"
                                                            x-text="user.is_nakes == 1 || user.is_nakes === true || user.is_nakes === '1' ? 'N' : 'A'">
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="text-theme-sm mb-0.5 block font-medium text-gray-700"
                                                            x-text="user.is_nakes == 1 || user.is_nakes === true || user.is_nakes === '1'
                                                                ? user.name + ' (' + (user.instansi_name ?? '') + ')'
                                                                : user.name">
                                                        </span>
                                                        <p x-text="user.email" class="text-gray-500"></p>
                                                        <!-- Point information -->
                                                        <div class="flex flex-col gap-1 mt-1">
                                                            <div class="flex items-center text-sm gap-1">
                                                                <span class="text-gray-600">Points:</span>
                                                                <span class="font-medium text-blue-600"
                                                                    x-text="user.is_nakes == 1 || user.is_nakes === true || user.is_nakes === '1'
                                                                ? user.total_instansi_points : user.total_points"></span>
                                                                <span
                                                                    x-show="user.latest_expired || user.instansi_latest_expired"
                                                                    class="text-xs text-gray-500">
                                                                    (
                                                                    <span
                                                                        x-text="(user.is_nakes == 1 || user.is_nakes === true || user.is_nakes === '1'
                                                                            ? new Date(user.instansi_latest_expired)
                                                                            : new Date(user.latest_expired)
                                                                        ).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: '2-digit' })">
                                                                    </span>
                                                                    )
                                                                </span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </template>
                                        <div x-show="results.length === 0 && !errorMessage && !isLoading && search.trim().length >= 3"
                                            class="p-3 text-sm text-gray-500">
                                            No results found
                                        </div>
                                    </div>
                                </div>

                                <!-- Selected user info -->
                                <p class="text-theme-xs text-gray-500 mt-1">
                                    Masukkan minimal 3 karakter untuk mencari pengguna berdasarkan email atau nama pengguna.
                                </p>

                                <div x-show="selectedUserId" class="mt-2 text-sm">
                                    <span class="font-medium">Selected user:</span>
                                    <span x-text="selectedUserName"></span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <!-- Status Selection -->
                        <div class="w-full xl:w-full">
                            <x-input-label for="status" :value="__('Status Transaksi')" required />
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="status" id="status"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                    :class="isOptionSelected && 'text-gray-800'" @change="isOptionSelected = true"
                                    required>
                                    <option value="pending" selected>Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="cancelled">Cancelled</option>
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
                            <x-input-error :messages="$errors->get('status')" />
                        </div>

                        <button type="submit" :disabled="!selectedPackage"
                            class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-blue-500 shadow-theme-xs hover:bg-blue-600 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>

                <!-- Order Summary (unchanged) -->
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
                selectedPackage: null,
                subscriptionFor: '',
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
                    senderName: '',
                    senderAccountNumber: '',
                    selectedBank: 'Bank BCA',
                    transferProof: null
                },
                get selectedBankAccount() {
                    return this.bankAccounts.find(account => account.bank === this.form.selectedBank);
                },
                selectPackage(paket) {
                    this.selectedPackage = paket;
                }
            }
        }

        function searchUserByEmail() {
            return {
                open: false,
                search: '',
                selectedUserId: '',
                selectedUserName: '',
                isLoading: false,
                errorMessage: '',
                results: [],

                select(id, name) {
                    this.selectedUserId = id;
                    this.selectedUserName = name;
                    this.search = name;
                    this.open = false;
                },

                fetchResults() {
                    if (this.search.trim().length < 3) return;

                    this.isLoading = true;
                    this.errorMessage = '';

                    fetch(`/super-admin/point/transaksi/search?email=${encodeURIComponent(this.search)}`)
                        .then(response => response.json())
                        .then(data => {
                            this.results = data;
                            this.isLoading = false;

                            if (data.length === 0) {
                                this.errorMessage = 'No users found with this email';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching users:', error);
                            this.isLoading = false;
                            this.errorMessage = 'Failed to search users';
                        });
                }
            };
        }
    </script>
@endsection
