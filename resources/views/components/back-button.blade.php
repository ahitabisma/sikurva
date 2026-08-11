@props(['url', 'text'])

<a href="{{ $url }}"
    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg  bg-gray-100 px-4 py-2.5 font-medium text-gray-500 hover:bg-gray-200 hover:text-gray-800 transition ease-in-out duration-300">
    <i class="fa-solid fa-arrow-left"></i> {{ $text }}
</a>
