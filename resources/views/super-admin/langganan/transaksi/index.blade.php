@extends('layouts.tailadmin')

@section('content')
    <div x-data="subscriptionList()">
        {{-- Metric Start --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4 mb-5">
            @foreach ($subscriptions as $subscription)
                <!-- Metric Item Start -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <p class="text-theme-sm text-gray-500">
                        {{ $subscription->name }}
                    </p>

                    <div class="mt-3 flex items-end justify-between">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-800">
                                {{ $subscription->total_users }}
                            </h4>
                        </div>
                    </div>
                </div>
                <!-- Metric Item End -->
            @endforeach
        </div>
        {{-- Metric End --}}

        <div class="rounded-2xl border border-gray-200 bg-white">
            <div class="border-t border-gray-100 p-5 sm:p-6">
                <!-- Table Four -->
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                    <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <x-tambah-button url="{{ route('super-admin.langganan.transaksi.create') }}"
                                text="Tambah Langganan" />
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <form method="GET" action="{{ route('super-admin.langganan.transaksi.index') }}"
                                class="flex flex-col gap-2 lg:flex-row">
                                <div class="relative">
                                    <span class="absolute -translate-y-1/2 pointer-events-none top-1/2 left-4">
                                        <svg class="fill-gray-500" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                                fill=""></path>
                                        </svg>
                                    </span>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search..."
                                        class="shadow-theme-xs focus:border-blue-300 focus:ring-blue-500/10 h-10 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px]">

                                </div>

                                <x-search-button>Cari</x-search-button>
                            </form>
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
                                                Nama User/Instansi
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Paket
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Metode
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Tanggal
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
                                                    {{ $langganan->user_name ? $langganan->user_name : ($langganan->instansi_name ? $langganan->instansi_name : '-') }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center flex-col">
                                                <p class="text-gray-700 text-theme-sm">
                                                    {{ $langganan->subscription_name ?? '-' }}
                                                </p>

                                                <p>
                                                    (<span class="text-green-500 text-theme-sm">
                                                        +{{ $langganan->point }} poin
                                                    </span>)
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                @if ($langganan->snap_token)
                                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-50 text-blue-600">
                                                        Midtrans
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-50 text-gray-600">
                                                        Manual
                                                    </span>
                                                @endif
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
                                            <div class="flex items-center justify-center flex-col">
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

                                                <div x-data="{ isModalOpen: false }">
                                                    <!-- Button to open the modal -->
                                                    @if ($langganan->status == 'pending')
                                                        <button type="button" @click="isModalOpen = true"
                                                            class="text-blue-500 text-theme-xs hover:underline transition mt-1">
                                                            Ubah Status
                                                        </button>
                                                    @endif

                                                    <!-- Modal -->
                                                    <div x-show="isModalOpen" x-cloak
                                                        class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg z-[99999]">

                                                        <div @click.outside="isModalOpen = false"
                                                            class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-lg">

                                                            <!-- Close Button -->
                                                            <button @click="isModalOpen = false"
                                                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-700">
                                                                ✖
                                                            </button>

                                                            <!-- Modal Content -->
                                                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Ubah Status
                                                                Transaksi</h4>
                                                            <p class="text-sm text-gray-500">Pilih status yang ingin Anda
                                                                tetapkan untuk transaksi ini.</p>

                                                            <div class="flex justify-end mt-5 gap-3">
                                                                <!-- Cancel Button -->
                                                                <button @click="isModalOpen = false"
                                                                    class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                                                                    Batal
                                                                </button>

                                                                <!-- Paid Form -->
                                                                <form
                                                                    action="{{ route('super-admin.langganan.transaksi.update-status', $langganan->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" value="paid">
                                                                    <button type="submit"
                                                                        class="px-4 py-2 text-sm text-white bg-green-500 rounded-lg hover:bg-green-600">
                                                                        Paid
                                                                    </button>
                                                                </form>

                                                                <!-- Cancelled Form -->
                                                                <form
                                                                    action="{{ route('super-admin.langganan.transaksi.update-status', $langganan->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status"
                                                                        value="cancelled">
                                                                    <button type="submit"
                                                                        class="px-4 py-2 text-sm text-white bg-red-500 rounded-lg hover:bg-red-600">
                                                                        Cancelled
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a
                                                    href="{{ route('super-admin.langganan.transaksi.show', $langganan->id) }}">
                                                    <x-svg-show />
                                                </a>

                                                {{-- <a href="{{ route('super-admin.langganan.transaksi.edit', $langganan->id) }}"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    <x-svg-edit />
                                                </a> --}}

                                                <x-modal-delete title="Hapus Transaksi"
                                                    message="Apakah Anda yakin ingin menghapus data transaksi ini?"
                                                    confirmText="Hapus" cancelText="Batal"
                                                    url="{{ route('super-admin.langganan.transaksi.destroy', $langganan->id) }}" />
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-3 whitespace-nowrap">
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
