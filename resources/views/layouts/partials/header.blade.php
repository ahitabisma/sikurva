<header x-data="{ menuToggle: false, isReferralModalOpen: false }" x-init="isReferralModalOpen = false, menuToggle = false"
    class="sticky top-0 z-99999 flex w-full border-gray-200 bg-white lg:border-b shadow">
    <div class="flex grow flex-col items-center justify-between lg:flex-row lg:px-6">
        <div
            class="flex w-full items-center justify-between gap-2 border-b border-gray-200 px-3 py-3 sm:gap-4 lg:justify-normal lg:border-b-0 lg:px-0 lg:py-4">
            <!-- Hamburger Toggle BTN -->
            <button :class="sidebarToggle ? 'md:hidden' : ''"
                class="z-99999 flex h-10 w-10 items-center justify-center rounded-lg border-gray-200 text-gray-500 lg:h-11 lg:w-11 lg:border"
                @click.stop="sidebarToggle = !sidebarToggle">
                <svg class="hidden fill-current lg:block" width="16" height="12" viewBox="0 0 16 12" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z"
                        fill="" />
                </svg>

                <svg :class="sidebarToggle ? 'hidden' : 'block lg:hidden'" class="fill-current lg:hidden" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.25 6C3.25 5.58579 3.58579 5.25 4 5.25L20 5.25C20.4142 5.25 20.75 5.58579 20.75 6C20.75 6.41421 20.4142 6.75 20 6.75L4 6.75C3.58579 6.75 3.25 6.41422 3.25 6ZM3.25 18C3.25 17.5858 3.58579 17.25 4 17.25L20 17.25C20.4142 17.25 20.75 17.5858 20.75 18C20.75 18.4142 20.4142 18.75 20 18.75L4 18.75C3.58579 18.75 3.25 18.4142 3.25 18ZM4 11.25C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75L12 12.75C12.4142 12.75 12.75 12.4142 12.75 12C12.75 11.5858 12.4142 11.25 12 11.25L4 11.25Z"
                        fill="" />
                </svg>

                <!-- cross icon -->
                <svg :class="sidebarToggle ? 'block lg:hidden' : 'hidden'" class="fill-current" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                        fill="" />
                </svg>
            </button>
            <!-- Hamburger Toggle BTN -->

            <div class="lg:hidden w-full flex items-center justify-center">
                <button type="button" @click="isReferralModalOpen = true"
                    class="flex items-center justify-center gap-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition duration-300 ease-in-out hover:bg-gray-50 hover:text-gray-800">
                    Referral
                </button>
            </div>

            <!-- Application nav menu button -->
            <button
                class="z-99999 flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 lg:hidden"
                :class="menuToggle ? 'bg-gray-100' : ''" @click.stop="menuToggle = !menuToggle">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M5.99902 10.4951C6.82745 10.4951 7.49902 11.1667 7.49902 11.9951V12.0051C7.49902 12.8335 6.82745 13.5051 5.99902 13.5051C5.1706 13.5051 4.49902 12.8335 4.49902 12.0051V11.9951C4.49902 11.1667 5.1706 10.4951 5.99902 10.4951ZM17.999 10.4951C18.8275 10.4951 19.499 11.1667 19.499 11.9951V12.0051C19.499 12.8335 18.8275 13.5051 17.999 13.5051C17.1706 13.5051 16.499 12.8335 16.499 12.0051V11.9951C16.499 11.1667 17.1706 10.4951 17.999 10.4951ZM13.499 11.9951C13.499 11.1667 12.8275 10.4951 11.999 10.4951C11.1706 10.4951 10.499 11.1667 10.499 11.9951V12.0051C10.499 12.8335 11.1706 13.5051 11.999 13.5051C12.8275 13.5051 13.499 12.8335 13.499 12.0051V11.9951Z"
                        fill="" />
                </svg>
            </button>
            <!-- Application nav menu button -->

            {{-- Search --}}
            <div class="hidden lg:block">
                <button type="button" @click="isReferralModalOpen = true"
                    class="flex items-center justify-center gap-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition duration-300 ease-in-out hover:bg-gray-50 hover:text-gray-800">
                    Referral
                </button>
            </div>
        </div>

        <div :class="menuToggle ? 'flex' : 'hidden'"
            class="shadow-theme-md w-full items-center justify-between gap-4 px-5 py-4 lg:flex lg:justify-end lg:px-0 lg:shadow-none">
            <div class="2xsm:gap-3 flex items-center gap-2">
                <div>
                    @role('admin')
                        <div class="text-center flex justify-center">
                            <a href="{{ route('langganan.index') }}"
                                class="flex items-center justify-center gap-1 rounded-full bg-slate-400 px-2.5 py-0.5 font-medium text-theme-sm text-white transition duration-300 ease-in-out hover:bg-slate-600 w-fit">
                                {{ $totalPoin ?? 0 }}
                                points
                            </a>
                        </div>
                        @if ($expiredAt)
                            <p class="text-xs text-gray-700 mt-1">Expired :
                                {{ \Carbon\Carbon::parse($expiredAt)->translatedFormat('d F Y') ?? '-' }}
                            </p>
                        @endif
                    @endrole
                </div>
            </div>

            {{-- Notification --}}
            <div class="relative" x-data="notificationSystem()" @click.outside="dropdownOpen = false">
                <button
                    class="relative flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700"
                    @click.prevent="toggleDropdown()">
                    <span :class="notifying ? 'flex' : 'hidden'"
                        class="absolute right-0 top-0.5 z-1 h-2 w-2 rounded-full bg-orange-400">
                        <span
                            class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"></span>
                    </span>
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z"
                            fill=""></path>
                    </svg>
                </button>

                <!-- Dropdown Start -->
                <div x-show="dropdownOpen"
                    class="absolute -right-[240px] mt-[17px] flex h-[480px] w-[350px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg sm:w-[361px] lg:right-0">
                    <div class="mb-3 flex items-center justify-between border-b border-gray-100 pb-3">
                        <h5 class="text-lg font-semibold text-gray-800">
                            Notification
                        </h5>

                        <div class="flex items-center gap-2">
                            @if (Auth::user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-500 hover:text-blue-700"
                                        title="Mark all as read">
                                        Mark all as read
                                    </button>
                                </form>
                            @endif
                            <button @click="closeDropdown()" class="text-gray-500">
                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                                        fill=""></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Loading indicator -->
                    <div x-show="isLoading" class="flex justify-center items-center h-40">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                    </div>

                    <!-- Notifications list -->
                    <ul x-show="!isLoading" class="custom-scrollbar flex h-auto flex-col overflow-y-auto">
                        <template x-for="(notification, index) in notifications" :key="index">
                            <li class="rounded-lg border-b border-gray-100 p-3 px-4.5 py-3"
                                :class="notification.read_at === null ? 'bg-blue-50' : ''">
                                <div class="block w-full">
                                    <div class="mb-1.5 block text-theme-sm">
                                        <span class="font-medium text-gray-800"
                                            x-text="notification.data.title"></span>
                                        <template x-if="notification.read_at === null">
                                            <span class="ml-2 inline-block h-2 w-2 rounded-full bg-orange-400"></span>
                                        </template>
                                    </div>

                                    <div class="text-theme-sm text-gray-500" x-text="notification.data.message">
                                    </div>

                                    <div class="flex items-center gap-2 text-theme-xs text-gray-500 mt-2">
                                        <span x-text="notification.created_at_formatted"></span>
                                        <span class="h-1 w-1 rounded-full bg-gray-400"></span>
                                        <span x-text="'From: ' + (notification.shared_by_name || 'eKurva.com')"></span>
                                    </div>

                                    <!-- Notification type handling -->
                                    <template x-if="notification.data.type">
                                        <div>
                                            {{-- Share Pasien --}}
                                            <template
                                                x-if="notification.data.type === 'patient_shared' && notification.data.shared_id">
                                                <div>
                                                    <template x-if="notification.data.share_status === 'accepted'">
                                                        <div class="mt-3">
                                                            <div
                                                                class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2 font-medium text-green-700 w-full justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                Pasien Diterima
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <template x-if="notification.data.share_status === 'rejected'">
                                                        <div class="mt-3">
                                                            <div
                                                                class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2 font-medium text-red-700 w-full justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                Pasien Ditolak
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <template x-if="notification.data.share_status === 'revoked'">
                                                        <div class="mt-3">
                                                            <div
                                                                class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-2 font-medium text-yellow-700 w-full justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                                Share Dihentikan
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <template x-if="notification.data.share_status === 'pending'">
                                                        <div class="mt-3 flex gap-2">
                                                            <form
                                                                :action="'/patient-share/' + notification.data
                                                                    .shared_id + '/accept/' + notification.id"
                                                                method="POST" class="w-full">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-green-500 px-4 py-2 font-medium text-white w-full justify-center hover:bg-green-700 transition ease-in-out duration-300">
                                                                    Terima Pasien
                                                                </button>
                                                            </form>
                                                            <form
                                                                :action="'/patient-share/' + notification.data
                                                                    .shared_id + '/reject/' + notification.id"
                                                                method="POST" class="w-1/3">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 w-full justify-center hover:bg-gray-100 transition ease-in-out duration-300">
                                                                    Tolak
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            {{-- Collaborator --}}
                                            <!-- Similar patterns for collaborator_shared notifications -->
                                            <template
                                                x-if="notification.data.type === 'collaborator_shared' && notification.data.shared_id">
                                                <!-- Collaborator notification actions -->
                                                <div>
                                                    <template x-if="notification.data.share_status === 'accepted'">
                                                        <div class="mt-3">
                                                            <div
                                                                class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-2 font-medium text-blue-700 w-full justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                Kolaborasi Diterima
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <template x-if="notification.data.share_status === 'rejected'">
                                                        <div class="mt-3">
                                                            <div
                                                                class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2 font-medium text-red-700 w-full justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                Kolaborasi Ditolak
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <template x-if="notification.data.share_status === 'revoked'">
                                                        <div class="mt-3">
                                                            <div
                                                                class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-2 font-medium text-yellow-700 w-full justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                                Kolaborasi Dihentikan
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <template x-if="notification.data.share_status === 'pending'">
                                                        <div class="mt-3 flex gap-2">
                                                            <form
                                                                :action="'/patient/collaborator/' + notification.data
                                                                    .shared_id + '/accept/' + notification.id"
                                                                method="POST" class="w-full">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2 font-medium text-white w-full justify-center hover:bg-blue-700 transition ease-in-out duration-300 transition ease-in-out duration-300">
                                                                    Terima Kolaborasi
                                                                </button>
                                                            </form>
                                                            <form
                                                                :action="'/patient/collaborator/' + notification.data
                                                                    .shared_id + '/reject/' + notification.id"
                                                                method="POST" class="w-1/3">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 w-full justify-center hover:bg-gray-100 transition ease-in-out duration-300">
                                                                    Tolak
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            {{-- Account delete warning --}}
                                            <!-- Add this template somewhere after your other notification type templates -->
                                            <template x-if="notification.data.type === 'account_deletion_warning'">
                                                <div class="mt-3">
                                                    <div x-if="notification.data.type === 'account_deletion_warning'"
                                                        class="text-theme-sm
                                                        shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg
                                                        border border-red-200 bg-red-50 px-4 py-2 font-medium
                                                        text-red-700 w-full justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                        <span
                                                            x-text="'Akun terhapus dalam ' + notification.data.days_remaining + ' hari'"></span>
                                                    </div>
                                                    <a :href="notification.data.url" class="mt-2 inline-block w-full">
                                                        <button type="button"
                                                            class="w-full rounded-lg bg-blue-500 px-4 py-2 text-theme-sm font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                                                            Beli Poin Sekarang
                                                        </button>
                                                    </a>
                                                </div>
                                            </template>

                                            {{-- Account Expiry Warning --}}
                                            <template x-if="notification.data.type === 'account_expiry'">
                                                <div class="mt-3">
                                                    <div
                                                        class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg
             border border-orange-200 bg-orange-50 px-4 py-2 font-medium
             text-orange-700 w-full justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span
                                                            x-text="'Masa Aktif habis dalam ' + notification.data.days_remaining + ' hari'"></span>
                                                    </div>
                                                    <a :href="notification.data.url" class="mt-2 inline-block w-full">
                                                        <button type="button"
                                                            class="w-full rounded-lg bg-blue-500 px-4 py-2 text-theme-sm font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                                                            Beli Poin Sekarang
                                                        </button>
                                                    </a>
                                                </div>
                                            </template>

                                            {{-- Low Points Warning --}}
                                            <template x-if="notification.data.type === 'low_points'">
                                                <div class="mt-3">
                                                    {{-- <div
                                                        class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg
             border border-yellow-200 bg-yellow-50 px-4 py-2 font-medium
             text-yellow-700 w-full justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span
                                                            x-text="'Low points: ' + notification.data.points + ' remaining'"></span>
                                                    </div> --}}
                                                    <a :href="notification.data.url" class="mt-2 inline-block w-full">
                                                        <button type="button"
                                                            class="w-full rounded-lg bg-blue-500 px-4 py-2 text-theme-sm font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                                                            Beli Point
                                                        </button>
                                                    </a>
                                                </div>
                                            </template>

                                            {{-- <template
                                                x-if="notification.data.type !== 'patient_shared' && notification.data.type !== 'collaborator_shared'">
                                                <div class="mt-3">
                                                    <form :action="'/notifications/mark-as-read/' + notification.id"
                                                        method="GET">
                                                        <button type="submit"
                                                            class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 w-full justify-center hover:bg-gray-100 transition ease-in-out duration-300">
                                                            Tandai Dibaca
                                                        </button>
                                                    </form>
                                                </div>
                                            </template> --}}
                                        </div>
                                    </template>

                                    <template x-if="!notification.data.type">
                                        <div class="mt-3">
                                            <form :action="'/notifications/mark-as-read/' + notification.id"
                                                method="GET">
                                                <button type="submit"
                                                    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 w-full justify-center hover:bg-gray-100 transition ease-in-out duration-300">
                                                    Tandai Dibaca
                                                </button>
                                            </form>
                                        </div>
                                    </template>
                                </div>
                            </li>
                        </template>

                        <template x-if="notifications.length === 0 && !isLoading">
                            <li class="text-center text-gray-500 p-4">
                                No notifications available.
                            </li>
                        </template>
                    </ul>

                    <!-- Custom pagination controls -->
                    <div x-show="lastPage > 1" class="mt-4 flex justify-center items-center gap-3">
                        <button @click="goToPrevPage()" :disabled="currentPage === 1"
                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                            class="px-3 py-1 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <span class="text-gray-700" x-text="`Page ${currentPage} of ${lastPage}`"></span>
                        <button @click="goToNextPage()" :disabled="currentPage === lastPage"
                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage }"
                            class="px-3 py-1 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Dropdown End -->
            </div>

            <!-- User Area -->
            <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                <a class="flex items-center text-gray-700" href="#"
                    @click.prevent="dropdownOpen = ! dropdownOpen">
                    <div class="w-10 h-10 overflow-hidden border border-gray-200 rounded-full mr-3">
                        @if ($header)
                            <img src="{{ asset('img-public/header/' . $header) }}" alt="user"
                                class="w-10 h-10 object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <span class="text-gray-400 text-xs">None</span>
                            </div>
                        @endif
                    </div>

                    <span class="text-theme-sm mr-1 block font-medium"> {{ Auth::user()->name }} </span>

                    <svg class="stroke-gray-500" width="18" height="20" viewBox="0 0 18 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.3125 8.65625L9 13.3437L13.6875 8.65625" stroke="" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <!-- Dropdown Start -->
                <div x-show="dropdownOpen"
                    class="shadow-theme-lg absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3">
                    <div>
                        <span class="text-theme-sm block font-medium text-gray-700">
                            {{ Auth::user()->name }}
                        </span>
                        <span class="text-theme-xs mt-0.5 block text-gray-500">
                            {{ Auth::user()->email }}
                        </span>
                    </div>

                    <ul class="flex flex-col gap-1 border-b border-gray-200 pt-4 pb-3">
                        <li>
                            <a href="{{ route('profile.show') }}"
                                class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700">
                                <svg class="fill-gray-500 group-hover:fill-gray-700" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                                        fill="" />
                                </svg>
                                Edit profile
                            </a>
                        </li>
                    </ul>
                    <form action="{{ route('logout') }}" method="post" class="w-full block">
                        @csrf
                        <button type="submit"
                            class="w-full group text-theme-sm mt-3 flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700">
                            <svg class="fill-gray-500 group-hover:fill-gray-700" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.1007 19.247C14.6865 19.247 14.3507 18.9112 14.3507 18.497L14.3507 14.245H12.8507V18.497C12.8507 19.7396 13.8581 20.747 15.1007 20.747H18.5007C19.7434 20.747 20.7507 19.7396 20.7507 18.497L20.7507 5.49609C20.7507 4.25345 19.7433 3.24609 18.5007 3.24609H15.1007C13.8581 3.24609 12.8507 4.25345 12.8507 5.49609V9.74501L14.3507 9.74501V5.49609C14.3507 5.08188 14.6865 4.74609 15.1007 4.74609L18.5007 4.74609C18.9149 4.74609 19.2507 5.08188 19.2507 5.49609L19.2507 18.497C19.2507 18.9112 18.9149 19.247 18.5007 19.247H15.1007ZM3.25073 11.9984C3.25073 12.2144 3.34204 12.4091 3.48817 12.546L8.09483 17.1556C8.38763 17.4485 8.86251 17.4487 9.15549 17.1559C9.44848 16.8631 9.44863 16.3882 9.15583 16.0952L5.81116 12.7484L16.0007 12.7484C16.4149 12.7484 16.7507 12.4127 16.7507 11.9984C16.7507 11.5842 16.4149 11.2484 16.0007 11.2484L5.81528 11.2484L9.15585 7.90554C9.44864 7.61255 9.44847 7.13767 9.15547 6.84488C8.86248 6.55209 8.3876 6.55226 8.09481 6.84525L3.52309 11.4202C3.35673 11.5577 3.25073 11.7657 3.25073 11.9984Z"
                                    fill="" />
                            </svg>

                            Sign out
                        </button>
                    </form>
                </div>
                <!-- Dropdown End -->
            </div>
            <!-- User Area -->
        </div>
    </div>

    <div x-show="isReferralModalOpen" x-cloak
        class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
        style="z-index: 99999;">
        <div class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
        <div @click.outside="isReferralModalOpen = false"
            class="relative w-full max-w-[584px] rounded-3xl bg-white p-6 lg:p-10">
            <!-- close btn -->
            <button @click="isReferralModalOpen = false"
                class="group absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-200 text-gray-500 transition-colors hover:bg-gray-300 hover:text-gray-500 sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                <svg class="transition-colors fill-current group-hover:text-gray-600" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z">
                    </path>
                </svg>
            </button>

            <form action="{{ route('referral.send') }}" method="POST" id="referralForm">
                @csrf
                <h4 class="mb-6 text-lg font-medium text-gray-800">
                    Kirim Referral
                </h4>

                <div class="mb-6">
                    <p class="text-gray-600 mb-4">
                        Bagikan eKurva.com dengan teman dan kolega Anda.
                    </p>
                </div>

                <div class="mb-6">
                    <x-input-label for="email" value="Email Penerima" required />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        placeholder="Masukkan email penerima" required autofocus autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end w-full gap-3 mt-6">
                    <button @click="isReferralModalOpen = false" type="button"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 sm:w-auto">
                        Batal
                    </button>
                    <x-primary-button class="min-w-fit">
                        Kirim Referral
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</header>
