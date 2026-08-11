<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        @if (isset($title))
            {{ $title }} | Sikurva
        @else
            Sikurva
        @endif
    </title>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="Sikurva - Solusi digital untuk memantau pertumbuhan dan perkembangan anak dengan kurva tumbuh kembang yang akurat." />
    <meta name="keywords"
        content="ekurva, ekurva anak, ekurva anak indonesia, kurva anak indonesia, kurva pertumbuhan anak indonesia, kurva pertumbuhan anak who, pertumbuhan anak, perkembangan anak, kurva tumbuh kembang, kesehatan anak, monitoring kesehatan anak, aplikasi kesehatan" />
    <meta name="author" content="Dr. Johannus S Wibisono" />

    <!-- Open Graph Meta Tags for social sharing -->
    <meta property="og:title" content="Sikurva - Kurva Tumbuh Kembang Digital" />
    <meta property="og:description"
        content="Solusi terbaik untuk memantau pertumbuhan dan perkembangan anak Anda dengan kurva digital yang akurat." />
    <meta property="og:image" content="{{ asset('logo.png') }}" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:type" content="website" />

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Sikurva" />
    <meta name="twitter:description"
        content="Solusi digital untuk memantau pertumbuhan dan perkembangan anak dengan kurva tumbuh kembang yang akurat." />
    <meta name="twitter:image" content="{{ asset('logo.png') }}" />

    <!-- Google Search Console -->
    <meta name="google-site-verification" content="-SVRfw3P74w-C5QhIUtXyga2Cc07fy1aKBpV7sucZso" />

    <!-- Favicon -->
    <link href="{{ asset('logo.png') }}" rel="icon">

    <link rel="stylesheet" href="{{ asset('tailadmin/build/style.css') }}">
    <script src="{{ asset('tailadmin/build/bundle.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (file_exists(public_path('build/manifest.json')))
        <link rel="stylesheet" href="{{ asset_vite('resources/css/app.css') }}">
        <script type="module" src="{{ asset_vite('resources/js/app.js') }}"></script>
    @elseif (file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

</head>

<body class="font-sans text-gray-900 antialiased">
    <!-- ===== Preloader Start ===== -->
    {{-- <div id="preloader"> --}}
    <!-- Preloader content here -->
    @include('layouts.partials.preloader')
    {{-- </div> --}}
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="relative bg-white">
        <!-- Container Utama -->
        <div class="flex flex-col lg:flex-row min-h-screen">
            <!-- Bagian Kiri (Content) -->
            <div class="w-full lg:w-1/2 lg:overflow-y-auto">
                <div class="max-w-md mx-auto px-4 py-10">
                    <!-- Back Button -->
                    <div class="mb-8">
                        <a href="/" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                            <svg class="stroke-current mr-1" xmlns="http://www.w3.org/2000/svg" width="20"
                                height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Back to home
                        </a>
                    </div>

                    <!-- Main Content -->
                    <div>
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Bagian Kanan (Fixed Image) -->
            <div class="hidden lg:block fixed top-0 right-0 w-1/2 h-full z-0">
                <!-- Background Image -->
                <div class="absolute inset-0 overflow-hidden">
                    <img src="{{ asset('img/bg-1.jpeg') }}" alt="Background"
                        class="w-full h-full object-cover object-center">
                </div>

                <!-- Overlay -->
                <div class="absolute inset-0 bg-blue-950/70"></div>

                <!-- Content -->
                <div class="relative flex h-full items-center justify-center z-10">
                    <div class="text-center max-w-xs px-4">
                        {{-- <a href="{{ route('home') }}" class="block mb-6">
                                <img src="{{ asset('logo.png') }}" alt="Logo"
                                    class="w-30 mx-auto">
                            </a> --}}
                        <div class="w-28 h-28 overflow-hidden border rounded-full mx-auto">
                            <img src="{{ asset('logo.png') }}" alt="user"
                                class="w-28 h-28 object-cover">
                        </div>
                        <h2 class="text-white text-2xl font-bold mb-4">
                            Sikurva
                        </h2>
                        <p class="text-white text-sm">
                            Digitalisasi Kurva Tumbuh Kembang Anak Indonesia
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ===== Page Wrapper End ===== -->

    <script>
        function refreshCaptcha() {
            fetch('/get_captcha/default')
                .then(response => response.text())
                .then(data => {
                    document.querySelector('.captcha-img').src = data + '&rand=' + Math.random();
                });
        }
    </script>
</body>

</html>
