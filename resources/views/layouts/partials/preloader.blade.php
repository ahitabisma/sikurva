@if (Route::is('patient.preview', 'super-admin.patient.preview'))
    <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => { setTimeout(() => loaded = false, 500) })"
        class="fixed left-0 top-0 z-999999 flex flex-col h-screen w-screen items-center justify-center bg-white">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-blue-500 border-t-transparent">
        </div>
        <p class="mt-5">Sedang memuat halaman, harap tunggu...</p>
    </div>
@else
    <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => { setTimeout(() => loaded = false, 500) })"
        class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-blue-500 border-t-transparent">
        </div>
    </div>
@endif
