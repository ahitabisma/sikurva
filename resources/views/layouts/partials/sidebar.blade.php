<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[100px]' : '-translate-x-full'"
    class="fixed left-0 top-0 z-99 flex h-screen w-[290px] flex-col border-r border-gray-300 bg-white px-5 lg:static lg:translate-x-0 shadow-lg">
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
                class="w-10 h-10 overflow-hidden border border-gray-200 rounded-full ">
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
                        class="fill-current menu-group-icon mx-auto" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-4 mb-6">
                    <!-- Menu Item Pasien -->
                    <li class="relative group">
                        <a href="{{ route('patient.index') }}" class="menu-item"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('patient.*') ? 'true' : 'false' }},
                                'menu-item-inactive':
                                    !{{ request()->routeIs('patient.*') ? 'true' : 'false' }}
                            }">
                            <i class="fa-solid fa-users fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('patient.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive': {{ request()->routeIs('patient.*') ? 'false' : 'true' }},
                                    ...(sidebarToggle ? { 'my-2 ml-1': 'ml-0' } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                {{ Auth::user()->is_nakes ? 'Pasien' : 'Anak' }}
                            </span>
                        </a>
                        <div :class="sidebarToggle ? 'fixed left-[90px] -translate-y-8' : 'absolute top-0'"
                            class="invisible z-9999 opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100 pointer-events-none">
                            <div class="relative w-max">
                                <div
                                    class="whitespace-nowrap rounded-lg bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-lg">
                                    {{ Auth::user()->is_nakes ? 'Pasien' : 'Anak' }}
                                </div>
                                <div class="absolute -left-1.5 top-1/2 h-3 w-4 -translate-y-1/2 rotate-45 bg-white">
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Menu Item Pasien -->

                    <!-- Menu Item Aktivitas -->
                    <li class="relative group">
                        <a href="{{ route('aktivitas.index') }}" class="menu-item"
                            :class="{
                                'menu-item-active': '{{ Route::currentRouteName() }}'
                                === 'aktivitas.index',
                                'menu-item-inactive': '{{ Route::currentRouteName() }}'
                                !== 'aktivitas.index'
                            }">
                            <i class="fa-solid fa-person-walking fa-lg"
                                :class="{
                                    'menu-item-icon-active': '{{ Route::currentRouteName() }}'
                                    === 'aktivitas.index',
                                    'menu-item-icon-inactive': '{{ Route::currentRouteName() }}'
                                    !== 'aktivitas.index',
                                    ...(sidebarToggle ? { 'my-2 ml-2': 'ml-0' } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Aktivitas
                            </span>
                        </a>
                        <div :class="sidebarToggle ? 'fixed left-[90px] -translate-y-8' : 'absolute top-0'"
                            class="invisible z-9999 opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100 pointer-events-none">
                            <div class="relative w-max">
                                <div
                                    class="whitespace-nowrap rounded-lg bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-lg">
                                    Aktivitas
                                </div>
                                <div class="absolute -left-1.5 top-1/2 h-3 w-4 -translate-y-1/2 rotate-45 bg-white">
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Menu Item Aktivitas -->

                    <!-- Menu Item Langganan -->
                    <li class="relative group">
                        <a href="{{ route('langganan.index') }}" class="menu-item"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('langganan.*') ? 'true' : 'false' }},
                                'menu-item-inactive':
                                    !{{ request()->routeIs('langganan.*') ? 'true' : 'false' }}
                            }">
                            <i class="fa-solid fa-gift fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('langganan.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive': {{ request()->routeIs('langganan.*') ? 'false' : 'true' }},
                                    ...(sidebarToggle ? { 'my-2 ml-1': 'ml-0' } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Poin
                            </span>
                        </a>
                        <div :class="sidebarToggle ? 'fixed left-[90px] -translate-y-8' : 'absolute top-0'"
                            class="invisible z-9999 opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100 pointer-events-none">
                            <div class="relative w-max">
                                <div
                                    class="whitespace-nowrap rounded-lg bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-lg">
                                    Poin
                                </div>
                                <div class="absolute -left-1.5 top-1/2 h-3 w-4 -translate-y-1/2 rotate-45 bg-white">
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Menu Item Langganan -->

                    <!-- Menu Item Testimoni -->
                    <li class="relative group">
                        <a href="{{ route('testimoni.index') }}" class="menu-item"
                            :class="{
                                'menu-item-active': {{ request()->routeIs('testimoni.*') ? 'true' : 'false' }},
                                'menu-item-inactive':
                                    !{{ request()->routeIs('testimoni.*') ? 'true' : 'false' }}
                            }">
                            <i class="fas fa-comments fa-lg"
                                :class="{
                                    'menu-item-icon-active': {{ request()->routeIs('testimoni.*') ? 'true' : 'false' }},
                                    'menu-item-icon-inactive':
                                        !{{ request()->routeIs('testimoni.*') ? 'true' : 'false' }},
                                    ...(sidebarToggle ? { 'my-2 ml-1': 'ml-0' } : {})
                                }"></i>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Testimoni
                            </span>
                        </a>
                        <div :class="sidebarToggle ? 'fixed left-[90px] -translate-y-8' : 'absolute top-0'"
                            class="invisible z-9999 opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100 pointer-events-none">
                            <div class="relative w-max">
                                <div
                                    class="whitespace-nowrap rounded-lg bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-lg">
                                    Testimoni
                                </div>
                                <div class="absolute -left-1.5 top-1/2 h-3 w-4 -translate-y-1/2 rotate-45 bg-white">
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Menu Item Testimoni -->
                </ul>
            </div>
        </nav>
        <!-- Sidebar Menu -->
    </div>
</aside>
