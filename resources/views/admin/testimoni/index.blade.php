@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        @if ($testimoni)
                            <x-edit-button url="{{ route('testimoni.edit') }}" text="Edit Testimoni" />

                            <x-modal-delete textBtn="Hapus Testimoni" title="Hapus Testimoni"
                                message="Apakah Anda yakin ingin menghapus data testimoni ini?" confirmText="Hapus"
                                cancelText="Batal" url="{{ route('testimoni.destroy') }}" />
                        @else
                            <x-tambah-button url="{{ route('testimoni.create') }}" text="Tambah Testimoni" />
                        @endif
                    </div>
                </div>

                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <!-- table header start -->
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr width="5%">
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
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
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Rating
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Testimoni Anda
                                        </p>
                                    </div>
                                </th>
                                <th width="10%" class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Created At
                                        </p>
                                    </div>
                                </th>
                                <th width="10%" class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Updated At
                                        </p>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <!-- table header end -->

                        <!-- table body start -->
                        <tbody class="divide-y divide-gray-100">
                            @if ($testimoni)
                                <tr>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                1
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
                                        })" class="flex flex-col">

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
                                        <div class="flex items-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ $testimoni->created_at }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ $testimoni->updated_at }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-500 text-theme-sm">
                                                Belum ada testimoni yang ditambahkan
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <!-- table body end -->
                    </table>
                </div>
            </div>
            <!-- Table Four -->
        </div>
    </div>
@endsection
