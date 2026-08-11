<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 lg:static lg:translate-x-0">
    <!-- SIDEBAR HEADER -->
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 sidebar-header">
        <a href="{{ route('home') }}" class="flex items-center justify-between">
            <div :class="sidebarToggle ? 'hidden' : ''"
                class="w-10 h-10 overflow-hidden border border-gray-200 rounded-full mr-3">
                <img src="{{ asset('logo.png') }}" alt="user" class="w-10 h-10 object-cover">
            </div>

            <span class="font-semibold" :class="sidebarToggle ? 'hidden' : ''">Sikurva</span>

            <div :class="sidebarToggle ? 'lg:block' : 'hidden'"
                class="w-10 h-10 overflow-hidden border border-gray-200 rounded-full mr-3">
                <img src="{{ asset('logo.png') }}" alt="user" class="w-10 h-10 object-cover">
            </div>
        </a>
    </div>
    <div class="mt-1">
        <p class="text-gray-500 text-center" :class="sidebarToggle ? 'hidden' : ''">
            {{ Auth::user()->getInstansi() }}
        </p>
    </div>
    <hr class="border-gray-200 mb-5 mt-3">
    <!-- SIDEBAR HEADER -->

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <!-- Sidebar Menu -->
        <nav x-data="{ selected: $persist('Dashboard') }">
            <!-- Menu Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        MENU
                    </span>

                    <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-4 mb-6">
                    <!-- Menu Item Dashboard -->
                    <li>
                        <a href="{{ route('super-admin.dashboard') }}" class="menu-item group"
                            :class="{
                                'menu-item-active': '{{ Route::currentRouteName() }}'
                                === 'super-admin.dashboard',
                                'menu-item-inactive': '{{ Route::currentRouteName() }}'
                                !== 'super-admin.dashboard'
                            }">
                            <i class="fa-solid fa-house fa-lg"
                                :class="{
                                    'menu-item-icon-active': '{{ Route::currentRouteName() }}'
                                    === 'super-admin.dashboard',
                                    'menu-item-icon-inactive': '{{ Route::currentRouteName() }}'
                                    !== 'super-admin.dashboard',
                                    ...(sidebarToggle ? { 'my-2': true } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Dashboard -->

                    <!-- Menu Item Pengguna -->
                    <li>
                        <a href="{{ route('super-admin.users.index') }}" class="menu-item group"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('super-admin.users.*') ? 'true' : 'false' }},
                                'menu-item-inactive':
                                    !{{ request()->routeIs('super-admin.users.*') ? 'true' : 'false' }}
                            }">
                            <i class="fa-solid fa-users fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('super-admin.users.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive':
                                        !{{ request()->routeIs('super-admin.users.*') ? 'true' : 'false' }},
                                    ...(sidebarToggle ? { 'my-2': true } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Pengguna
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Pengguna -->

                    <!-- Menu Item Patient -->
                    <li>
                        <a href="{{ route('super-admin.patient.index') }}" class="menu-item group"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('super-admin.patient.*') ? 'true' : 'false' }},
                                'menu-item-inactive':
                                    !{{ request()->routeIs('super-admin.patient.*') ? 'true' : 'false' }}
                            }">
                            <i class="fa-solid fa-user fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('super-admin.patient.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive':
                                        !{{ request()->routeIs('super-admin.patient.*') ? 'true' : 'false' }},
                                    ...(sidebarToggle ? { 'my-2': true } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Pasien
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Patient -->

                    <!-- Menu Item Klinik -->
                    <li>
                        <a href="{{ route('super-admin.klinik.index') }}" class="menu-item group"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('super-admin.klinik.*') ? 'true' : 'false' }},
                                'menu-item-inactive':
                                    !{{ request()->routeIs('super-admin.klinik.*') ? 'true' : 'false' }}
                            }">
                            <i class="fa-solid fa-hospital fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('super-admin.klinik.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive':
                                        !{{ request()->routeIs('super-admin.klinik.*') ? 'true' : 'false' }},
                                    ...(sidebarToggle ? { 'my-2': true } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Klinik
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Klinik -->

                    <!-- Menu Item Kurva -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Kurva' ? '' : 'Kurva')"
                            class="menu-item group relative"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('super-admin.kurva.*') ? 'true' : 'false' }},
                                'menu-item-inactive': {{ request()->routeIs('super-admin.kurva.*') ? 'false' : 'true' }}
                            }">
                            <i class="fas fa-chart-line fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('super-admin.kurva.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive': {{ request()->routeIs('super-admin.kurva.*') ? 'false' : 'true' }},
                                    ...(sidebarToggle ? { 'my-2': true } : {})
                                }"></i>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Penilaian & Kurva
                            </span>
                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="{
                                    'menu-item-arrow-active': selected === 'Kurva' ||
                                        @json(request()->routeIs('super-admin.kurva.*')),
                                    'menu-item-arrow-inactive': !(
                                        selected === 'Kurva' ||
                                        @json(request()->routeIs('super-admin.kurva.*'))
                                    ),
                                    ...(sidebarToggle ? { 'lg:hidden': true } : {})
                                }"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden"
                            :class="selected === 'Kurva' ||
                                {{ request()->routeIs('super-admin.kurva.*') ? 'true' : 'false' }} ?
                                'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                {{-- <li>
                                    <a href="{{ route('super-admin.kurva.pasien.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.kurva.pasien.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.kurva.pasien.*') ? 'false' : 'true' }}
                                        }">
                                        Pasien
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="{{ route('super-admin.kurva.setting.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.kurva.setting.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.kurva.setting.*') ? 'false' : 'true' }}
                                        }">
                                        Setting
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Kurva -->

                    <!-- Menu Item Interpretasi -->

                    <!-- Menu Item Interpretasi -->


                    <!-- Menu Item Langganan -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Langganan' ? '' : 'Langganan')"
                            class="menu-item group relative"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('super-admin.langganan.*') ? 'true' : 'false' }},
                                'menu-item-inactive': {{ request()->routeIs('super-admin.langganan.*') ? 'false' : 'true' }}
                            }">
                            <i class="fa-solid fa-gift fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('super-admin.langganan.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive': {{ request()->routeIs('super-admin.langganan.*') ? 'false' : 'true' }},
                                    ...(sidebarToggle ? { 'my-2': true } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Poin
                            </span>
                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="{
                                    'menu-item-arrow-active': selected === 'Langganan' ||
                                        @json(request()->routeIs('super-admin.langganan.*')),
                                    'menu-item-arrow-inactive': !(
                                        selected === 'Langganan' ||
                                        @json(request()->routeIs('super-admin.langganan.*'))
                                    ),
                                    ...(sidebarToggle ? { 'lg:hidden': true } : {})
                                }"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden"
                            :class="selected === 'Langganan' ||
                                {{ request()->routeIs('super-admin.langganan.*') ? 'true' : 'false' }} ?
                                'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="{{ route('super-admin.langganan.paket.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.langganan.paket.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.langganan.paket.*') ? 'false' : 'true' }}
                                        }">
                                        Paket
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.langganan.transaksi.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.langganan.transaksi.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.langganan.transaksi.*') ? 'false' : 'true' }}
                                        }">
                                        Transaksi
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.langganan.setting.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.langganan.setting.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.langganan.setting.*') ? 'false' : 'true' }}
                                        }">
                                        Setting
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>

                    <!-- Menu Item Langganan -->

                    <!-- Menu Item Testimoni -->
                    <li>
                        <a href="{{ route('super-admin.testimoni.index') }}" class="menu-item group"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('super-admin.testimoni.*') ? 'true' : 'false' }},
                                'menu-item-inactive':
                                    !{{ request()->routeIs('super-admin.testimoni.*') ? 'true' : 'false' }}
                            }">
                            <i class="fas fa-comments fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('super-admin.testimoni.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive':
                                        !{{ request()->routeIs('super-admin.testimoni.*') ? 'true' : 'false' }},
                                    ...(sidebarToggle ? { 'my-2': true } : {})
                                }"></i>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Testimoni
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Testimoni -->

                    <!-- Menu Item Landing Page -->
                    <li>
                        <a href="#"
                            @click.prevent="selected = (selected === 'LandingPage' ? '' : 'LandingPage')"
                            class="menu-item group relative"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('super-admin.landing-page.*') ? 'true' : 'false' }},
                                'menu-item-inactive': {{ request()->routeIs('super-admin.landing-page.*') ? 'false' : 'true' }}
                            }">
                            <i class="fa-regular fa-newspaper fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('super-admin.landing-page.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive': {{ request()->routeIs('super-admin.landing-page.*') ? 'false' : 'true' }},
                                    ...(sidebarToggle ? { 'my-2': true } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Landing Page
                            </span>
                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="{
                                    'menu-item-arrow-active': selected === 'LandingPage' ||
                                        @json(request()->routeIs('super-admin.landing-page.*')),
                                    'menu-item-arrow-inactive': !(
                                        selected === 'LandingPage' ||
                                        @json(request()->routeIs('super-admin.landing-page.*'))
                                    ),
                                    ...(sidebarToggle ? { 'lg:hidden': true } : {})
                                }"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden"
                            :class="selected === 'LandingPage' ||
                                {{ request()->routeIs('super-admin.landing-page.*') ? 'true' : 'false' }} ?
                                'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="{{ route('super-admin.landing-page.banner.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.landing-page.banner.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.landing-page.banner.*') ? 'false' : 'true' }}
                                        }">
                                        Banner
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.landing-page.profile.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.landing-page.profile.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.landing-page.profile.*') ? 'false' : 'true' }}
                                        }">
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.landing-page.layanan.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.landing-page.layanan.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.landing-page.layanan.*') ? 'false' : 'true' }}
                                        }">
                                        Layanan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.landing-page.help.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.landing-page.help.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.landing-page.help.*') ? 'false' : 'true' }}
                                        }">
                                        Help
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.landing-page.ads-header.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.landing-page.ads-header.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.landing-page.ads-header.*') ? 'false' : 'true' }}
                                        }">
                                        Ads & Header
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.landing-page.sk-pp.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.landing-page.sk-pp.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.landing-page.sk-pp.*') ? 'false' : 'true' }}
                                        }">
                                        SK & PP
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Landing Page -->


                    <!-- Menu Item Setting -->
                    <li>
                        <a href="#"
                            @click.prevent="selected = (selected === 'Setting' ? '' : 'Setting')"
                            class="menu-item group relative"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('super-admin.setting.*') ? 'true' : 'false' }},
                                'menu-item-inactive': {{ request()->routeIs('super-admin.setting.*') ? 'false' : 'true' }}
                            }">
                            <i class="fa-solid fa-wrench fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('super-admin.setting.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive': {{ request()->routeIs('super-admin.setting.*') ? 'false' : 'true' }},
                                    ...(sidebarToggle ? { 'my-2': true } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Setting
                            </span>
                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="{
                                    'menu-item-arrow-active': selected === 'Setting' ||
                                        @json(request()->routeIs('super-admin.setting.*')),
                                    'menu-item-arrow-inactive': !(
                                        selected === 'Setting' ||
                                        @json(request()->routeIs('super-admin.setting.*'))
                                    ),
                                    ...(sidebarToggle ? { 'lg:hidden': true } : {})
                                }"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden"
                            :class="selected === 'Setting' ||
                                {{ request()->routeIs('super-admin.setting.*') ? 'true' : 'false' }} ?
                                'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="{{ route('super-admin.setting.api.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.setting.api.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.setting.api.*') ? 'false' : 'true' }}
                                        }">
                                        API
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.setting.pdf.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.setting.pdf.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.setting.pdf.*') ? 'false' : 'true' }}
                                        }">
                                        PDF
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.setting.user.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="{
                                            'menu-dropdown-item-active': {{ request()->routeIs('super-admin.setting.user.*') ? 'true' : 'false' }},
                                            'menu-dropdown-item-inactive': {{ request()->routeIs('super-admin.setting.user.*') ? 'false' : 'true' }}
                                        }">
                                        User
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Setting -->
                </ul>
            </div>
        </nav>
        <!-- Sidebar Menu -->
    </div>
</aside>
