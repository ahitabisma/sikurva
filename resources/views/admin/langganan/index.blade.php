@extends('layouts.tailadmin')

@section('content')
    <div x-data="subscriptionList()">
        <div class="rounded-2xl border border-gray-200 bg-white">
            <div class="border-t border-gray-100 p-5 sm:p-6">
                <!-- Table Four -->
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                    <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <x-tambah-button url="{{ route('langganan.create') }}" text="Beli Langganan" />
                        </div>
                    </div>

                    <div class="max-w-full overflow-x-auto custom-scrollbar">
                        <table class="min-w-full">
                            <!-- table header start -->
                            <thead class="border-gray-100 border-y bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <div class="flex items-center gap-3">
                                                <div>
                                                    <span class="block font-medium text-gray-500 text-theme-xs">
                                                        No
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Order ID
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Nama Paket
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Point
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Masa Aktif
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Tanggal Pembelian
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Status
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Sisa Poin
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Action
                                            </p>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <!-- table header end -->

                            <!-- table body start -->
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($langganans as $langganan)
                                    <tr>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <p class="text-gray-700 text-theme-sm">
                                                    {{ ($langganans->currentPage() - 1) * $langganans->perPage() + $loop->iteration }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <p class="text-gray-700 text-theme-sm">
                                                    {{ $langganan->order_id ?? '-' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <p class="text-gray-700 text-theme-sm">
                                                    {{ $langganan->subscription_name ?? '-' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <p class="text-gray-700 text-theme-sm">
                                                    +{{ $langganan->point }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <p class="text-gray-700 text-theme-sm font-semibold">
                                                    {{ $langganan->expired_at ? \Carbon\Carbon::parse($langganan->expired_at)->format('d F Y') : 'Belum Aktif' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <p class="text-gray-700 text-theme-sm">
                                                    {{ \Carbon\Carbon::parse($langganan->created_at)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                @if ($langganan->status == 'paid')
                                                    <p
                                                        class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600">
                                                        Paid
                                                    </p>
                                                @elseif ($langganan->status == 'pending')
                                                    <p
                                                        class="rounded-full bg-warning-50 px-2 py-0.5 text-theme-xs font-medium text-warning-600">
                                                        Pending
                                                    </p>
                                                @elseif ($langganan->status == 'cancelled')
                                                    <p
                                                        class="rounded-full bg-error-50 px-2 py-0.5 text-theme-xs font-medium text-error-600">
                                                        Cancelled
                                                    </p>
                                                @elseif ($langganan->status == 'expired')
                                                    <p
                                                        class="rounded-full bg-gray-50 px-2 py-0.5 text-theme-xs font-medium text-gray-600">
                                                        Expired
                                                    </p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <p class="text-gray-700 text-theme-sm">
                                                    {{ $langganan->sisa_point ?? '-' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                @if ($langganan->status == 'pending' && $langganan->snap_token)
                                                    <button
                                                        @click="openPayment({{ $langganan->id }}, '{{ $langganan->snap_token }}')"
                                                        class="flex items-center justify-center gap-1 rounded-lg border border-blue-500 bg-white px-3 py-1 text-sm font-medium text-blue-700 shadow-theme-xs transition duration-300 ease-in-out hover:bg-blue-50">
                                                        Bayar Sekarang
                                                    </button>
                                                @elseif ($langganan->status == 'pending')
                                                    <x-modal-delete textBtn="Cancel" title="Cancel Langganan"
                                                        message="Apakah Anda yakin ingin membatalkan langganan ini?"
                                                        confirmText="Cancel" cancelText="Batal" :isDelete="false"
                                                        url="{{ route('langganan.cancel', $langganan->id) }}" />
                                                @else
                                                    <a href="{{ route('langganan.show', $langganan->id) }}"
                                                        class="flex items-center justify-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-700 shadow-theme-xs transition duration-300 ease-in-out hover:bg-gray-50 hover:text-gray-800">
                                                        Lihat
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <p class="text-gray-500 text-theme-xs">
                                                    Tidak ada data
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <!-- table body end -->
                        </table>
                    </div>

                    <div class="border-t border-gray-200 px-6 py-4">
                        {!! $langganans->links() !!}
                    </div>
                </div>
                <!-- Table Four -->
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Include Midtrans Snap JS -->
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    <script>
        function subscriptionList() {
            return {
                openPayment(subscriptionId, snapToken) {
                    if (!snapToken) {
                        alert('Token pembayaran tidak valid');
                        return;
                    }

                    // Open Midtrans Snap payment popup
                    window.snap.pay(snapToken, {
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
                }
            }
        }
    </script>
@endsection
