@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <x-tambah-button url="{{ route('super-admin.testimoni.create') }}" text="Tambah Testimoni" />
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <form method="GET" action="{{ route('super-admin.testimoni.index') }}"
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
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
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
                                <th width="5%" class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            No
                                        </p>
                                    </div>
                                </th>
                                <th width="20%" class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            User
                                        </p>
                                    </div>
                                </th>
                                <th width="10%" class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Rating
                                        </p>
                                    </div>
                                </th>
                                <th width="50%" class="px-6 py-3 whitespace-nowrap ">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs ">
                                            Testimoni
                                        </p>
                                    </div>
                                </th>
                                <th width="15%" class="px-6 py-3 whitespace-nowrap">
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
                            @forelse ($testimonis as $testimoni)
                                <tr>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-500 text-theme-xs">
                                                {{ ($testimonis->currentPage() - 1) * $testimonis->perPage() + $loop->iteration }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ $testimoni->user_name }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <p
                                                    class="{{ $i <= $testimoni->rating ? 'text-yellow-400' : 'text-gray-300' }} text-theme-sm">
                                                    <i class="fa-solid fa-star"></i>
                                                </p>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-wrap">
                                        <div x-data="{ expanded: false, hasMore: false }" x-init="$nextTick(() => {
                                            hasMore = $refs.testimoni.scrollHeight > $refs.testimoni.clientHeight;
                                        })"
                                            class="flex flex-col text-center">

                                            <!-- Kontainer teks -->
                                            <p x-ref="testimoni" :class="expanded ? '' : 'line-clamp-2'"
                                                class="text-gray-700 text-theme-sm transition-all">
                                                {{ $testimoni->testimoni }}
                                            </p>

                                            <!-- Tombol See More -->
                                            <button x-show="hasMore" @click="expanded = !expanded"
                                                class="text-blue-500 text-xs font-semibold mt-1 focus:outline-none">
                                                <span x-text="expanded ? 'See Less' : 'See More'"></span>
                                            </button>
                                        </div>
                                    </td>


                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('super-admin.testimoni.edit', $testimoni->id) }}"
                                                type="button"><x-svg-edit /></a>
                                            <x-modal-delete title="Hapus Testimoni"
                                                message="Apakah Anda yakin ingin menghapus data testimoni ini?"
                                                confirmText="Hapus" cancelText="Batal"
                                                url="{{ route('super-admin.testimoni.destroy', $testimoni->id) }}" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-3 whitespace-nowrap">
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
                    {!! $testimonis->appends(request()->query())->links() !!}
                </div>
            </div>
            <!-- Table Four -->
        </div>
    </div>
@endsection
