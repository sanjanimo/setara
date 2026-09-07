@extends('layouts.dashboard')

@section('title', 'Laporan Kunjungan')
@section('page_title', 'Laporan Kunjungan')
@section('page_description', 'Riwayat laporan kegiatan yang sudah kamu buat.')

@section('content')

    @if ($reports->isEmpty())
        <x-empty-state icon="check-circle" title="Belum ada laporan" description="Laporan kunjungan yang kamu buat akan muncul di sini." />
    @else
        <div class="space-y-4">
            @foreach ($reports as $report)
                <x-card>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-stone-ink">{{ $report->panti->name ?? '-' }}</h3>
                            <p class="text-sm text-stone-gray">{{ $report->activity_date->format('d M Y') }}</p>
                        </div>
                        @if ($report->follow_up_needed)
                            <span class="rounded-full bg-warm-amber/10 px-3 py-1 text-xs font-semibold uppercase text-warm-amber">Perlu Tindak Lanjut</span>
                        @else
                            <span class="rounded-full bg-urgency-green/10 px-3 py-1 text-xs font-semibold uppercase text-urgency-green">Tuntas</span>
                        @endif
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-stone-gray">{{ $report->summary }}</p>
                </x-card>
            @endforeach
        </div>
    @endif

@endsection
