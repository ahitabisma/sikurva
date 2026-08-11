<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border justify-center bg-gray-400 px-4 py-2.5 font-medium text-white hover:bg-gray-500 transition ease-in-out duration-300']) }}>
    {{ $slot }}
</button>
