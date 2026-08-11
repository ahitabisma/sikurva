@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 mb-1.5']) }}>
    {{ $value ?? $slot }}
    @if ($attributes->has('required'))
        <span class="text-red-500">*</span>
    @endif
</label>
