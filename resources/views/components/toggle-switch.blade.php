@props(['name', 'active' => true, 'labelActive' => 'Aktif', 'labelInactive' => 'Tidak Aktif'])

<div x-data="{ isActive: {{ $active ? '1' : '0' }} }" class="relative inline-flex items-center gap-2">
    <input type="hidden" name="{{ $name }}" x-model="isActive">

    <!-- Toggle Switch -->
    <div @click="isActive = !isActive" class="relative inline-block w-11 h-5 cursor-pointer">
        <div :class="isActive ? 'bg-blue-700' : 'bg-slate-300'"
            class="w-11 h-5 rounded-full transition-colors duration-300"></div>
        <div :class="isActive ? 'translate-x-6 border-blue-500' : 'border-blue-300'"
            class="absolute top-0 left-0 w-5 h-5 bg-white rounded-full border shadow-sm transition-transform duration-300">
        </div>
    </div>

    <!-- Label Text -->
    <span x-text="isActive ? '{{ $labelActive }}' : '{{ $labelInactive }}'" class="text-sm text-black"></span>
</div>
