@props(['url', 'text'])
<a href="{{ $url }}"
    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border  bg-orange-400 px-4 py-2.5 font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300"><i
        class="fa-solid fa-pen"></i>
    {{ $text }}</a>
