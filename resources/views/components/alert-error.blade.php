@props(['title'])

<div class="rounded-xl border border-error-500 bg-error-50 p-3" x-data="{ show: true }" x-show="show" x-transition
    x-init="setTimeout(() => show = false, 2000)">
    <div class="flex items-start gap-3">
        <div class="-mt-0.5 text-error-500">
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </div>

        <div>
            <h4 class="mb-1 text-sm font-semibold text-gray-800">
                {{ $title }}
            </h4>
        </div>
    </div>
</div>
