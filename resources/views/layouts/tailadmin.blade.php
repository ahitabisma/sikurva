<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    {{-- Csrf Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <title>
        @if (isset($title))
            {{ $title }} | Sikurva
        @else
            Sikurva
        @endif
    </title>

    <!-- Favicon -->
    <link href="{{ asset('logo.png') }}" rel="icon">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('tailadmin/build/style.css') }}">
    {{-- <script src="{{ asset('tailadmin/build/bundle.js') }}"></script> --}}


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('style')

    <style>
        [x-cloak] {
            display: none !important;
        }

        .animate-progress {
            width: 100%;
            animation: progressBar 3s linear forwards;
        }

        @keyframes progressBar {
            from {
                width: 100%;
            }

            to {
                width: 0;
            }
        }
    </style>
</head>

<body x-data="{ page: 'ecommerce', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': true, 'scrollTop': false }" x-init="darkMode = false;
isModalOpen = false">
    <!-- ===== Preloader Start ===== -->
    <div id="loading-overlay"
        class="hidden fixed z-999999 flex flex-col h-screen w-screen items-center justify-center bg-white">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-blue-500 border-t-transparent">
        </div>
        <p class="text-black text-theme-lg mt-5">Sedang mengenerate PDF, harap tunggu...</p>
    </div>
    @include('layouts.partials.preloader')
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
        <!-- ===== Sidebar Start ===== -->
        @role('super-admin')
            @include('layouts.partials.sidebar-super-admin')
        @else
            @include('layouts.partials.sidebar')
        @endrole

        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Small Device Overlay Start -->
            @include('layouts.partials.overlay')
            <!-- Small Device Overlay End -->

            <!-- ===== Header Start ===== -->
            @role('super-admin')
                @include('layouts.partials.header-super-admin')
            @else
                @include('layouts.partials.header')
            @endrole
            <!-- ===== Header End ===== -->

            <!-- ===== Main Content Start ===== -->
            <main x-data="{
                openConfirmationModal(title, points, callback) {
                    window.dispatchEvent(
                        new CustomEvent('open-confirmation-modal', {
                            detail: {
                                title: title,
                                points: points,
                                callback: callback
                            }
                        })
                    );
                }
            }">
                <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">


                    {{-- Breadcrumb Start --}}
                    @if (isset($title) && $title !== '')
                        <div x-data="{ pageName: `{{ $title }}` }">
                            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                                <h2 class="text-xl font-semibold text-gray-800" x-text="pageName">
                                    {{ $title }}
                                </h2>
                                <nav>
                                    <ol class="flex items-center gap-1.5">
                                        <li>
                                            <a class="inline-flex items-center gap-1.5 text-sm text-gray-500"
                                                href="{{ Auth::user()->roles()->first()->name == 'admin' ? route('patient.index', absolute: false) : route('super-admin.dashboard', absolute: false) }}">
                                                Home
                                                <svg class="stroke-current" width="17" height="16"
                                                    viewBox="0 0 17 16" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                                                        stroke="" stroke-width="1.2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                    </path>
                                                </svg>
                                            </a>
                                        </li>
                                        <li class="text-sm text-gray-800" x-text="pageName">{{ $title }}
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    @endif
                    {{-- Breadcrumb end --}}

                    {{-- Toast --}}
                    @if (session('success'))
                        <div class="my-3">
                            <x-notif-success :message="session('success')" />
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="my-3">
                            <x-notif-error :message="session('error')" />
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
            <!-- ===== Main Content End ===== -->
        </div>
        <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->


    <!-- Include the confirmation modal component -->
    <x-confirmation-modal id="confirmation-modal" />

    @yield('script')

    <script>
        // // Nonaktifkan klik kanan
        // document.addEventListener('contextmenu', e => e.preventDefault());

        // // Nonaktifkan F12 dan inspect shortcuts
        // document.addEventListener('keydown', function(e) {
        //     if (e.key === "F12" ||
        //         (e.ctrlKey && e.shiftKey && ['I', 'C', 'J'].includes(e.key.toUpperCase())) ||
        //         (e.ctrlKey && e.key === 'u')) {
        //         e.preventDefault();
        //     }
        // });
    </script>

    {{-- Pagination Notificatin --}}
    <script>
        function notificationSystem() {
            return {
                dropdownOpen: false,
                notifying: {{ Auth::user()->unreadNotifications->count() > 0 ? 'true' : 'false' }},
                notifications: [],
                currentPage: 1,
                lastPage: 1,
                isLoading: false,

                init() {
                    this.fetchNotifications(1);
                },

                toggleDropdown() {
                    this.dropdownOpen = !this.dropdownOpen;
                },

                closeDropdown() {
                    this.dropdownOpen = false;
                },

                fetchNotifications(page) {
                    this.isLoading = true;
                    fetch(`/notifications/fetch?page=${page}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.notifications = data.notifications;
                            this.currentPage = data.current_page;
                            this.lastPage = data.last_page;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.error('Error fetching notifications:', error);
                            this.isLoading = false;
                        });
                },

                goToNextPage() {
                    if (this.currentPage < this.lastPage) {
                        this.fetchNotifications(this.currentPage + 1);
                    }
                },

                goToPrevPage() {
                    if (this.currentPage > 1) {
                        this.fetchNotifications(this.currentPage - 1);
                    }
                }
            }
        }
    </script>
</body>

</html>
