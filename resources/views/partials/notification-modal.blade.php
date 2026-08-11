{{-- filepath: c:\laragon\www\work\tumbuh-kembang\resources\views\partials\notification-modal.blade.php --}}
<div id="notification-modal"
    class="hidden fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
    style="z-index: 99999;">
    <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-lg">
        <!-- Close Button (for failure case) -->
        <button id="modal-close-button" class="hidden absolute right-3 top-3 text-gray-400 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <!-- Title -->
        <h4 id="modal-title" class="text-lg font-semibold text-gray-800 mb-4"></h4>
        <!-- Message -->
        <div id="modal-message" class="text-sm text-gray-500" style="white-space: pre-line;"></div>
        <!-- Button -->
        <div class="flex justify-end mt-5">
            <button id="modal-action-button" class="px-4 py-2 text-sm rounded-lg"></button>
        </div>
    </div>
</div>

<div x-data="modalHandler()" id="confirmation-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-modal="true"
    role="dialog">
    <div class="flex min-h-screen items-center justify-center px-4 text-center sm:block sm:p-0">
        <div x-show="open" @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
        </div>

        <div x-show="open"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="title"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="message"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button @click="handleAction()" type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Confirm
                </button>
                <button @click="closeModal()" type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function modalHandler() {
        return {
            open: false,
            title: '',
            message: '',
            actionContext: null,
            actionFunction: null,

            openModal(title, message, actionContext, actionFunction) {
                this.open = true;
                this.title = title;
                this.message = message;
                this.actionContext = actionContext;
                this.actionFunction = actionFunction;
                document.getElementById('confirmation-modal').classList.remove('hidden');
            },

            closeModal() {
                this.open = false;
                document.getElementById('confirmation-modal').classList.add('hidden');
            },

            handleAction() {
                if (this.actionFunction && this.actionContext) {
                    // Call the function with the preserved context
                    this.actionFunction.call(this.actionContext);
                }
                this.closeModal();
            }
        };
    }
</script>
