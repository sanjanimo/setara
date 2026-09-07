@extends('layouts.dashboard')

@section('page_title', 'Selamat datang, ' . auth()->user()->name)
@section('page_description', 'Kebaikanmu selalu punya alamat yang tepat.')

@section('content')

@php
    $user = auth()->user();
    $total = $user->donations()->count();
    $pending = $user->donations()->where('status', 'diajukan')->count();
    $confirmed = $user->donations()->where('status', 'dikonfirmasi')->count();
    $done = $user->donations()->where('status', 'selesai')->count();
    $recent = $user->donations()->with('panti')->latest()->limit(4)->get();
@endphp

<div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
    <x-card class="p-5"><p class="text-2xl font-bold text-stone-ink">{{ $total }}</p><p class="text-sm text-stone-gray">Total niat bantuan</p></x-card>
    <x-card class="p-5"><p class="text-2xl font-bold text-warm-amber">{{ $pending }}</p><p class="text-sm text-stone-gray">Menunggu konfirmasi</p></x-card>
    <x-card class="p-5"><p class="text-2xl font-bold text-teal-forest">{{ $confirmed }}</p><p class="text-sm text-stone-gray">Siap dikoordinasikan</p></x-card>
    <x-card class="p-5"><p class="text-2xl font-bold text-urgency-green">{{ $done }}</p><p class="text-sm text-stone-gray">Kebaikan tuntas</p></x-card>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
    <x-card>
        <h3 class="text-base font-semibold text-stone-ink mb-4">Bantuan Terakhirmu</h3>
        <div class="space-y-3">
            @forelse ($recent as $d)
                <div class="flex items-center justify-between rounded-xl border border-border-soft bg-warm-bg px-4 py-3">
                    <div>
                        <p class="text-sm font-semibold text-stone-ink">{{ $d->panti->name ?? '-' }}</p>
                        <p class="text-xs text-stone-gray">{{ $d->need->title ?? 'Bantuan umum' }} • {{ $d->created_at->format('d M Y') }}</p>
                    </div>
                    <x-status-badge :status="$d->status" />
                </div>
            @empty
                <p class="text-sm text-stone-gray">Kebaikan pertamamu bisa dimulai hari ini.</p>
            @endforelse
        </div>
    </x-card>

    <x-card class="grain relative overflow-hidden bg-teal-forest text-white">
        <h3 class="text-lg font-bold">Mulai dari yang paling mendesak</h3>
        <p class="mt-2 text-sm text-teal-100">Pin merah yang berdenyut di peta menandai panti yang paling membutuhkan hari ini.</p>
        <a href="{{ route('map.index') }}" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-teal-forest transition hover:bg-teal-50">
            Lihat Peta Kebutuhan <x-icon name="arrow-right" class="h-4 w-4" />
        </a>
    </x-card>
</div>

@endsection
