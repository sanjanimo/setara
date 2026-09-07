<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'SETARA') - SETARA</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/png" href="{{ asset('images/home/logoSetara.png') }}">
    </head>
    <!-- Tambahan py-10 sm:py-16 untuk memberikan jarak atas & bawah dari ujung browser -->
    <body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center px-4 py-10 sm:py-16">

        <div class="w-full max-w-md">
            <!-- Header Brand & Logo -->
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2.5">
                    <img src="{{ asset('images/home/logoSetara.png') }}" alt="SETARA Logo" class="h-8 w-auto object-contain">
                    <span class="text-3xl font-bold text-teal-forest">SETARA</span>
                </a>
                <p class="text-sm text-gray-500 mt-1">
                    Bantuan tepat, karena kebutuhan terlihat.
                </p>
            </div>

            <!-- Card Content -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                @yield('content')
            </div>

            <p class="text-center text-xs text-gray-500 mt-6">
                &copy; 2026 SETARA. Data demo untuk kompetisi.
            </p>
        </div>

    </body>
</html>
