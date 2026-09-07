@extends('layouts.dashboard')

@section('page_title', 'Selamat datang, ' . auth()->user()->name)
@section('page_description', 'Kelola pantimu dengan martabat.')

@section('content')

@php $panti = auth()->user()->panti; @endphp

@if (! $panti)
    <x-empty-state icon="building" title="Lengkapi profil panti" description="Akun kamu terdaftar sebagai panti, tetapi profil panti belum dibuat.">
        <a href="{{ route('panti.profile.edit') }}" class="inline-flex items-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">Lengkapi Profil Panti</a>
    </x-empty-state>
@else
    @php
        $activeNeeds = $panti->needs()->active()->count();
        $pendingDonations = $panti->donations()->where('status', 'diajukan')->count();
        $pendingVolunteers = $panti->volunteerApplications()->where('status', 'diajukan')->count();
        $youthCount = $panti->youthProfiles()->count();
        $latestDonations = $panti->donations()->with('need')->latest()->limit(3)->get();
        $latestVolunteers = $panti->volunteerApplications()->with('user')->latest()->limit(3)->get();
    @endphp

    <x-card class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-stone-ink">{{ $panti->name }}</h2>
            <p class="mt-1 text-sm text-stone-gray">{{ ucfirst($panti->type) }} • {{ $panti->city }} • Verifikasi: {{ ucfirst($panti->verification_status) }}</p>
        </div>
        <x-urgency-badge :status="$panti->urgency_status" />
    </x-card>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('panti.needs.index') }}" class="card-craft rounded-2xl border border-border-soft bg-warm-surface p-5 transition hover:-translate-y-0.5">
            <p class="text-2xl font-bold text-stone-ink">{{ $activeNeeds }}</p>
            <p class="text-sm text-stone-gray">Kebutuhan aktif</p>
        </a>
        <a href="{{ route('panti.donations.index') }}" class="card-craft rounded-2xl border border-border-soft bg-warm-surface p-5 transition hover:-translate-y-0.5">
            <p class="text-2xl font-bold text-stone-ink">{{ $pendingDonations }}</p>
            <p class="text-sm text-stone-gray">Donasi menunggu konfirmasi</p>
        </a>
        <a href="{{ route('panti.volunteers.index') }}" class="card-craft rounded-2xl border border-border-soft bg-warm-surface p-5 transition hover:-translate-y-0.5">
            <p class="text-2xl font-bold text-stone-ink">{{ $pendingVolunteers }}</p>
            <p class="text-sm text-stone-gray">Relawan menunggu persetujuan</p>
        </a>
        <a href="{{ route('panti.youth.index') }}" class="card-craft rounded-2xl border border-border-soft bg-warm-surface p-5 transition hover:-translate-y-0.5">
            <p class="text-2xl font-bold text-stone-ink">{{ $youthCount }}</p>
            <p class="text-sm text-stone-gray">Profil youth</p>
        </a>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-card>
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-stone-ink">Donasi Terbaru</h3>
                <a href="{{ route('panti.donations.index') }}" class="text-sm font-semibold text-teal-forest hover:text-teal-hover">Semua →</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($latestDonations as $d)
                    <div class="flex items-center justify-between rounded-xl border border-border-soft bg-warm-bg px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-stone-ink">{{ $d->donor_name }}</p>
                            <p class="text-xs text-stone-gray">{{ $d->need->title ?? 'Bantuan umum' }} • {{ ucfirst($d->type) }}</p>
                        </div>
                        <x-status-badge :status="$d->status" />
                    </div>
                @empty
                    <p class="text-sm text-stone-gray">Belum ada donasi masuk.</p>
                @endforelse
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-stone-ink">Relawan Terbaru</h3>
                <a href="{{ route('panti.volunteers.index') }}" class="text-sm font-semibold text-teal-forest hover:text-teal-hover">Semua →</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($latestVolunteers as $v)
                    <div class="flex items-center justify-between rounded-xl border border-border-soft bg-warm-bg px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-stone-ink">{{ $v->user->name }}</p>
                            <p class="text-xs text-stone-gray">{{ ucfirst($v->activity_type) }}</p>
                        </div>
                        <x-status-badge :status="$v->status" />
                    </div>
                @empty
                    <p class="text-sm text-stone-gray">Belum ada pengajuan relawan.</p>
                @endforelse
            </div>
        </x-card>
    </div>
@endif

@endsection
