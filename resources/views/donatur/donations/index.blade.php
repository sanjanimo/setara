@extends('layouts.dashboard')

@section('title', 'Donasi Saya')
@section('page_title', 'Donasi Saya')
@section('page_description', 'Pantau niat bantuan yang sudah kamu kirim.')

@section('content')
<x-admin-filter :action="route('donatur.donations.index')" search-placeholder="Cari nama panti...">
    <select name="status" class="rounded-lg border-border-soft bg-warm-surface text-sm">
        <option value="">Semua Status</option>
        @foreach (['diajukan', 'dikonfirmasi', 'ditolak', 'selesai'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
</x-admin-filter>

    @if ($donations->isEmpty())
        <x-empty-state
            icon="heart"
            title="Belum ada niat bantuan"
            description="Kamu dapat mulai membantu dengan melihat peta kebutuhan atau detail panti."
        >
            <a href="{{ route('map.index') }}" class="inline-flex items-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">
                Lihat Peta Kebutuhan
            </a>
        </x-empty-state>
    @else

        <x-card class="overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border-soft text-sm">
                    <thead class="bg-warm-bg text-left text-xs font-semibold uppercase tracking-wide text-stone-gray">
                        <tr>
                            <th class="px-4 py-3">Panti</th>
                            <th class="px-4 py-3">Kebutuhan</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Detail</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-border-soft">
                        @foreach ($donations as $donation)
                            <tr>
                                <td class="px-4 py-4">
                                    <a href="{{ route('pantis.show', $donation->panti->slug ?? '') }}" class="font-medium text-teal-forest hover:text-teal-hover">
                                        {{ $donation->panti->name ?? '-' }}
                                    </a>
                                </td>

                                <td class="px-4 py-4 text-stone-gray">
                                    {{ $donation->need->title ?? 'Bantuan umum' }}
                                </td>

                                <td class="px-4 py-4 text-stone-gray">
                                    {{ ucfirst($donation->type) }}
                                </td>

                                <td class="px-4 py-4 text-stone-gray">
                                    @if ($donation->type === 'barang')
                                        {{ $donation->quantity ?? '-' }} {{ $donation->need->unit ?? '' }}
                                    @else
                                        {{ Str::limit($donation->message, 60) ?: 'Bantuan tenaga' }}
                                    @endif
                                </td>

                                <td class="px-4 py-4">
                                    <x-status-badge :status="$donation->status" />
                                </td>

                                <td class="px-4 py-4 text-stone-gray">
                                    {{ $donation->created_at->format('d M Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

    @endif

@endsection
