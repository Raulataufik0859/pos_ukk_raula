<!DOCTYPE html>
<html lang="id" class="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'POS Raula') - Aplikasi Kasir Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" type="image/jpg" href="{{ asset('imagelogo/lopos.jpg') }}">

    <style>
        /* Sidebar Collapsed Mode */
        #sidebar.collapsed {
            width: 4.5rem;
        }

        #sidebar.collapsed .sidebar-text {
            display: none;
        }

        #sidebar.collapsed .sidebar-item {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        #main-content.collapsed {
            margin-left: 4.5rem;
        }

        @media (min-width: 1024px) {
            #main-content {
                margin-left: 16rem;
            }

            #main-content.collapsed {
                margin-left: 4.5rem;
            }
        }
    </style>
</head>

<body class="bg-sky-50 dark:bg-gray-950 font-sans antialiased transition-colors duration-300">

    <div class="h-screen flex overflow-hidden">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300">

            {{-- Topbar --}}
            <header
                class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 h-[72px] flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20 shadow-sm transition-colors duration-300">

                <div class="flex items-center gap-3">
                    {{-- Mobile Toggle --}}
                    <button id="sidebar-toggle"
                        class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- Desktop Collapse Toggle --}}
                    <button id="sidebar-collapse"
                        class="hidden lg:flex items-center justify-center p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition"
                        title="Collapse / Expand Sidebar">
                        <svg id="icon-expanded" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                        <svg id="icon-collapsed" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>

                    {{-- Logo + Brand --}}
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('imagelogo/lopos.jpg') }}" alt="Logo"
                            class="w-10 h-10 rounded-xl object-contain bg-white p-0.5 shadow-md shadow-indigo-500/25 shrink-0">
                        <div class="leading-tight hidden sm:block">
                            <div class="font-bold text-gray-900 dark:text-white text-[15px]">POS Raula</div>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400">Point of Sale</div>
                        </div>
                    </div>

                    {{-- Separator --}}
                    <div class="hidden md:block w-px h-8 bg-gray-200 dark:bg-gray-700 mx-1"></div>

                    {{-- Judul Halaman --}}
                    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 tracking-tight">
                        @if (request()->is('dashboard'))
                            Dashboard
                        @elseif(request()->is('profile*'))
                            Profil Saya
                        @elseif(request()->is('admin/users*') || request()->is('users*'))
                            Pengguna
                        @elseif(request()->is('produk*'))
                            Produk
                        @elseif(request()->is('kategori*'))
                            Kategori Produk
                        @elseif(request()->is('pemasok*'))
                            Pemasok
                        @elseif(request()->is('pembelian*'))
                            Pembelian
                        @elseif(request()->is('laporan*'))
                            Laporan
                        @elseif(request()->is('penjualan*'))
                            Penjualan
                        @else
                            {{ trim($__env->yieldContent('header')) ?: '' }}
                        @endif
                    </h2>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">

                    {{-- Dark Mode Toggle --}}
                    <button id="theme-toggle" type="button"
                        class="p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                        </svg>
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    </button>

                    {{-- Tanggal & Jam --}}
                    <div class="hidden md:flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <span id="navbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <span id="navbar-clock" class="font-medium text-indigo-600 dark:text-indigo-400 tabular-nums">
                            --:--:--
                        </span>
                    </div>

                    {{-- Logout (upgraded) --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium
                                   text-gray-600 dark:text-gray-300
                                   bg-gray-100 hover:bg-rose-50 dark:bg-gray-800 dark:hover:bg-rose-900/30
                                   hover:text-rose-600 dark:hover:text-rose-400
                                   border border-transparent hover:border-rose-200 dark:hover:border-rose-800
                                   transition shadow-sm"
                            title="Keluar dari aplikasi">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Dark Mode
        const themeToggleBtn = document.getElementById('theme-toggle');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');

        if (localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            lightIcon?.classList.remove('hidden');
            darkIcon?.classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            lightIcon?.classList.add('hidden');
            darkIcon?.classList.remove('hidden');
        }

        themeToggleBtn?.addEventListener('click', function() {
            lightIcon?.classList.toggle('hidden');
            darkIcon?.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });

        // Sidebar Mobile
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
        });

        // Sidebar Collapse (Desktop)
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const collapseBtn = document.getElementById('sidebar-collapse');
        const iconExpanded = document.getElementById('icon-expanded');
        const iconCollapsed = document.getElementById('icon-collapsed');

        function updateCollapseIcon(isCollapsed) {
            if (isCollapsed) {
                iconExpanded?.classList.add('hidden');
                iconCollapsed?.classList.remove('hidden');
            } else {
                iconExpanded?.classList.remove('hidden');
                iconCollapsed?.classList.add('hidden');
            }
        }

        // Load saved state
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar?.classList.add('collapsed');
            mainContent?.classList.add('collapsed');
            updateCollapseIcon(true);
        } else {
            updateCollapseIcon(false);
        }

        collapseBtn?.addEventListener('click', function() {
            sidebar?.classList.toggle('collapsed');
            mainContent?.classList.toggle('collapsed');

            const isCollapsed = sidebar?.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            updateCollapseIcon(isCollapsed);
        });

        // Clock
        function updateNavbarClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const el = document.getElementById('navbar-clock');
            if (el) el.textContent = timeString + ' WIB';
        }
        updateNavbarClock();
        setInterval(updateNavbarClock, 1000);

    </script>

    @include('components.toast')
</body>

</html>
