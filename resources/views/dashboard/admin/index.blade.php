@extends('layouts.dashboard')

@section('page_title', 'Selamat datang, ' . auth()->user()->name)
@section('page_description', 'Pantau ekosistem SETARA secara menyeluruh.')

@section('content')
@php
    $pantiPending = App\Models\Panti::where('verification_status', 'pending')->count();
    $pantiVerified = App\Models\Panti::where('verification_status', 'verified')->count();
    $donationsPending = App\Models\Donation::where('status', 'diajukan')->count();
    $volunteersPending = App\Models\VolunteerApplication::where('status', 'diajukan')->count();
    $recentLogs = App\Models\ActivityLog::with('user')->latest()->limit(6)->get();
@endphp

<div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
    <x-card class="flex items-center gap-4">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-warm-amber/10 text-warm-amber">
            <x-icon name="shield" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-2xl font-bold text-stone-ink">{{ $pantiPending }}</p>
            <p class="text-sm text-stone-gray">Panti menunggu verifikasi</p>
        </div>
    </x-card>

    <x-card class="flex items-center gap-4">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-forest/10 text-teal-forest">
            <x-icon name="building" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-2xl font-bold text-stone-ink">{{ $pantiVerified }}</p>
            <p class="text-sm text-stone-gray">Panti terverifikasi</p>
        </div>
    </x-card>

    <x-card class="flex items-center gap-4">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-warm-amber/10 text-warm-amber">
            <x-icon name="heart" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-2xl font-bold text-stone-ink">{{ $donationsPending }}</p>
            <p class="text-sm text-stone-gray">Donasi menunggu konfirmasi</p>
        </div>
    </x-card>

    <x-card class="flex items-center gap-4">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-forest/10 text-teal-forest">
            <x-icon name="users" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-2xl font-bold text-stone-ink">{{ $volunteersPending }}</p>
            <p class="text-sm text-stone-gray">Relawan menunggu persetujuan</p>
        </div>
    </x-card>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <x-card>
        <div class="flex items-center justify-between">
            <h2 class="text-base font-semibold text-stone-ink">Antrean Verifikasi</h2>
            <a href="{{ route('admin.verification.index') }}" class="text-sm font-semibold text-teal-forest hover:text-teal-hover">Lihat semua →</a>
        </div>
        @php $pending = App\Models\Panti::where('verification_status', 'pending')->latest()->limit(3)->get(); @endphp
        <div class="mt-4 space-y-3">
            @forelse ($pending as $p)
                <div class="flex items-center justify-between rounded-xl border border-border-soft bg-warm-bg px-4 py-3">
                    <div>
                        <p class="text-sm font-semibold text-stone-ink">{{ $p->name }}</p>
                        <p class="text-xs text-stone-gray">{{ $p->city }} • {{ ucfirst($p->type) }}</p>
                    </div>
                    <a href="{{ route('admin.verification.index') }}" class="text-xs font-bold text-warm-amber">Tinjau →</a>
                </div>
            @empty
                <p class="text-sm text-stone-gray">Tidak ada panti menunggu.</p>
            @endforelse
        </div>
    </x-card>

    <x-card>
        <h2 class="text-base font-semibold text-stone-ink">Denyut Sistem</h2>
        <div class="mt-4 space-y-3">
            @foreach ($recentLogs as $log)
                <div class="flex items-start gap-3 text-sm">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-warm-amber"></span>
                    <p class="text-stone-gray">{{ $log->description ?? $log->action }}
                        <span class="text-xs text-stone-gray/60">— {{ $log->created_at->diffForHumans() }}</span>
                    </p>
                </div>
            @endforeach
        </div>
    </x-card>
</div>
@endsection
