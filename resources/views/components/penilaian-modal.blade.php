@props(['id' => 'penilaian-modal'])

<div x-data="{
    showModal: false,
    title: 'Penilaian',
    points: 0,
    message: '',
    actionCallback: null,
    checkboxToggle: false,
    skipConfirmation: false,

    open(points, callback, customMessage = null) {
        // Check if Laravel has set the cookie
        const hasCookie = {{ Cookie::has('skip_confirm') ? 'true' : 'false' }};

        // If cookie exists, skip showing the modal
        if (hasCookie) {
            if (typeof callback === 'function') {
                callback(true); // Pass true to indicate it was auto-confirmed
            }
            return; // Don't show modal
        }

        this.points = points;
        this.actionCallback = callback;
        this.message = customMessage || `Tindakan ini membutuhkan <strong class='text-blue-500'>${points} poin</strong>. Apakah Anda yakin ingin melanjutkan?`;
        this.skipConfirmation = false;
        this.showModal = true;

        // Set a small timeout to ensure modal is rendered before setting focus
        setTimeout(() => {
            this.$refs.confirmButton.focus();
        }, 50);
    },

    close() {
        this.showModal = false;
    },

    handleCheckboxChange() {
        this.checkboxToggle = !this.checkboxToggle;
    },

    confirm() {
        if (typeof this.actionCallback === 'function') {
            this.actionCallback(this.skipConfirmation);
        }
        this.close();
    },

    handleKeydown(event) {
        if (this.showModal && event.key === 'Enter') {
            event.preventDefault();
            this.confirm();
        } else if (this.showModal && event.key === 'Escape') {
            event.preventDefault();
            this.close();
        }
    }
}"
    x-on:open-penilaian-modal.window="open($event.detail.points, $event.detail.callback, $event.detail.message)"
    x-on:keydown.window="handleKeydown($event)" id="{{ $id }}">
    <!-- Modal Backdrop -->
    <div x-show="showModal" x-cloak
        class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
        style="z-index: 99999 !important" x-transition:enter="transition ease duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md mx-auto"
            x-transition:enter="transition ease duration-300" x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" @click.outside="close()" @click.stop>

            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Penilaian</h3>
                    <button @click="close()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-6">
                    <p class="text-gray-600" x-html="message">
                    </p>
                </div>

                <!-- Don't show this again checkbox -->
                <div class="mb-4" @click.stop>
                    <label for="skipPenilaianConfirmation" class="flex cursor-pointer items-center text-sm select-none"
                        @click.stop>
                        <div class="relative" @click.stop>
                            <input type="checkbox" name="skipConfirmation" id="skipPenilaianConfirmation"
                                class="sr-only" x-model="skipConfirmation" @click.stop>

                            <div :class="skipConfirmation ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300'"
                                class="hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] border-gray-300"
                                @click.stop>
                                <span :class="skipConfirmation ? '' : 'opacity-0'" class="" @click.stop>
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white"
                                            stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500" @click.stop>
                            Don't show this message again
                        </span>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="close()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200">
                        Tidak
                    </button>
                    <button @click="confirm()" x-ref="confirmButton"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-200">
                        Ya, lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
