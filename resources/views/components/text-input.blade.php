@props(['disabled' => false, 'placeholder' => '', 'required' => false])

<input @disabled($disabled)
    {{ $attributes->merge([
        'class' =>
            'h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10 disabled:bg-gray-100 read-only:bg-gray-100 read-only:cursor-not-allowed',
        'placeholder' => $placeholder,
        'required' => $required,
    ]) }}>
