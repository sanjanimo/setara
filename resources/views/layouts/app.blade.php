<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SETARA — Bantuan tepat, karena kebutuhan terlihat')</title>
    <meta name="description"
        content="SETARA menghubungkan panti, relawan, dan donatur berdasarkan kebutuhan paling mendesak, bukan popularitas.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/home/logoSetara.png') }}">
</head>

<body class="flex min-h-screen flex-col font-sans bg-warm-bg text-stone-ink antialiased" x-data="{ mobileMenuOpen: false }">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 border-b border-border-soft bg-warm-surface/95 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">

                <!-- Brand (Logo Baru di Navbar) -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/home/logoSetara.png') }}" alt="SETARA Logo" class="h-12 w-auto object-contain">
                    <span class="text-lg font-accent italic text-teal-forest">SETARA</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden items-center gap-8 lg:flex">
                    <a href="{{ route('home') }}"
                        class="text-sm font-medium text-stone-gray transition hover:text-teal-forest">
                        Beranda
                    </a>
                    <a href="{{ route('map.index') }}"
                        class="text-sm font-medium text-stone-gray transition hover:text-teal-forest">
                        Peta Kebutuhan
                    </a>
                    <a href="{{ route('home') }}#cara-kerja"
                        class="text-sm font-medium text-stone-gray transition hover:text-teal-forest">Cara Kerja</a>
                    <a href="{{ route('home') }}#relawan"
                        class="text-sm font-medium text-stone-gray transition hover:text-teal-forest">Relawan</a>
                </div>

                <!-- Auth Actions -->
                <div class="hidden items-center gap-3 lg:flex">
                    @auth
                        <a href="{{ route(
                            match (Auth::user()->role) {
                                'admin' => 'admin.dashboard',
                                'panti' => 'panti.dashboard',
                                'relawan' => 'relawan.dashboard',
                                'donatur' => 'donatur.dashboard',
                                default => 'home',
                            },
                        ) }}"
                            class="text-sm font-medium text-stone-gray transition hover:text-teal-forest">
                            Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="rounded-lg border border-border-soft px-4 py-2 text-sm font-medium text-stone-gray transition hover:border-teal-forest hover:text-teal-forest">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium text-stone-gray transition hover:text-teal-forest">
                            Masuk
                        </a>

                        <a href="{{ route('register') }}"
                            class="rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-teal-hover">
                            Daftar
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button type="button"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-stone-gray transition hover:bg-warm-bg hover:text-teal-forest lg:hidden"
                    @click="mobileMenuOpen = !mobileMenuOpen">
                    <span x-show="!mobileMenuOpen">
                        <x-icon name="menu" class="h-6 w-6" />
                    </span>
                    <span x-show="mobileMenuOpen" x-cloak>
                        <x-icon name="x-mark" class="h-6 w-6" />
                    </span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="border-t border-border-soft bg-warm-surface lg:hidden">
            <div class="space-y-1 px-4 pb-4 pt-3">
                <a href="{{ route('home') }}"
                    class="block rounded-lg px-3 py-2 text-sm font-medium text-stone-gray transition hover:bg-warm-bg hover:text-teal-forest">
                    Beranda
                </a>
                <a href="{{ route('map.index') }}"
                    class="text-sm font-medium text-stone-gray transition hover:text-teal-forest">
                    Peta Kebutuhan
                </a>
                <a href="{{ route('home') }}#cara-kerja"
                    class="block rounded-lg px-3 py-2 text-sm font-medium text-stone-gray transition hover:bg-warm-bg hover:text-teal-forest">
                    Cara Kerja
                </a>
                <a href="{{ route('home') }}#relawan"
                    class="block rounded-lg px-3 py-2 text-sm font-medium text-stone-gray transition hover:bg-warm-bg hover:text-teal-forest">
                    Relawan
                </a>

                <div class="border-t border-border-soft pt-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-stone-gray transition hover:bg-warm-bg hover:text-teal-forest">
                            Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg border border-border-soft px-3 py-2 text-left text-sm font-medium text-stone-gray transition hover:border-teal-forest hover:text-teal-forest">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-stone-gray transition hover:bg-warm-bg hover:text-teal-forest">
                            Masuk
                        </a>

                        <a href="{{ route('register') }}"
                            class="mt-2 block rounded-lg bg-teal-forest px-3 py-2 text-center text-sm font-medium text-white transition hover:bg-teal-hover">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-border-soft bg-warm-surface">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-10 md:grid-cols-3">

                <div>
                    <!-- Logo Baru di Footer -->
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/home/logoSetara.png') }}" alt="SETARA Logo" class="h-12 w-auto object-contain">
                        <span class=" text-lg font-accent italic text-teal-forest">SETARA</span>
                    </div>

                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-stone-gray">
                        Bantuan tepat, karena kebutuhan terlihat. Platform penghubung panti, relawan, dan donatur
                        berbasis kebutuhan mendesak.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-stone-ink">Fokus SDG</h3>
                    <ul class="mt-4 space-y-2 text-sm text-stone-gray">
                        <li>SDG 11 — Kota dan Komunitas Berkelanjutan</li>
                        <li>SDG 8 — Pekerjaan Layak dan Pertumbuhan Ekonomi</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-stone-ink">Catatan</h3>
                    <p class="mt-4 text-sm leading-relaxed text-stone-gray">
                        Data pada aplikasi ini merupakan data simulasi untuk keperluan kompetisi. SETARA mengutamakan
                        martabat, privasi, dan keamanan warga panti.
                    </p>
                </div>

            </div>

            <div class="mt-10 border-t border-border-soft pt-6">
                <p class="text-xs text-stone-gray">
                    &copy; 2026 SETARA. Dibuat untuk ITechno Cup 2026.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>
