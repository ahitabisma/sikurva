<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sikurva</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- Alpine --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('template/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/lib/twentytwenty/twentytwenty.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('template/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">

    {{-- Override Style --}}
    <style>
        html {
            scroll-behavior: smooth;
        }

        .btn-primary {
            background-color: rgb(59 130 246);
            border-color: rgb(59 130 246);
            border-radius: 12px;
            border: none;
        }

        .btn-primary:hover {
            background-color: rgb(37 99 235);
            color: white;
            border: none;
        }

        .bg-primary-new {
            background-color: rgb(59 130 246);
        }

        .text-primary-new {
            color: rgb(59 130 246);
            ;
        }

        #login-btn {
            color: rgb(59 130 246);
            ;
        }

        #login-btn:hover {
            color: rgb(37 99 235);
            ;
            text-decoration: none;
        }

        .bg-light-new {
            background-color: #f8f9fa;
        }

        .btn-light-new {
            background-color: white;
            border-radius: 20px;
            border: none;
            color: black;
        }

        .btn-light-new:hover {
            background-color: rgb(203 213 225);
            color: black;
            border: none;
        }

        .carousel-item img {
            height: 100vh;
            object-fit: cover;
            width: 100%;
        }

        section {
            scroll-margin-top: 80px;
            /* Sesuaikan dengan tinggi navbar */
        }

        a.text-light {
            position: relative;
            text-decoration: none;
        }

        a.text-light::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 0;
            height: 2px;
            background-color: #ffcc00;
            transition: width 0.3s ease;
        }

        a.text-light:hover::after {
            width: 100%;
        }

        .contact-link {
            transition: color 0.3s ease-in-out;
        }

        .contact-link:hover {
            /* background-color: #ffffff !important; */
            /* Warna latar putih saat hover */
            color: black !important;
            /* Warna teks biru saat hover */
        }

        .contact-link:hover i {
            color: black !important;
            /* Warna ikon saat hover */
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Dropdown Help */
        /* Dropdown Help - Updated styling for both desktop and mobile */
        /* Show dropdown on hover only for desktop */
        @media (min-width: 992px) {
            .nav-item.dropdown:hover .dropdown-menu {
                display: block;
            }
        }

        /* For all screen sizes */
        .dropdown-menu {
            margin-top: 0;
            border-radius: 10px;
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            left: 0;
            min-width: 100%;
            transform: translateX(-20%);
        }

        .nav-link.dropdown-toggle::after {
            margin-left: 0.2em;
            vertical-align: middle;
        }

        .dropdown-item {
            padding: 8px 20px 8px 20px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: rgb(59 130 246);
            color: white;
        }

        /* Mobile dropdown adjustments */
        @media (max-width: 991.98px) {
            .dropdown-menu {
                position: static;
                float: none;
                width: auto;
                margin-top: 0;
                background-color: transparent;
                border: 0;
                box-shadow: none;
                transform: none;
                padding-left: 15px;
            }

            .dropdown-item {
                padding: 8px 0;
                color: rgba(0, 0, 0, 0.55);
            }

            .dropdown-item:hover {
                background-color: transparent;
                color: rgb(59 130 246);
            }

            /* Hide dropdown menu by default on mobile */
            .nav-item.dropdown .dropdown-menu {
                display: none;
            }

            /* Show dropdown menu when parent has 'show' class */
            .nav-item.dropdown.show .dropdown-menu {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed top-0 start-0 w-100 vh-100 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary-new" role="status"
            style="width: 4rem; height: 4rem; border-width: 4px;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->



    <!-- Topbar Start -->
    <div class="container-fluid bg-light ps-5 pe-0 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center">
                    <small class="py-2">Sikurva</small>
                </div>
            </div>
            <div class="col-md-6 text-center text-lg-end">
                <div
                    class="position-relative d-inline-flex align-items-center bg-primary-new text-white top-shape px-5">
                    <a href="mailto:{{ isset($contact['email']) ? $contact['email'] : 'cs.ptekai@gmail.com' }}"
                        class="me-3 pe-3 border-end py-2 contact-link" style="color: white; text-decoration: none;">
                        <p class="m-0"><i
                                class="fa fa-envelope-open me-2"></i>{{ isset($contact['email']) ? $contact['email'] : 'cs.ptekai@gmail.com' }}
                        </p>
                    </a>
                    <a href="https://wa.me/{{ isset($contact['no_wa_convert']) ? $contact['no_wa_convert'] : '6281314158140' }}"
                        target="_blank" class="py-2 contact-link" style="color: white; text-decoration: none;">
                        <p class="m-0"><i
                                class="fab fa-whatsapp me-2"></i>{{ isset($contact['no_wa']) ? $contact['no_wa'] : '081314158140' }}
                        </p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm px-4 py-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Logo + Nama -->
            <a href="{{ route('home') }}" class="navbar-brand p-0 d-flex align-items-center">
                <img src="{{ asset('logo.png') }}" alt="Sikurva" width="40" height="40"
                    class="me-2 rounded-circle" />
                <h3 class="m-0 text-primary-new d-none d-lg-inline">Sikurva</h3>
            </a>


            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="container">
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Menu -->
                <div class="navbar-nav ms-auto py-0">
                    <a href="#home" class="nav-item nav-link active">Home</a>
                    <a href="#about" class="nav-item nav-link">About</a>
                    <a href="#layanan" class="nav-item nav-link">Layanan</a>
                    <a href="#langganan" class="nav-item nav-link">Langganan</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Help</a>
                        <div class="dropdown-menu m-0">
                            @forelse ($helps as $help)
                                <a href="{{ $help->url }}" target="_blank"
                                    class="dropdown-item">{{ $help->title }}</a>
                            @empty
                                <a href="https://www.who.int" target="_blank" class="dropdown-item">WHO Growth
                                    Chart</a>
                                <a href="https://www.cdc.or" target="_blank" class="dropdown-item">CDC
                                    Calculation</a>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Login & Register Buttons -->
                @if (Auth::check())
                    <div class="d-lg-flex align-items-center d-block text-center mt-3 mt-lg-0">
                        <a href="{{ Auth::user()->roles()->first()->name === 'super-admin' ? route('super-admin.dashboard') : route('patient.index') }}"
                            class="py-2 px-3 d-block d-lg-inline-block" id="login-btn">
                            <i class="fas fa-user me-2"></i>{{ Auth::user()->name ?? 'Dashboard' }}
                        </a>
                    </div>
                @else
                    <div class="d-lg-flex align-items-center d-block text-center mt-3 mt-lg-0">
                        <a href="{{ route('login') }}" class="py-2 px-3 d-block d-lg-inline-block" id="login-btn">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </a>
                        <span class="border-start border-2 me-3 d-none d-lg-inline" style="height: 40px;"></span>
                        <a href="{{ route('register') }}"
                            class="btn btn-primary py-2 px-4 d-block d-lg-inline-block mt-2 mt-lg-0">Daftar</a>
                    </div>
                @endif
            </div>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Carousel Start -->
    <section id="home" class="container-fluid p-0">
        <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                @forelse ($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ asset($banner->bg_banner) }}" alt="Image">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 900px;">
                                <h5 class="text-white text-uppercase mb-3 animated slideInDown">
                                    {{ $banner->subtitle }}
                                </h5>
                                <h1 class="display-1 text-white mb-md-4 animated zoomIn">{{ $banner->title }}</h1>
                                <a href="{{ route('register') }}"
                                    class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Daftar
                                    Sekarang</a>
                                {{-- <a href="" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Contact
                                Us</a> --}}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="carousel-item active">
                        <img src="{{ asset('img/carousel-2.jpeg') }}" alt="Image">

                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 900px;">
                                <h5 class="text-white text-uppercase mb-3 animated slideInDown">Keep Your Child's
                                    Healthy
                                </h5>
                                <h1 class="display-1 text-white mb-md-4 animated zoomIn">Digitalisaasi Kurva Tumbuh
                                    Kembang
                                    Anak Indonesia</h1>
                                <a href="{{ route('register') }}"
                                    class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInTop">Daftar
                                    Sekarang</a>
                                {{-- <a href="" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Contact
                                Us</a> --}}
                            </div>
                        </div>
                    </div>
                @endforelse

            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    <!-- Carousel End -->


    <!-- Banner Start -->

    <!-- Banner Start -->


    <!-- About Start -->
    <section id="about" class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="section-title mb-4">
                        <h5 class="position-relative d-inline-block text-primary-new text-uppercase">About</h5>
                        <h1 class="display-5 mb-0">dr. Andi Pratama, Sp.A</h1>
                    </div>
                    <h4 class="text-body fst-italic mb-4">Spesialis Anak</h4>
                    <p class="mb-4">dr. Andi Pratama, Sp.A adalah seorang Dokter Spesialis Anak berpengalaman yang berdedikasi dalam memberikan layanan kesehatan anak secara menyeluruh. Beliau menyelesaikan pendidikan spesialis di Universitas Airlangga dan aktif dalam berbagai program edukasi kesehatan anak di Indonesia.</p>
                    <div class="row g-3">
                        <div class="col-sm-6 wow zoomIn" data-wow-delay="0.3s">
                            <h5 class="mb-3"><i class="fa fa-check-circle text-primary-new me-3"></i>Konsultasi Tumbuh Kembang</h5>
                            <h5 class="mb-3"><i class="fa fa-check-circle text-primary-new me-3"></i>Imunisasi Anak</h5>
                        </div>
                        <div class="col-sm-6 wow zoomIn" data-wow-delay="0.6s">
                            <h5 class="mb-3"><i class="fa fa-check-circle text-primary-new me-3"></i>Gizi Anak</h5>
                            <h5 class="mb-3"><i class="fa fa-check-circle text-primary-new me-3"></i>Deteksi Dini Penyakit</h5>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-primary py-3 px-5 mt-4 wow zoomIn"
                        data-wow-delay="0.6s">Daftar Sekarang</a>
                </div>
                <div class="col-lg-5" style="min-height: 500px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100 wow zoomIn" data-wow-delay="0.9s"
                            src="{{ asset('template/img/about.jpg') }}"
                            style="object-fit: cover; border-radius: 30px">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile End -->


    <!-- Appointment Start -->
    <!-- Appointment End -->


    <!-- Layanan Start -->
    <section id="layanan" class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="col-lg-7">
                    <div class="section-title">
                        <h5 class="position-relative d-inline-block text-primary-new text-uppercase">Layanan</h5>
                        <h1 class="display-5 mb-0">Layanan yang Tersedia</h1>
                    </div>
                </div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay="0.1s">
                <div class="col-lg-12">
                    <div class="row">
                        @forelse ($layanans as $layanan)
                            <div class="col-md-4 service-item wow zoomIn" data-wow-delay="0.3s">
                                <div class="rounded-top overflow-hidden d-flex justify-content-center">
                                    <img class="img-fluid py-3" src="{{ asset($layanan->image) }}" alt=""
                                        width="100">
                                </div>
                                <div class="position-relative bg-light-new rounded-bottom text-center p-4">
                                    <h5 class="m-0 mb-3">{{ $layanan->title }}</h5>
                                    <p>{{ $layanan->description }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-4 service-item wow zoomIn" data-wow-delay="0.3s">
                                <div class="rounded-top overflow-hidden d-flex justify-content-center">
                                    <img class="img-fluid py-3" src="{{ asset('img/patient.png') }}" alt=""
                                        width="100">
                                </div>
                                <div class="position-relative bg-light-new rounded-bottom text-center p-4">
                                    <h5 class="m-0 mb-3">Pencatatan Data Pasien</h5>
                                    <p>Layanan pencatatan data pasien kami memungkinkan penyimpanan informasi anak
                                        secara akurat dan
                                        terorganisir.</p>
                                </div>
                            </div>
                            <div class="col-md-4 service-item wow zoomIn" data-wow-delay="0.6s">
                                <div class="rounded-top overflow-hidden d-flex justify-content-center">
                                    <img class="img-fluid py-3" src="{{ asset('img/redo-arrow.png') }}"
                                        width="100" alt="">
                                </div>
                                <div class="position-relative bg-light-new rounded-bottom text-center p-4">
                                    <h5 class="m-0 mb-3">Generate Kurva</h5>
                                    <p>Menghasilkan kurva tumbuh kembang anak yang mudah dipahami berdasarkan
                                        data
                                        yang tercatat.</p>
                                </div>
                            </div>
                            <div class="col-md-4 service-item wow zoomIn" data-wow-delay="0.6s">
                                <div class="rounded-top overflow-hidden d-flex justify-content-center">
                                    <img class="img-fluid py-3" src="{{ asset('img/interpreter.png') }}"
                                        width="100" alt="">
                                </div>
                                <div class="position-relative bg-light-new rounded-bottom text-center p-4">
                                    <h5 class="m-0 mb-3">Generate Interpretasi</h5>
                                    <p>Interpretasi otomatis yang memberikan wawasan mendalam tentang status
                                        perkembangan anak.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Layanan End -->


    <!-- Offer Start -->
    <section id="offer" class="container-fluid my-5 wow fadeInUp" data-wow-delay="0.1s"
        style="background-image: url({{ asset('img/lp-1.jpeg') }}); background-size: cover; background-position: center;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="text-center p-5" style="background-color: rgb(59 130 246); border-radius: 20px;">
                        <h1 class="display-5 text-white">Gratis {{ $pointSettingPenggunaBaruNakes->points }} point
                            untuk
                            pengguna baru</h1>
                        <p class="text-white mb-4">Dapatkan {{ $pointSettingPenggunaBaruNakes->points }} point dengan
                            masa
                            berlaku
                            {{ $pointSettingPenggunaBaruNakes->duration . ' ' . $pointSettingPenggunaBaruNakes->duration_type }}
                            untuk pengguna
                            tenaga kesehatan baru dan {{ $pointSettingPenggunaBaruAwam->points }} point dengan masa
                            berlaku
                            {{ $pointSettingPenggunaBaruAwam->duration . ' ' . $pointSettingPenggunaBaruAwam->duration_type }}
                            untuk pengguna
                            awam baru
                            setelah pendaftaran. </p>
                        <a href="{{ route('register') }}" class="btn btn-light-new py-3 px-5 me-3">Daftar
                            Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Offer End -->


    <!-- langganan Start -->
    <section id="langganan" class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="section-title mb-4">
                        <h5 class="position-relative d-inline-block text-primary-new text-uppercase">Langganan</h5>
                        <h1 class="display-5 mb-0">Langganan yang Tersedia</h1>
                    </div>
                    <p class="mb-4">Pilih paket layanan kami yang sesuai dengan kebutuhan Anda untuk
                        memantau dan mendukung tumbuh kembang anak dengan solusi digital terbaik.</p>
                    {{-- <h5 class="text-uppercase text-primary-new wow fadeInUp" data-wow-delay="0.3s">Call for
                        Appointment
                    </h5>
                    <h1 class="wow fadeInUp" data-wow-delay="0.6s">+012 345 6789</h1> --}}
                </div>
                <div class="col-lg-7">
                    <div class="owl-carousel price-carousel wow zoomIn" data-wow-delay="0.9s">

                        @forelse ($pakets as $paket)
                            <div class="price-item pb-4">
                                <div class="position-relative">
                                    {{-- <img class="img-fluid rounded-top" src="{{ asset('template/img/price-1.jpg') }}"
                                alt=""> --}}
                                    <div class="d-flex flex-column align-items-center justify-content-center bg-light-new rounded pt-3"
                                        style="z-index: 2;">
                                        <h4 style="margin-top: 0; padding: 0">{{ $paket->name }}</h4>
                                        <h3 class="text-primary-new" style="margin: 0; padding: 0">Rp
                                            {{ formatPrice($paket->price) }}</h3>
                                        {{-- <p>Cocok untuk orang awam</p> --}}
                                        {{-- <p class="" style="margin-top: 0; padding: 0">100 poin (1 tahun)</p> --}}
                                    </div>
                                </div>
                                <div
                                    class="position-relative text-center bg-light-new border-bottom border-primary p-4">

                                    <hr class="text-primary-new w-50 mx-auto mt-0">
                                    <div class="d-flex justify-content-between mb-3"><span>{{ $paket->point }}
                                            point
                                            ({{ $paket->duration . ' ' . $paket->duration_type }})
                                        </span><i class="fa fa-check text-primary-new pt-1"></i></div>
                                    @if ($paket->description)
                                        @foreach (json_decode($paket->description, true) as $desc)
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>{{ $desc }}</span><i
                                                    class="fa fa-check text-primary-new pt-1"></i>
                                            </div>
                                        @endforeach
                                    @endif
                                    <a href="{{ route('register') }}"
                                        class="btn btn-primary py-2 px-4 position-absolute top-100 start-50 translate-middle">Beli</a>
                                </div>
                            </div>
                        @empty
                            <div class="price-item pb-4">
                                <div class="position-relative">
                                    {{-- <img class="img-fluid rounded-top" src="{{ asset('template/img/price-1.jpg') }}"
                                    alt=""> --}}
                                    <div class="d-flex flex-column align-items-center justify-content-center bg-light-new rounded pt-3"
                                        style="z-index: 2;">
                                        <h4 style="margin-top: 0; padding: 0">Basic</h4>
                                        <h3 class="text-primary-new" style="margin: 0; padding: 0">$</h3>
                                        {{-- <p>Cocok untuk orang awam</p> --}}
                                        {{-- <p class="" style="margin-top: 0; padding: 0">100 poin (1 tahun)</p> --}}
                                    </div>
                                </div>
                                <div
                                    class="position-relative text-center bg-light-new border-bottom border-primary p-4">

                                    <hr class="text-primary-new w-50 mx-auto mt-0">
                                    <div class="d-flex justify-content-between mb-3"><span>100 point (1
                                            tahun)</span><i class="fa fa-check text-primary-new pt-1"></i></div>
                                    <div class="d-flex justify-content-between mb-3"><span>Cocok untuk orang
                                            awam</span><i class="fa fa-check text-primary-new pt-1"></i></div>
                                    <a href="{{ route('register') }}"
                                        class="btn btn-primary py-2 px-4 position-absolute top-100 start-50 translate-middle">Beli</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- langganan End -->


    <!-- Testimonial Start -->
    <section id="testimoni" class="container-fluid my-5 wow fadeInUp" data-wow-delay="0.1s"
        style="background-image: url({{ asset('img/lp-2.jpg') }}); background-size: cover; background-position: top;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="owl-carousel testimonial-carousel p-5 wow zoomIn" data-wow-delay="0.6s"
                        style="border-radius: 20px;">
                        @forelse ($testimonis as $testimoni)
                            <div class="testimonial-item text-center text-white">
                                <div class="d-flex justify-content-center align-items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <p
                                            class="{{ $i <= $testimoni->rating ? 'text-warning' : 'text-white' }} fs-6 mx-2">
                                            <i class="fa-solid fa-star fa-lg"></i>
                                        </p>
                                    @endfor
                                </div>

                                <!-- Kontainer untuk teks testimoni dengan Alpine.js -->
                                <div x-data="{ isExpanded: false }">
                                    <p class="fs-5"
                                        x-bind:style="isExpanded ? 'display: block; overflow: visible;' :
                                            'display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;'"
                                        x-transition>
                                        {{ $testimoni->testimoni }}
                                    </p>

                                    <!-- Tombol See More / See Less -->
                                    <button @click="isExpanded = !isExpanded" class="btn btn-link text-white p-0 mt-1"
                                        style="text-decoration: none;">
                                        <span x-show="!isExpanded" x-transition>See More</span>
                                        <span x-show="isExpanded" x-transition>See Less</span>
                                    </button>
                                </div>

                                <hr class="mx-auto w-25">
                                <div>
                                    <h4 class="text-white mb-0">{{ $testimoni->user_name }}</h4>
                                    <p>{{ $testimoni->instansi_name ?? '' }}</p>
                                </div>
                            </div>

                        @empty
                            <div class="testimonial-item text-center text-white">
                                <img class="img-fluid mx-auto mb-4" src="{{ asset('img/user.png') }}" alt=""
                                    style="border-radius: 100%">
                                <p class="fs-5">Dolores sed duo clita justo dolor et stet lorem kasd dolore lorem
                                    ipsum.
                                    At lorem lorem magna ut et, nonumy labore diam erat. Erat dolor rebum sit ipsum.
                                </p>
                                <hr class="mx-auto w-25">
                                <h4 class="text-white mb-0">Client Name</h4>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial End -->


    <!-- Team Start -->
    <!-- Team End -->


    <!-- Newsletter Start -->
    <!-- Newsletter End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light py-5 wow fadeInUp" data-wow-delay="0.3s"
        style="margin-top: -75px;">
        <div class="container pt-5">
            <div class="row g-5 pt-4">
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Tentang Kami</h3>
                    <div class="d-flex flex-column justify-content-start">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('logo.png') }}" alt="Sikurva" width="30"
                                height="30" class="me-2 rounded-circle" />
                            <h6 class="text-white mb-0">Sikurva</h6>
                        </div>

                        <p class="text-white">Solusi terbaik untuk memantau pertumbuhan dan perkembangan anak Anda.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Layanan</h3>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="#"><i
                                class="bi bi-arrow-right text-primary-new me-2"></i>Pencatatan Data Pasien</a>
                        <a class="text-light mb-2" href="#"><i
                                class="bi bi-arrow-right text-primary-new me-2"></i>Grafik Pertumbuhan Anak</a>
                        <a class="text-light mb-2" href="#"><i
                                class="bi bi-arrow-right text-primary-new me-2"></i>Interpretasi Data Anak</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Get In Touch</h3>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2"><i class="bi bi-geo-alt text-primary-new me-2"></i>Sikurva
                            Anak
                            Indonesia</a>
                        <a href="mailto:{{ isset($contact['email']) ? $contact['email'] : 'cs.ptekai@gmail.com' }}"
                            class="text-light mb-2"><i
                                class="bi bi-envelope-open text-primary-new me-2"></i>{{ isset($contact['email']) ? $contact['email'] : 'cs.ptekai@gmail.com' }}
                        </a>
                        <a href="https://wa.me/{{ isset($contact['no_wa_convert']) ? $contact['no_wa_convert'] : '6281314158140' }}"
                            class="text-light mb-2"><i
                                class="bi bi-telephone text-primary-new me-2"></i>{{ isset($contact['no_wa']) ? $contact['no_wa'] : '081314158140' }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-light py-4" style="background: #051225;">
        <div class="container">
            <div class="row g-0">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">&copy; <a class="text-white border-bottom" href="#">Sikurva Anak
                            Indonesia</a>.
                        All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top" style="border-radius: 100%; padding:10px 15px;"><i
            class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('template/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('template/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('template/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('template/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('template/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('template/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('template/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('template/lib/twentytwenty/jquery.event.move.js') }}"></script>
    <script src="{{ asset('template/lib/twentytwenty/jquery.twentytwenty.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('template/js/main.js') }}"></script>

    <script>
        // Nonaktifkan klik kanan
        document.addEventListener('contextmenu', e => e.preventDefault());

        // Nonaktifkan F12 dan inspect shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === "F12" ||
                (e.ctrlKey && e.shiftKey && ['I', 'C', 'J'].includes(e.key.toUpperCase())) ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sections = document.querySelectorAll("section"); // Ambil semua section
            const navLinks = document.querySelectorAll(".nav-link"); // Ambil semua link navbar

            function activateNav() {
                let scrollPos = window.scrollY + 120; // Ambil posisi scroll (tambah offset)

                sections.forEach(section => {
                    if (scrollPos >= section.offsetTop && scrollPos < section.offsetTop + section
                        .offsetHeight) {
                        let id = section.getAttribute("id"); // Ambil ID section
                        navLinks.forEach(link => {
                            link.classList.remove("active"); // Hapus class active
                            if (link.getAttribute("href") === `#${id}`) {
                                link.classList.add(
                                    "active"); // Tambahkan class active ke link yang sesuai
                            }
                        });
                    }
                });
            }

            window.addEventListener("scroll", activateNav);
        });

        // Add this script just before the closing </body> tag
        document.addEventListener("DOMContentLoaded", function() {
            // Handle dropdown on mobile
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            const dropdownParent = document.querySelector('.nav-item.dropdown');

            // For mobile: Toggle the dropdown when clicking the dropdown toggle
            dropdownToggle.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    e.preventDefault();
                    dropdownParent.classList.toggle('show');
                }
            });

            // Update responsive behavior on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    // On desktop, use hover behavior defined in CSS
                    dropdownParent.classList.remove('show');
                }
            });
        });
    </script>
</body>

</html>
