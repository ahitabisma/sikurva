<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        404 | Page Not Found
    </title>
    <link rel="icon" href="favicon.ico">
    <!-- Favicon -->

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('tailadmin/build/style.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('style')

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body x-data="{ page: 'error', 'loaded': true }" x-init="window.addEventListener('DOMContentLoaded', () => { setTimeout(() => loaded = false, 500) })">
    <!-- ===== Preloader Start ===== -->
    <div x-show="loaded"
        class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent">
        </div>
    </div>
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="relative flex flex-col items-center justify-center w-full min-h-screen p-6 overflow-hidden z-1">
        <!-- ===== Common Grid Shape Start ===== -->
        <div class="absolute right-0 top-0 -z-1 w-full max-w-[250px] xl:max-w-[450px]">
            <img src="{{ asset('img/grid-01.svg') }}" alt="background pattern" />
        </div>
        <div class="absolute bottom-0 left-0 -z-1 w-full max-w-[250px] rotate-180 xl:max-w-[450px]">
            <img src="{{ asset('img/grid-01.svg') }}" alt="background pattern" />
        </div>
        <!-- ===== Common Grid Shape End ===== -->

        <div>
            <div class="mx-auto w-full max-w-[460px] text-center">
                <div class="flex flex-wrap justify-center mb-6">
                    <div class="p-6 rounded-full bg-blue-50">
                        <i class="fas fa-search text-blue-500 text-5xl"></i>
                    </div>
                </div>

                <h1 class="flex flex-wrap justify-center text-xl font-bold text-blue-500 xl:text-4xl mb-2">
                    404
                </h1>

                <h2 class="mb-3 font-bold text-gray-800 text-xl xl:text-2xl">
                    Page Not Found
                </h2>

                <p class="text-base text-gray-500 mb-6">
                    {{ $exception->getMessage() ?: 'Sorry, the page you are looking for could not be found.' }}
                </p>

                <div class="mt-8">
                    @if (Auth::user() && Auth::user()->hasRole('admin'))
                        <a href="{{ route('patient.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white transition duration-300 rounded-lg bg-brand-500 hover:bg-brand-600">
                            <i class="fas fa-home mr-2"></i> Back to Home
                        </a>
                    @elseif(Auth::user() && Auth::user()->hasRole('super-admin'))
                        <a href="{{ route('super-admin.dashboard') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white transition duration-300 rounded-lg bg-brand-500 hover:bg-brand-600">
                            <i class="fas fa-home mr-2"></i> Back to Home
                        </a>
                    @else
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white transition duration-300 rounded-lg bg-brand-500 hover:bg-brand-600">
                            <i class="fas fa-home mr-2"></i> Back to Home
                        </a>
                    @endif
                </div>
            </div>

            <div class="absolute -translate-x-1/2 bottom-6 left-1/2">
                <!-- ===== Common Social Links Start ===== -->
                <p class="mb-6 mt-20 text-center text-base text-gray-500">
                    Powered by
                    <a href="{{ route('home') }}" class="text-brand-500">ekurva.com</a>
                </p>
                <!-- ===== Common Social Links End ===== -->
            </div>
        </div>
    </div>
    <!-- ===== Page Wrapper End ===== -->
</body>

</html>
