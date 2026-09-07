@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <h1 class="text-xl font-semibold text-gray-900">Masuk</h1>
    <p class="mt-1 text-sm text-gray-500">
        Gunakan akun demo atau akun yang sudah kamu daftarkan.
    </p>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
        </div>

        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-stone-gray">Password</label>
            <div class="relative mt-1">
                <input id="password" name="password" :type="show ? 'text' : 'password'" required placeholder="••••••••"
                    class="w-full rounded-lg border border-border-soft bg-warm-surface py-2 pr-11 text-sm focus:border-teal-forest">

                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-stone-gray transition hover:text-teal-forest"
                    :aria-label="show ? 'Sembunyikan password' : 'Lihat password'">
                    <x-icon name="eye" x-show="!show" class="h-5 w-5" />
                    <x-icon name="eye-off" x-show="show" x-cloak class="h-5 w-5" />
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                Ingat saya
            </label>

            <a href="{{ route('register') }}" class="text-sm text-teal-700 hover:text-teal-800">
                Belum punya akun?
            </a>
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-teal-600 px-4 py-2 font-medium text-white transition hover:bg-teal-700">
            Masuk
        </button>
    </form>

    <!-- Tombol Kembali Interaktif (Posisi Tengah) -->
    <div class="mt-6 text-center">
        <a href="{{ route('home') }}"
            class="group inline-flex items-center justify-center gap-1.5 text-xs font-medium text-gray-500 transition-colors hover:text-teal-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Halaman Utama
        </a>
    </div>
@endsection
