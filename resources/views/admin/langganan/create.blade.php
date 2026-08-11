@extends('layouts.tailadmin')

@section('content')
    <div x-data="subscriptionApp()" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">
            <template x-for="paket in packages" :key="paket.id">
                <div class="">
                    <div @click="selectPackage(paket)"
                        class="rounded-2xl border border-gray-200 bg-white p-3 md:p-3 transition-shadow cursor-pointer relative transform hover:-translate-y-1"
                        :class="{ 'ring-2 ring-blue-500 shadow-lg': selectedPackage?.id === paket.id }">
                        <div class="p-3">
                            <h3 class="text-lg font-semibold text-gray-900" x-text="paket.name"></h3>

                            <!-- Harga Paket -->
                            <div class="mt-4 flex flex-col items-center justify-center text-center w-full">
                                <span class="text-3xl font-extrabold text-blue-600"
                                    x-text="'Rp ' + Number(paket.price).toLocaleString('id-ID')">
                                </span>
                                <span class="text-xl text-gray-500">
                                    <span
                                        x-text="paket.point + ' point / ' + paket.duration + ' ' + paket.duration_type"></span>
                                </span>
                            </div>

                            {{-- Description --}}
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

        <div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">
                <!-- Order Summary -->
                <div class="md:col-span-3 rounded-2xl border border-gray-200 bg-white overflow-hidden">
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
                                    <span class="text-gray-600">Point</span>
                                    <span x-text="selectedPackage.point"></span>
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
                            <div class="p-6">
                                <button type="button" :disabled="!selectedPackage || isProcessing"
                                    @click="processPayment()"
                                    class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-blue-500 shadow-theme-xs hover:bg-blue-600 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-text="isProcessing ? 'Memproses...' : 'Lanjut ke Pembayaran'"></span>
                                </button>
                                <p class="text-xs text-gray-500 text-center mt-4">
                                    Pembayaran akan diproses melalui Midtrans secara aman
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Loading overlay -->
        <div x-show="isProcessing"
            class="fixed left-0 top-0 z-999999 flex flex-col h-screen w-screen items-center justify-center bg-white">
            <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-blue-500 border-t-transparent">
            </div>
            <p class="mt-5">Mempersiapkan pembayaran...</p>
        </div>
    </div>
@endsection

@section('script')
    <!-- Include Midtrans Snap JS -->
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    <script>
        function subscriptionApp() {
            return {
                selectedPackage: null,
                packages: @json($pakets ?? []),
                isProcessing: false,

                selectPackage(paket) {
                    this.selectedPackage = paket;
                },

                processPayment() {
                    if (!this.selectedPackage) return;

                    this.isProcessing = true;

                    // Send AJAX request to create subscription
                    fetch('{{ route('langganan.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                subscription_id: this.selectedPackage.id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show Midtrans Snap payment popup
                                window.snap.pay(data.token, {
                                    onSuccess: function(result) {
                                        window.location.href = '{{ route('payment.finish') }}';
                                    },
                                    onPending: function(result) {
                                        window.location.href = '{{ route('payment.pending') }}';
                                    },
                                    onError: function(result) {
                                        window.location.href = '{{ route('payment.error') }}';
                                    },
                                    onClose: function() {
                                        window.location.href = '{{ route('payment.unfinish') }}';
                                    }
                                });
                            } else {
                                alert('Terjadi kesalahan: ' + data.message);
                            }
                            this.isProcessing = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses pembayaran');
                            this.isProcessing = false;
                        });
                }
            }
        }
    </script>
@endsection
