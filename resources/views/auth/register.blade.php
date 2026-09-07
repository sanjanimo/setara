@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
    <h1 class="text-xl font-semibold text-gray-900">Daftar Akun</h1>
    <p class="mt-1 text-sm text-gray-500">
        Pilih peran sesuai kebutuhanmu.
    </p>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
        </div>

        <div>
            <span class="block text-sm font-medium text-gray-700">Peran</span>

            <div class="mt-1 grid grid-cols-3 gap-1 rounded-xl border border-gray-200 bg-gray-100 p-1">
                {{-- Donatur --}}
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="donatur" class="peer sr-only" @checked(old('role', 'donatur') === 'donatur')>
                    <div
                        class="flex flex-col items-center justify-center rounded-lg py-2 text-gray-500 transition-all hover:text-gray-800 peer-checked:bg-white peer-checked:font-medium peer-checked:text-teal-700 peer-checked:shadow-xs">
                        <x-icon name="heart" class="mb-1 h-5 w-5" />
                        <span class="text-xs">Donatur</span>
                    </div>
                </label>

                {{-- Relawan --}}
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="relawan" class="peer sr-only" @checked(old('role') === 'relawan')>
                    <div
                        class="flex flex-col items-center justify-center rounded-lg py-2 text-gray-500 transition-all hover:text-gray-800 peer-checked:bg-white peer-checked:font-medium peer-checked:text-teal-700 peer-checked:shadow-xs">
                        <x-icon name="users" class="mb-1 h-5 w-5" />
                        <span class="text-xs">Relawan</span>
                    </div>
                </label>

                {{-- Panti --}}
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="panti" class="peer sr-only" @checked(old('role') === 'panti')>
                    <div
                        class="flex flex-col items-center justify-center rounded-lg py-2 text-gray-500 transition-all hover:text-gray-800 peer-checked:bg-white peer-checked:font-medium peer-checked:text-teal-700 peer-checked:shadow-xs">
                        <x-icon name="home" class="mb-1 h-5 w-5" />
                        <span class="text-xs">Panti</span>
                    </div>
                </label>
            </div>
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
        </div>

        <div>
            <label for="organization_name" class="block text-sm font-medium text-gray-700">
                Organisasi / Komunitas / Panti
            </label>
            <input id="organization_name" name="organization_name" type="text" value="{{ old('organization_name') }}"
                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
        </div>

        <div x-data="{ show: false }" class="space-y-4">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative mt-1">
                    <input id="password" name="password" :type="show ? 'text' : 'password'" type="password" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-sm shadow-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-gray-400 hover:text-gray-600 focus:outline-none"
                        :aria-label="show ? 'Sembunyikan password' : 'Lihat password'">
                        <x-icon name="eye" x-show="!show" class="h-5 w-5" />
                        <x-icon name="eye-off" x-show="show" x-cloak class="h-5 w-5" />
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Minimal 8 karakter.
                </p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Konfirmasi Password
                </label>
                <div class="mt-1">
                    <input id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'"
                        type="password" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                </div>
            </div>
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-teal-600 px-4 py-2 font-medium text-white transition hover:bg-teal-700">
            Daftar
        </button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-teal-700 hover:text-teal-800">
            Masuk di sini
        </a>
    </p>

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
