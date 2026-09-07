@extends('layouts.dashboard')
@section('title', 'Pengajuan Saya')
@section('page_title', 'Pengajuan Saya')

@section('content')
    @if($applications->isEmpty())
        <x-empty-state icon="clipboard" title="Belum ada pengajuan" description="Kamu belum mengajukan kunjungan atau pendampingan ke panti manapun.">
            <a href="{{ route('relawan.applications.search') }}" class="inline-flex items-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">Cari Panti</a>
        </x-empty-state>
    @else
        <div class="space-y-4">
            @foreach($applications as $app)
                <x-card class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-stone-ink">{{ $app->panti->name }}</h3>
                        <p class="text-sm text-stone-gray">{{ ucfirst($app->activity_type) }} • {{ $app->created_at->format('d M Y') }}</p>
                        @if($app->youthProfile)
                            <p class="text-xs text-teal-forest mt-1">Mentor untuk: {{ $app->youthProfile->initials }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <x-status-badge :status="$app->status" />

                        @if($app->status === 'disetujui' && !$app->visitReport)
                            <a href="{{ route('relawan.reports.create', $app) }}" class="rounded-lg bg-warm-amber px-3 py-1.5 text-xs font-bold text-white hover:opacity-90">
                                Buat Laporan
                            </a>
                        @endif
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
@endsection
