@props(['url'])

<a href="{{ $url }}"
    class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-800">
    {{ $slot }}
</a>
