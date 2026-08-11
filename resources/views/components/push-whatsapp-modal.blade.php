<!-- filepath: d:\laragon\www\closing\ekurva\resources\views\components\push-whatsapp-modal.blade.php -->
@props(['id' => 'push-whatsapp-modal'])

<div x-data="{
    showModal: false,
    points: 0,
    pointsHeader: 0,
    actionCallback: null,
    hasCustomHeader: false,
    waNumberType: 'patient', // 'patient' or 'user'
    customWaNumber: '',
    patientWaNumber: '',
    userWaNumber: '',
    useCustomNumber: false,
    isSupportHeader: false,
    isSuperAdmin: false,

    open(points, callback, patientWaNumber, userWaNumber, hasCustomHeader = false, pointsHeader = 0, isSuperAdmin = false, isSupportHeader = false) {
        this.points = points;
        this.actionCallback = callback;
        this.patientWaNumber = patientWaNumber;
        this.userWaNumber = userWaNumber.replace(/^0/, '62');
        this.hasCustomHeader = hasCustomHeader;
        this.pointsHeader = pointsHeader;
        this.isSuperAdmin = isSuperAdmin;
        this.isSupportHeader = isSupportHeader;

        // Auto-select user number if patient number isn't available
        this.waNumberType = patientWaNumber ? 'patient' : 'user';

        this.customWaNumber = '';
        this.useCustomNumber = false;
        this.showModal = true;
    },

    close() {
        this.showModal = false;
    },

    // Toggle radio selection when custom number is selected
    toggleCustomNumber() {
        if (this.useCustomNumber) {
            this.waNumberType = ''; // Deselect radio buttons
        } else {
            // If switching back from custom, select an available number
            if (this.patientWaNumber) {
                this.waNumberType = 'patient';
            } else if (this.userWaNumber) {
                this.waNumberType = 'user';
            }
        }
    },

    confirm() {
        if (typeof this.actionCallback === 'function') {
            let waNumber = '';

            if (this.useCustomNumber) {
                waNumber = '62' + this.customWaNumber;
            } else {
                waNumber = this.waNumberType === 'patient' ? this.patientWaNumber : this.userWaNumber;
            }

            this.actionCallback(waNumber);
        }
        this.close();
    },
    // Inside the handleKeydown function, add event.preventDefault()
    handleKeydown(event) {
        if (this.showModal && event.key === 'Enter') {
            event.preventDefault(); // Prevent the event from bubbling
            this.confirm();
        } else if (this.showModal && event.key === 'Escape') {
            event.preventDefault(); // Prevent the event from bubbling
            this.close();
        }
    },
    'customWaPoint': {{ $pointSettings->where('name', 'NO-WA-CUSTOM')->value('points') ?? 0 }},
}"
    x-on:open-push-whatsapp-modal.window="open(
    $event.detail.points,
    $event.detail.callback,
    $event.detail.patientWaNumber,
    $event.detail.userWaNumber,
    $event.detail.hasCustomHeader,
    $event.detail.pointsHeader,
    $event.detail.isSuperAdmin,
    $event.detail.isSupportHeader
)"
    x-on:keydown.window="handleKeydown($event)" id="{{ $id }}" id="{{ $id }}">

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
                    <h3 class="text-lg font-semibold text-gray-800">Push WhatsApp</h3>
                    <button @click="close()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-6">
                    <!-- Super Admin message (no points) -->
                    <p x-show="isSuperAdmin" class="text-gray-600 mb-4">
                        Anda akan mengirim grafik via WhatsApp. Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && isSupportHeader" class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class="text-blue-500" x-text="points"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && !hasCustomHeader && !isSupportHeader" class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class="text-blue-500" x-text="points"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && hasCustomHeader && !isSupportHeader" class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Karena Anda sudah menambahkan header maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsHeader"></strong> poin sehingga total point yang akan
                        digunakan adalah <strong class='text-blue-500' x-text="points + pointsHeader"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <div class="mt-6">
                        <p class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih nomor WhatsApp tujuan:
                        </p>

                        <div class="space-y-3">
                            <!-- Patient Number Option -->
                            <label class="flex items-center space-x-3"
                                :class="{
                                    'cursor-pointer': patientWaNumber && !useCustomNumber,
                                    'opacity-50': !
                                        patientWaNumber || useCustomNumber
                                }">
                                <input type="radio" name="waNumberType" value="patient" x-model="waNumberType"
                                    class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                                    :disabled="!patientWaNumber || useCustomNumber">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">Nomor Pasien</span>
                                    <span class="text-xs text-gray-500"
                                        x-text="patientWaNumber ? '+' + patientWaNumber : 'Tidak tersedia'"></span>
                                </div>
                            </label>

                            <!-- User Number Option -->
                            <label class="flex items-center space-x-3"
                                :class="{
                                    'cursor-pointer': userWaNumber && !useCustomNumber,
                                    'opacity-50': !userWaNumber ||
                                        useCustomNumber
                                }">
                                <input type="radio" name="waNumberType" value="user" x-model="waNumberType"
                                    class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                                    :disabled="!userWaNumber || useCustomNumber">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">Nomor Anda</span>
                                    <span class="text-xs text-gray-500"
                                        x-text="userWaNumber ? '+' + userWaNumber : 'Tidak tersedia'"></span>
                                </div>
                            </label>

                            <!-- Custom Number Option -->
                            <div class="pt-3 border-t border-gray-100">
                                <label class="flex items-center space-x-3 cursor-pointer mb-2">
                                    <input type="checkbox" x-model="useCustomNumber" @change="toggleCustomNumber()"
                                        class="form-checkbox h-4 w-4 text-blue-600 rounded transition duration-150 ease-in-out">
                                    <span class="text-sm font-medium text-gray-900">Gunakan nomor custom</span>
                                </label>

                                <div x-show="useCustomNumber" class="mt-2">
                                    <div class="flex items-center">
                                        <span
                                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg h-11">
                                            +62
                                        </span>
                                        <x-text-input type="text" x-model="customWaNumber"
                                            class="rounded-l-none block w-full"
                                            placeholder="8123456789 (tanpa angka 0 di depan)" maxlength="15" />
                                    </div>
                                    <p x-show="!isSuperAdmin" class="text-xs text-gray-500 mt-1">
                                        Jika Anda menggunakan nomor custom, Anda akan dikenakan biaya <strong
                                            class="text-blue-500" x-text="customWaPoint"></strong> poin tambahan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="close()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200">
                        Batal
                    </button>
                    <button @click="confirm()"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-lg hover:bg-green-600 transition duration-200"
                        :disabled="(useCustomNumber && !customWaNumber) || (!useCustomNumber && !waNumberType)">
                        Kirim WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
