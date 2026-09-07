@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('page_description', 'Perbarui data diri dan keamanan akun.')

@section('content')

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-urgency-red/30 bg-urgency-red/10 px-4 py-3 text-sm text-urgency-red">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-card>
        <form method="POST" action="{{ route('donatur.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-stone-gray">Email</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-bg text-sm text-stone-gray">
                <p class="mt-1 text-xs text-stone-gray">Email tidak dapat diubah.</p>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-stone-gray">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="phone" class="block text-sm font-medium text-stone-gray">Nomor Telepon</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                        class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                </div>
                <div>
                    <label for="organization_name" class="block text-sm font-medium text-stone-gray">Organisasi /
                        Komunitas</label>
                    <input id="organization_name" name="organization_name" type="text"
                        value="{{ old('organization_name', $user->organization_name) }}"
                        class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                </div>
            </div>

            <div class="border-t border-border-soft pt-6">
                <h2 class="text-base font-semibold text-stone-ink">Ganti Password (Opsional)</h2>
                <p class="mt-1 text-xs text-stone-gray">Kosongkan jika tidak ingin mengganti password.</p>

                <div x-data="{ show: false }" class="mt-4 grid gap-4 md:grid-cols-2">
                    {{-- Password Baru --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-stone-gray">Password Baru</label>
                        <div class="relative mt-1">
                            <input id="password" name="password" :type="show ? 'text' : 'password'" type="password"
                                class="w-full rounded-lg border-border-soft bg-warm-surface pr-10 text-sm focus:border-teal-forest focus:ring-teal-forest">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-stone-gray hover:text-teal-forest focus:outline-none"
                                :aria-label="show ? 'Sembunyikan password' : 'Lihat password'">
                                <x-icon name="eye" x-show="!show" class="h-5 w-5" />
                                <x-icon name="eye-off" x-show="show" x-cloak class="h-5 w-5" />
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-stone-gray">Konfirmasi
                            Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation"
                                :type="show ? 'text' : 'password'" type="password"
                                class="w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <x-primary-button>Simpan Perubahan</x-primary-button>
            </div>
        </form>
    </x-card>

@endsection
