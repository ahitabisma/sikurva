@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <!-- table header start -->
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            No
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Nama
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
                                            Action
                                        </p>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <!-- table header end -->

                        <!-- table body start -->
                        <tbody class="divide-y divide-gray-100">
                            @forelse($settings as $name => $settingGroup)
                                <tr>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-500 text-theme-xs">{{ $loop->iteration }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-700 text-theme-sm">{{ $name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            @foreach ($settingGroup as $item)
                                                <p
                                                    class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-{{ $item->type === 'bonus' ? 'success' : 'error' }}-50 text-{{ $item->type === 'bonus' ? 'success' : 'error' }}-600">
                                                    {{ $item->user_type ? ucfirst($item->user_type) . ':' : '' }}
                                                    {{ $item->type === 'bonus' ? '+' : '-' }}{{ $item->points }}
                                                </p>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <x-edit-button url="{{ route('super-admin.langganan.setting.edit', $name) }}"
                                                text="Edit" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-500 text-theme-xs">Tidak ada data</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <!-- table body end -->
                    </table>
                </div>

                {{-- <div class="border-t border-gray-200 px-6 py-4">
                    {!! $allSettings->links() !!}
                </div> --}}
            </div>
        </div>
    </div>
@endsection
