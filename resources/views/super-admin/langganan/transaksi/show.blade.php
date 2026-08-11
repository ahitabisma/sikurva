@extends('layouts.tailadmin')

@section('content')
    <div x-data="subscription()">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">
            <div class="md:col-span-2 rounded-2xl border border-gray-200 bg-white overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detail Langganan</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="w-full xl:w-full">
                        <x-input-label for="status" :value="__('Status Transaksi')" />
                        <div class="relative z-20 bg-transparent">
                            <div
                                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 px-4 py-3 text-sm bg-gray-100
                            {{ $langganan->status === 'paid'
                                ? 'text-green-600 font-medium'
                                : ($langganan->status === 'pending'
                                    ? 'text-amber-600 font-medium'
                                    : 'text-red-600 font-medium') }}">
                                {{ ucfirst($langganan->status) }}
                            </div>
                        </div>
                    </div>

                    @if ($langganan->order_id)
                        <div class="w-full xl:w-full">
                            <x-input-label for="order_id" :value="__('Order ID')" />
                            <div
                                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-3 text-sm disabled:bg-gray-100">
                                {{ $langganan->order_id }}
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="w-full">
                            <x-input-label :value="__('Tipe Berlangganan')" />
                            <div
                                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-3 text-sm disabled:bg-gray-100">
                                {{ $langganan->user_id ? 'Personal' : 'Instansi' }}
                            </div>
                        </div>

                        <div class="w-full">
                            <x-input-label :value="__('Nama Pelanggan')" />
                            <div
                                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-3 text-sm disabled:bg-gray-100">
                                @if ($langganan->user_id)
                                    {{ $langganan->user->name ?? 'User tidak ditemukan' }}
                                @else
                                    {{ $langganan->instansi->name ?? 'Instansi tidak ditemukan' }}
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($langganan->payment_type)
                        <div class="w-full xl:w-full">
                            <x-input-label for="payment_type" :value="__('Metode Pembayaran')" />
                            <div
                                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm disabled:bg-gray-100">
                                {{ ucfirst(str_replace('_', ' ', $langganan->payment_type)) }}
                            </div>
                        </div>
                    @endif

                    @if ($langganan->payment_details)
                        <div class="w-full xl:w-full">
                            <x-input-label :value="__('Detail Pembayaran')" />
                            <div class="mt-2 p-4 rounded-lg border border-gray-300 bg-gray-50">
                                @php
                                    $paymentDetails = json_decode($langganan->payment_details);
                                @endphp

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if (isset($paymentDetails->transaction_time))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Waktu Transaksi</span>
                                            <span
                                                class="text-sm font-medium">{{ \Carbon\Carbon::parse($paymentDetails->transaction_time)->format('d M Y, H:i') }}</span>
                                        </div>
                                    @endif

                                    @if (isset($paymentDetails->transaction_status))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Status Transaksi</span>
                                            <span
                                                class="text-sm font-medium">{{ ucfirst($paymentDetails->transaction_status) }}</span>
                                        </div>
                                    @endif

                                    @if (isset($paymentDetails->gross_amount))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Jumlah</span>
                                            <span class="text-sm font-medium">Rp
                                                {{ number_format($paymentDetails->gross_amount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif

                                    @if (isset($paymentDetails->payment_type))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Tipe Pembayaran</span>
                                            <span
                                                class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $paymentDetails->payment_type)) }}</span>
                                        </div>
                                    @endif

                                    @if (isset($paymentDetails->bank))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Bank</span>
                                            <span
                                                class="text-sm font-medium">{{ strtoupper($paymentDetails->bank) }}</span>
                                        </div>
                                    @endif

                                    @if (isset($paymentDetails->va_number) || isset($paymentDetails->va_numbers[0]->va_number))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Virtual Account</span>
                                            <span
                                                class="text-sm font-medium">{{ isset($paymentDetails->va_number) ? $paymentDetails->va_number : $paymentDetails->va_numbers[0]->va_number }}</span>
                                        </div>
                                    @endif

                                    @if (isset($paymentDetails->store))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Store</span>
                                            <span
                                                class="text-sm font-medium">{{ strtoupper($paymentDetails->store) }}</span>
                                        </div>
                                    @endif

                                    @if (isset($paymentDetails->payment_code))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Kode Pembayaran</span>
                                            <span class="text-sm font-medium">{{ $paymentDetails->payment_code }}</span>
                                        </div>
                                    @endif

                                    @if (isset($paymentDetails->bill_key))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Bill Key</span>
                                            <span class="text-sm font-medium">{{ $paymentDetails->bill_key }}</span>
                                        </div>
                                    @endif

                                    @if (isset($paymentDetails->biller_code))
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">Biller Code</span>
                                            <span class="text-sm font-medium">{{ $paymentDetails->biller_code }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="w-full xl:w-full">
                        <x-input-label :value="__('Informasi Langganan')" />
                        <div class="mt-2 p-4 rounded-lg border border-gray-300 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($langganan->started_at)
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Tanggal Mulai</span>
                                        <span
                                            class="text-sm font-medium">{{ \Carbon\Carbon::parse($langganan->started_at)->format('d M Y') }}</span>
                                    </div>
                                @endif

                                @if ($langganan->expired_at)
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Tanggal Berakhir</span>
                                        <span
                                            class="text-sm font-medium">{{ \Carbon\Carbon::parse($langganan->expired_at)->format('d M Y') }}</span>
                                    </div>
                                @endif

                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Durasi</span>
                                    <span class="text-sm font-medium">{{ $langganan->duration }}
                                        {{ $langganan->duration_type }}</span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Point</span>
                                    <span class="text-sm font-medium">{{ $langganan->point }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ringkasan Pesanan</h3>
                </div>
                <div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Paket</span>
                            <span class="font-medium">{{ $langganan->subscription->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Point</span>
                            <span>{{ $langganan->point }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi</span>
                            <span>{{ $langganan->duration . ' ' . $langganan->duration_type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga</span>
                            <span>Rp <span>{{ number_format($langganan->price, 0, ',', '.') }}</span></span>
                        </div>
                        <div class="border-t border-gray-200 pt-4 flex justify-between">
                            <span class="font-medium text-gray-900">Total Pembayaran</span>
                            <span class="font-bold text-blue-600">
                                Rp <span>{{ number_format($langganan->price, 0, ',', '.') }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-500 text-center">
                            Harga sudah termasuk pajak dan biaya layanan
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History (Admin Only) -->
        @if ($langganan->status === 'paid')
            <div class="mt-6 rounded-2xl border border-gray-200 bg-white overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Riwayat Point</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Point</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipe</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @if (isset($pointTransactions) && $pointTransactions->count() > 0)
                                    @foreach ($pointTransactions as $transaction)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="font-medium text-red-600">
                                                    {{ $transaction->points ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700 capitalize">
                                                {{ $transaction->type }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                {{ $transaction->description }}
                                                {{ $transaction->patient_name ? ' - ' . $transaction->patient_name : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-sm text-gray-500 text-center">
                                            Tidak ada riwayat transaksi point
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
