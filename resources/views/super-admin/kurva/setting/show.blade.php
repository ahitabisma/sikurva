@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <x-export-button
                            url="{{ route('super-admin.kurva.setting.export', ['tableName' => $namaTabel, 'columnName' => $col]) }}"
                            text="Export Data {{ $namaTabel }}" />
                    </div>
                </div>

                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <!-- table header start -->
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            No
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Jenis Kelamin
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            {{ ucfirst($col) }}
                                        </p>
                                    </div>
                                </th>
                                @if (in_array($namaTabel, ['table9', 'table10', 'table11', 'table12']))
                                    @if (in_array($namaTabel, ['table9', 'table10', 'table11']))
                                        <th class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs">
                                                    Day
                                                </p>
                                            </div>
                                        </th>
                                    @endif
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Z (-3)
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Z (-2)
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Z (-1)
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Z (0)
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Z (1)
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Z (2)
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                Z (3)
                                            </p>
                                        </div>
                                    </th>
                                @else
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                L
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                M
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                S
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                SD4neg
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                SD3neg
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                SD2neg
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                SD1neg
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                SD0
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                SD1
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                SD2
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                SD3
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs">
                                                SD4
                                            </p>
                                        </div>
                                    </th>
                                    @if ($namaTabel === 'table8')
                                        <th class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs">
                                                    StDev
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs">
                                                    SD5Neg
                                                </p>
                                            </div>
                                        </th>
                                    @endif
                                @endif

                            </tr>
                        </thead>
                        <!-- table header end -->

                        <!-- table body start -->
                        <tbody class="divide-y divide-gray-100">
                            @forelse($datas as $data)
                                <tr>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="text-gray-500 text-theme-xs">
                                                {{ ($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ $data->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ $data->$col }}
                                            </p>
                                        </div>
                                    </td>
                                    @if (in_array($namaTabel, ['table9', 'table10', 'table11', 'table12']))
                                        @if (in_array($namaTabel, ['table9', 'table10', 'table11']))
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <p class="text-gray-700 text-theme-sm">{{ $data->days }}</p>
                                                </div>
                                            </td>
                                        @endif
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->z3neg }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->z2neg }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->z1neg }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->z0 }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->z1 }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->z2 }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->z3 }}</p>
                                            </div>
                                        </td>
                                    @else
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->l }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->m }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->s }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->sd4neg }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->sd3neg }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->sd2neg }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->sd1neg }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->sd0 }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->sd1 }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->sd2 }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->sd3 }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <p class="text-gray-700 text-theme-sm">{{ $data->sd4 }}</p>
                                            </div>
                                        </td>
                                        @if ($namaTabel === 'table8')
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <p class="text-gray-700 text-theme-sm">{{ $data->stdev }}</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <p class="text-gray-700 text-theme-sm">{{ $data->sd5neg }}</p>
                                                </div>
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ in_array($namaTabel, ['table9', 'table10', 'table11', 'table12']) ? 19 : ($namaTabel === 'table8' ? 19 : 17) }}"
                                        class="px-6 py-3 whitespace-nowrap">
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
                    {!! $datas->links() !!}
                </div>
            </div>
            <!-- Table Four -->
        </div>
    </div>
@endsection
