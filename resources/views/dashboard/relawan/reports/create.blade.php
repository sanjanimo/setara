@extends('layouts.dashboard')
@section('title', 'Laporan Kunjungan')
@section('page_title', 'Laporan Kunjungan')

@section('content')
    <x-card>
        <p class="text-sm text-stone-gray mb-6">Panti: <strong class="text-stone-ink">{{ $application->panti->name }}</strong></p>
        <form method="POST" action="{{ route('relawan.reports.store', $application) }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-stone-gray">Tanggal Kegiatan</label>
                <input type="date" name="activity_date" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-gray">Ringkasan Kegiatan</label>
                <textarea name="summary" rows="4" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest" placeholder="Ceritakan apa yang kamu lakukan..."></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="follow_up_needed" id="follow_up" value="1" class="rounded border-border-soft text-teal-forest focus:ring-teal-forest">
                <label for="follow_up" class="text-sm text-stone-gray">Panti membutuhkan tindak lanjut / bantuan tambahan</label>
            </div>
            <div class="flex justify-end">
                <x-primary-button>Kirim Laporan</x-primary-button>
            </div>
        </form>
    </x-card>
@endsection
