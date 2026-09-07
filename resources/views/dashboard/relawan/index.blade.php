@extends('layouts.dashboard')

@section('page_title', 'Selamat datang, ' . auth()->user()->name)
@section('page_description', 'Datang siap, pulang berarti.')

@section('content')

@php
    $user = auth()->user();
    $basicTotal = App\Models\Module::where('level', 'basic')->count();
    $passedBasic = $user->moduleAttempts()->where('passed', true)->whereHas('module', fn ($q) => $q->where('level', 'basic'))->count();
    $apps = $user->volunteerApplications()->count();
    $reports = $user->visitReports()->count();
    $recent = $user->volunteerApplications()->with('panti')->latest()->limit(3)->get();
    $progress = $basicTotal > 0 ? round($passedBasic / $basicTotal * 100) : 0;
@endphp

<x-card class="mb-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-stone-ink">Progres Relawan Siap</h2>
            <p class="mt-1 text-sm text-stone-gray">{{ $passedBasic }} dari {{ $basicTotal }} modul dasar lulus.</p>
        </div>
        @if ($passedBasic >= $basicTotal)
            <span class="inline-flex items-center gap-2 rounded-full bg-urgency-green/10 px-4 py-1.5 text-sm font-semibold text-urgency-green">
                <x-icon name="shield" class="h-4 w-4" /> Relawan Siap
            </span>
        @endif
    </div>
    <div class="stock-bar mt-4">
        <span style="width: {{ $progress }}%; background: {{ $progress >= 100 ? '#16a34a' : '#0f766e' }};"></span>
    </div>
</x-card>

<div class="grid gap-6 sm:grid-cols-3">
    <x-card class="p-5"><p class="text-2xl font-bold text-stone-ink">{{ $passedBasic }}/{{ $basicTotal }}</p><p class="text-sm text-stone-gray">Modul dasar lulus</p></x-card>
    <x-card class="p-5"><p class="text-2xl font-bold text-stone-ink">{{ $apps }}</p><p class="text-sm text-stone-gray">Pengajuan kegiatan</p></x-card>
    <x-card class="p-5"><p class="text-2xl font-bold text-stone-ink">{{ $reports }}</p><p class="text-sm text-stone-gray">Laporan kunjungan</p></x-card>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <x-card>
        <h3 class="text-base font-semibold text-stone-ink mb-4">Pengajuan Terbaru</h3>
        <div class="space-y-3">
            @forelse ($recent as $a)
                <div class="flex items-center justify-between rounded-xl border border-border-soft bg-warm-bg px-4 py-3">
                    <div>
                        <p class="text-sm font-semibold text-stone-ink">{{ $a->panti->name }}</p>
                        <p class="text-xs text-stone-gray">{{ ucfirst($a->activity_type) }}</p>
                    </div>
                    <x-status-badge :status="$a->status" />
                </div>
            @empty
                <p class="text-sm text-stone-gray">Perjalananmu belum dimulai.</p>
            @endforelse
        </div>
    </x-card>

    <x-card class="flex flex-col justify-between gap-6">
        <div>
            <h3 class="text-base font-semibold text-stone-ink">Langkah berikutnya</h3>
            <p class="mt-2 text-sm text-stone-gray">
                @if ($passedBasic < $basicTotal)
                    Lengkapi modul dasar untuk membuka pengajuan kunjungan.
                @else
                    Kamu siap! Temukan panti yang menunggu kehadiranmu.
                @endif
            </p>
        </div>
        <div class="flex gap-3">
            @if ($passedBasic < $basicTotal)
                <a href="{{ route('relawan.modules.index') }}" class="flex-1 rounded-lg bg-teal-forest px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-teal-hover">Buka Modul</a>
            @else
                <a href="{{ route('relawan.applications.search') }}" class="flex-1 rounded-lg bg-teal-forest px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-teal-hover">Cari Panti</a>
            @endif
            <a href="{{ route('relawan.reports.index') }}" class="flex-1 rounded-lg border border-border-soft px-4 py-2.5 text-center text-sm font-semibold text-stone-gray hover:border-teal-forest hover:text-teal-forest">Laporan</a>
        </div>
    </x-card>
</div>

@endsection
