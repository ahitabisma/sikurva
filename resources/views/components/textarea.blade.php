@props(['disabled' => false, 'rows' => 3])

<textarea rows="{{ $rows }}" {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => 'w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10']) }}>{{ $slot }}</textarea>
