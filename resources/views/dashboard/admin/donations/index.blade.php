@extends('layouts.dashboard')

@section('title', 'Monitor Donasi')
@section('page_title', 'Monitor Donasi')
@section('page_description', 'Seluruh niat bantuan lintas panti.')

@section('content')

    @if ($donations->isEmpty())
        <x-empty-state icon="heart" title="Belum ada donasi" description="Niat bantuan dari donatur akan muncul di sini." />
    @else
        <x-card class="overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border-soft text-sm">
                    <thead class="bg-warm-bg text-left text-xs font-semibold uppercase tracking-wide text-stone-gray">
                        <tr>
                            <th class="px-4 py-3">Donatur</th>
                            <th class="px-4 py-3">Panti</th>
                            <th class="px-4 py-3">Kebutuhan</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach ($donations as $donation)
                            <tr>
                                <td class="px-4 py-4 font-medium text-stone-ink">{{ $donation->donor_name }}</td>
                                <td class="px-4 py-4 text-stone-gray">{{ $donation->panti->name ?? '-' }}</td>
                                <td class="px-4 py-4 text-stone-gray">{{ $donation->need->title ?? 'Bantuan umum' }}</td>
                                <td class="px-4 py-4 text-stone-gray">{{ ucfirst($donation->type) }}</td>
                                <td class="px-4 py-4"><x-status-badge :status="$donation->status" /></td>
                                <td class="px-4 py-4 text-stone-gray">{{ $donation->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <div class="mt-6">{{ $donations->links() }}</div>
    @endif

@endsection
