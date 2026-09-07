<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — SETARA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/home/logoSetara.png') }}">
</head>

<body class="min-h-screen bg-warm-bg font-sans text-stone-ink antialiased" x-data="{ sidebarOpen: false }">

    @php
        $sidebarView = match (auth()->user()->role) {
            'admin' => 'partials.sidebar.admin',
            'panti' => 'partials.sidebar.panti',
            'relawan' => 'partials.sidebar.relawan',
            'donatur' => 'partials.sidebar.donatur',
            default => 'partials.sidebar.donatur',
        };
    @endphp

    <div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]">

        <!-- Desktop Sidebar -->
        <aside class="hidden border-r border-border-soft bg-warm-surface lg:block">
            <div class="flex h-16 items-center border-b border-border-soft px-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <!-- <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-forest text-white">
                        <x-icon name="heart" class="h-4 w-4" />
                    </span> -->
                    <img src="{{ asset('images/home/logoSetara.png') }}" alt="Logo"
                        class="h-9 w-auto" />
                    <span class="font-accent text-lg font-bold text-teal-forest">SETARA</span>
                </a>
            </div>

            <div class="p-4">
                @include($sidebarView)
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
            <div class="fixed inset-0 bg-stone-ink/40" @click="sidebarOpen = false"></div>

            <aside x-show="sidebarOpen" @click.outside="sidebarOpen = false"
                class="fixed inset-y-0 left-0 w-72 border-r border-border-soft bg-warm-surface">
                <div class="flex h-16 items-center justify-between border-b border-border-soft px-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-forest text-white">
                            <x-icon name="heart" class="h-4 w-4" />
                        </span>
                        <span class="font-heading text-lg font-bold text-teal-forest">SETARA</span>
                    </a>

                    <button type="button"
                        class="rounded-lg p-2 text-stone-gray transition hover:bg-warm-bg hover:text-teal-forest"
                        @click="sidebarOpen = false">
                        <x-icon name="x-mark" class="h-5 w-5" />
                    </button>
                </div>

                <div class="p-4">
                    @include($sidebarView)
                </div>
            </aside>
        </div>

        <!-- Main Area -->
        <div class="flex min-h-screen flex-col">

            <!-- Topbar -->
            <header class="sticky top-0 z-40 border-b border-border-soft bg-warm-surface/95 backdrop-blur">
                <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

                    <div class="flex items-center gap-3">
                        <button type="button"
                            class="rounded-lg p-2 text-stone-gray transition hover:bg-warm-bg hover:text-teal-forest lg:hidden"
                            @click="sidebarOpen = true">
                            <x-icon name="menu" class="h-6 w-6" />
                        </button>

                        <div>
                            <h1 class="font-heading text-lg font-semibold text-stone-ink">
                                @yield('page_title', 'Dashboard')
                            </h1>

                            <p class="text-xs text-stone-gray">
                                @yield('page_description', '')
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-medium text-stone-ink">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs uppercase tracking-wide text-stone-gray">
                                {{ auth()->user()->role }}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="rounded-lg border border-border-soft px-4 py-2 text-sm font-medium text-stone-gray transition hover:border-teal-forest hover:text-teal-forest">
                                Keluar
                            </button>
                        </form>
                    </div>

                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">

                    @if (session('success'))
                        <div
                            class="mb-6 rounded-xl border border-urgency-green/30 bg-urgency-green/10 px-4 py-3 text-sm text-urgency-green">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="mb-6 rounded-xl border border-urgency-red/30 bg-urgency-red/10 px-4 py-3 text-sm text-urgency-red">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')

                </div>
            </main>

        </div>

    </div>

</body>

</html>
