<!-- filepath: c:\laragon\www\work\ekurva\resources\views\components\push-email-modal.blade.php -->
@props(['id' => 'push-email-modal'])

<div x-data="{
    showModal: false,
    points: 0,
    pointsHeader: 0,
    pointsEmail: 0,
    displayName: '',
    actionCallback: null,
    hasCustomHeader: false,
    isSupportHeader: false,
    isSuperAdmin: false,
    skipConfirmation: false,

    open(points, callback, hasCustomHeader = false, pointsHeader = 0, pointsEmail = 0, isSuperAdmin = false, isSupportHeader = false, displayName = '') {
        // Check if Laravel has set the cookie
        const hasCookie = {{ Cookie::has('skip_confirm') ? 'true' : 'false' }};

        if (hasCookie) {
            if (typeof callback === 'function') {
                callback(displayName);
            }
            return; // Don't show modal
        }

        this.isSupportHeader = isSupportHeader;
        this.points = points;
        this.actionCallback = callback;
        this.hasCustomHeader = hasCustomHeader;
        this.pointsHeader = pointsHeader;
        this.pointsEmail = pointsEmail;
        this.isSuperAdmin = isSuperAdmin;
        this.displayName = displayName;
        this.skipConfirmation = false;
        this.showModal = true;
    },

    close() {
        this.showModal = false;
    },

    confirm() {
        // We'll pass the skipConfirmation value to backend via the AJAX request
        if (typeof this.actionCallback === 'function') {
            // Here we need to modify your existing action callback
            // to include the skipConfirmation parameter
            this.actionCallback(this.displayName, this.skipConfirmation);
        }
        this.close();
    },

    // Inside the handleKeydown function
    handleKeydown(event) {
        if (this.showModal && event.key === 'Enter') {
            event.preventDefault();
            this.confirm();
        } else if (this.showModal && event.key === 'Escape') {
            event.preventDefault();
            this.close();
        }
    },
    'customEmailPoint': {{ $pointSettings->where('name', 'EMAIL-CUSTOM')->value('points') ?? 0 }},
}"
    x-on:open-push-email-modal.window="open(
        $event.detail.points,
        $event.detail.callback,
        $event.detail.hasCustomHeader,
        $event.detail.pointsHeader,
        $event.detail.pointsEmail,
        $event.detail.isSuperAdmin,
        $event.detail.isSupportHeader,
        $event.detail.displayName,
    );"
    x-on:keydown.window="handleKeydown($event)" id="{{ $id }}">
    <!-- Modal content -->
    <!-- Modal Backdrop -->
    <div x-show="showModal" x-cloak
        class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
        style="z-index: 99999 !important" x-transition:enter="transition ease duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md mx-auto w-full"
            x-transition:enter="transition ease duration-300" x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" @click.outside="close()">

            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Push Email</h3>
                    <button @click="close()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-3">
                    {{-- <span x-text="displayName"></span> --}}
                    <!-- Super Admin message (no points) -->
                    <p x-show="isSuperAdmin" class="text-gray-600 mb-4">
                        Anda akan mengirim grafik via email. Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <!-- Admin message with custom header and support header -->
                    <p x-show="!isSuperAdmin && isSupportHeader && (!displayName && displayName.trim() === '')"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && isSupportHeader && (displayName && displayName.trim() !== '')"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin. Karena
                        Anda sudah menambahkan email sender display name maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsEmail"></strong> poin sehingga total point yang akan
                        digunakan adalah <strong class='text-blue-500' x-text="points + pointsEmail"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <!-- Admin message without custom header -->
                    <p x-show="!isSuperAdmin && !hasCustomHeader && !isSupportHeader && (!displayName && displayName.trim() === '')"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class="text-blue-500" x-text="points"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && !hasCustomHeader && !isSupportHeader && (displayName && displayName.trim() !== '')"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin. Karena
                        Anda sudah menambahkan email sender display name maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsEmail"></strong> poin sehingga total point yang akan
                        digunakan adalah <strong class='text-blue-500' x-text="points + pointsEmail"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <!-- Admin message with custom header but without support header -->
                    <p x-show="!isSuperAdmin && hasCustomHeader && !isSupportHeader && (displayName && displayName.trim() !== '')"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Karena Anda sudah menambahkan header maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsHeader"></strong> poin dan tambahan sender display name
                        sebesar <strong class='text-blue-500' x-text="pointsEmail"></strong> sehingga total point yang
                        akan
                        digunakan adalah <strong class='text-blue-500'
                            x-text="points + pointsHeader + pointsEmail"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && hasCustomHeader && !isSupportHeader && (!displayName && displayName.trim() === '')"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Karena Anda sudah menambahkan header maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsHeader"></strong> poin sehingga total point yang akan
                        digunakan adalah <strong class='text-blue-500'
                            x-text="points + pointsHeader + pointsEmail"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>
                </div>

                <div x-data="{ checkboxToggle: false }" class="mb-4">
                    <label for="skipConfirmation" class="flex cursor-pointer items-center text-sm select-none ">
                        <div class="relative">
                            <input type="checkbox" name="skipConfirmation" x-model="skipConfirmation" id="skipConfirmation" class="sr-only"
                                @change="checkboxToggle = !checkboxToggle" required>
                            <div :class="checkboxToggle ? 'border-brand-500 bg-brand-500' :
                                'bg-transparent border-gray-300 '"
                                class="hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] border-brand-500 bg-brand-500">
                                <span :class="checkboxToggle ? '' : 'opacity-0'" class="">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white"
                                            stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">
                            Don't show this message again
                        </span>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="close()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200">
                        Batal
                    </button>
                    <button @click="confirm()"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-200">
                        Kirim Email
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
