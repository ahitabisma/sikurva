<!-- filepath: d:\laragon\www\closing\ekurva\resources\views\components\push-custom-email-modal.blade.php -->
@props(['id' => 'push-custom-email-modal'])

<div x-data="{
    showModal: false,
    points: 0,
    pointsHeader: 0,
    pointsEmail: 0,
    actionCallback: null,
    hasCustomHeader: false,
    emailType: 'patient', // 'patient' or 'user'
    customEmail: '',
    patientEmail: '',
    userEmail: '',
    useCustomEmail: false,
    isSupportHeader: false,
    isSuperAdmin: false,
    displayName: '',

    open(points, callback, patientEmail, userEmail, hasCustomHeader = false, pointsHeader = 0, pointsEmail = 0, isSuperAdmin = false, isSupportHeader = false, displayName = '') {
        this.points = points;
        this.actionCallback = callback;
        this.patientEmail = patientEmail;
        this.userEmail = userEmail;
        this.hasCustomHeader = hasCustomHeader;
        this.pointsHeader = pointsHeader;
        this.pointsEmail = pointsEmail;
        this.isSuperAdmin = isSuperAdmin;
        this.isSupportHeader = isSupportHeader;
        this.displayName = displayName;

        // Auto-select user email if patient email isn't available
        this.emailType = patientEmail ? 'patient' : 'user';

        this.customEmail = '';
        this.useCustomEmail = false;
        this.showModal = true;
    },

    close() {
        this.showModal = false;
    },

    // Toggle radio selection when custom email is selected
    toggleCustomEmail() {
        if (this.useCustomEmail) {
            this.emailType = ''; // Deselect radio buttons
        } else {
            // If switching back from custom, select an available email
            if (this.patientEmail) {
                this.emailType = 'patient';
            } else if (this.userEmail) {
                this.emailType = 'user';
            }
        }
    },

    confirm() {
        if (typeof this.actionCallback === 'function') {
            let emailAddress = '';

            if (this.useCustomEmail) {
                emailAddress = this.customEmail;
            } else {
                emailAddress = this.emailType === 'patient' ? this.patientEmail : this.userEmail;
            }

            this.actionCallback(emailAddress, this.displayName);
        }
        this.close();
    },

    // Handle keyboard events
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
    x-on:open-push-custom-email-modal.window="open(
    $event.detail.points,
    $event.detail.callback,
    $event.detail.patientEmail,
    $event.detail.userEmail,
    $event.detail.hasCustomHeader,
    $event.detail.pointsHeader,
    $event.detail.pointsEmail,
    $event.detail.isSuperAdmin,
    $event.detail.isSupportHeader,
    $event.detail.displayName
)"
    x-on:keydown.window="handleKeydown($event)" id="{{ $id }}">

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
                    <h3 class="text-lg font-semibold text-gray-800">Push Email Custom</h3>
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
                        Anda akan mengirim grafik via email. Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <!-- Admin message with support header -->
                    <p x-show="!isSuperAdmin && isSupportHeader && (!displayName || displayName.trim() === '') && !useCustomEmail"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && isSupportHeader && (displayName && displayName.trim() !== '') && !useCustomEmail"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin. Karena
                        Anda sudah menambahkan email sender display name maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsEmail"></strong> poin sehingga total point yang akan
                        digunakan adalah <strong class='text-blue-500' x-text="points + pointsEmail"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <!-- Admin message without custom header -->
                    <p x-show="!isSuperAdmin && !hasCustomHeader && !isSupportHeader && (!displayName || displayName.trim() === '') && !useCustomEmail"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class="text-blue-500" x-text="points"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && !hasCustomHeader && !isSupportHeader && (displayName && displayName.trim() !== '') && !useCustomEmail"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin. Karena
                        Anda sudah menambahkan email sender display name maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsEmail"></strong> poin sehingga total point yang akan
                        digunakan adalah <strong class='text-blue-500' x-text="points + pointsEmail"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <!-- Admin message with custom header but without support header -->
                    <p x-show="!isSuperAdmin && hasCustomHeader && !isSupportHeader && (displayName && displayName.trim() !== '') && !useCustomEmail"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Karena Anda sudah menambahkan header maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsHeader"></strong> poin dan tambahan sender display name
                        sebesar <strong class='text-blue-500' x-text="pointsEmail"></strong> sehingga total point yang
                        akan digunakan adalah <strong class='text-blue-500'
                            x-text="points + pointsHeader + pointsEmail"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && hasCustomHeader && !isSupportHeader && (!displayName || displayName.trim() === '') && !useCustomEmail"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Karena Anda sudah menambahkan header maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsHeader"></strong> poin sehingga total point yang akan
                        digunakan adalah <strong class='text-blue-500' x-text="points + pointsHeader"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <!-- Custom email additional cost messages -->
                    <p x-show="!isSuperAdmin && useCustomEmail && (!displayName || displayName.trim() === '') && !hasCustomHeader"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Karena Anda menggunakan email custom maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="customEmailPoint"></strong> poin sehingga total point yang
                        akan
                        digunakan adalah <strong class='text-blue-500' x-text="points + customEmailPoint"></strong>
                        poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && useCustomEmail && (displayName && displayName.trim() !== '') && !hasCustomHeader"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Karena Anda sudah menambahkan email sender display name maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsEmail"></strong> poin dan email custom sebesar <strong
                            class='text-blue-500' x-text="customEmailPoint"></strong> poin sehingga total point yang
                        akan
                        digunakan adalah <strong class='text-blue-500'
                            x-text="points + pointsEmail + customEmailPoint"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && useCustomEmail && hasCustomHeader && (!displayName || displayName.trim() === '')"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Karena Anda sudah menambahkan header maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsHeader"></strong> poin dan email custom sebesar
                        <strong class='text-blue-500' x-text="customEmailPoint"></strong> poin sehingga total point
                        yang akan
                        digunakan adalah <strong class='text-blue-500'
                            x-text="points + pointsHeader + customEmailPoint"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <p x-show="!isSuperAdmin && useCustomEmail && hasCustomHeader && (displayName && displayName.trim() !== '')"
                        class="text-gray-600 mb-4">
                        Tindakan ini membutuhkan <strong class='text-blue-500' x-text="points"></strong> poin.
                        Karena Anda sudah menambahkan header maka akan ada tambahan sebesar <strong
                            class='text-blue-500' x-text="pointsHeader"></strong> poin, sender display name sebesar
                        <strong class='text-blue-500' x-text="pointsEmail"></strong> poin, dan email custom sebesar
                        <strong class='text-blue-500' x-text="customEmailPoint"></strong> poin sehingga total point
                        yang akan
                        digunakan adalah <strong class='text-blue-500'
                            x-text="points + pointsHeader + pointsEmail + customEmailPoint"></strong> poin.
                        Apakah Anda yakin ingin melanjutkan?
                    </p>

                    <div class="mt-6">
                        <p class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih email tujuan:
                        </p>

                        <div class="space-y-3">
                            <!-- Patient Email Option -->
                            <label class="flex items-center space-x-3"
                                :class="{
                                    'cursor-pointer': patientEmail && !useCustomEmail,
                                    'opacity-50': !patientEmail || useCustomEmail
                                }">
                                <input type="radio" name="emailType" value="patient" x-model="emailType"
                                    class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                                    :disabled="!patientEmail || useCustomEmail">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">Email Pasien</span>
                                    <span class="text-xs text-gray-500"
                                        x-text="patientEmail ? patientEmail : 'Tidak tersedia'"></span>
                                </div>
                            </label>

                            <!-- User Email Option -->
                            <label class="flex items-center space-x-3"
                                :class="{
                                    'cursor-pointer': userEmail && !useCustomEmail,
                                    'opacity-50': !userEmail || useCustomEmail
                                }">
                                <input type="radio" name="emailType" value="user" x-model="emailType"
                                    class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                                    :disabled="!userEmail || useCustomEmail">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">Email Anda</span>
                                    <span class="text-xs text-gray-500"
                                        x-text="userEmail ? userEmail : 'Tidak tersedia'"></span>
                                </div>
                            </label>

                            <!-- Custom Email Option -->
                            <div class="pt-3 border-t border-gray-100">
                                <label class="flex items-center space-x-3 cursor-pointer mb-2">
                                    <input type="checkbox" x-model="useCustomEmail" @change="toggleCustomEmail()"
                                        class="form-checkbox h-4 w-4 text-blue-600 rounded transition duration-150 ease-in-out">
                                    <span class="text-sm font-medium text-gray-900">Gunakan email custom</span>
                                </label>

                                <div x-show="useCustomEmail" class="mt-2">
                                    <x-text-input type="email" x-model="customEmail" class="block w-full"
                                        placeholder="contoh@example.com" />
                                    <p x-show="!isSuperAdmin" class="text-xs text-gray-500 mt-1">
                                        Jika Anda menggunakan email custom, Anda akan dikenakan biaya <strong
                                            class="text-blue-500" x-text="customEmailPoint"></strong> poin tambahan.
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
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-200"
                        :disabled="(useCustomEmail && !customEmail) || (!useCustomEmail && !emailType)">
                        Kirim Email
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
