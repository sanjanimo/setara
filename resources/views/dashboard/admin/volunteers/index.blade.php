@extends('layouts.dashboard')

@section('title', 'Monitor Relawan')
@section('page_title', 'Monitor Relawan')
@section('page_description', 'Seluruh pengajuan relawan lintas panti.')

@section('content')
<x-admin-filter :action="route('admin.volunteers.index')" search-placeholder="Cari nama relawan, panti, atau kegiatan..." />

    @if ($applications->isEmpty())
        <x-empty-state icon="users" title="Belum ada pengajuan" description="Pengajuan relawan akan muncul di sini." />
    @else
        <x-card class="overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border-soft text-sm">
                    <thead class="bg-warm-bg text-left text-xs font-semibold uppercase tracking-wide text-stone-gray">
                        <tr>
                            <th class="px-4 py-3">Relawan</th>
                            <th class="px-4 py-3">Panti</th>
                            <th class="px-4 py-3">Kegiatan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach ($applications as $application)
                            <tr>
                                <td class="px-4 py-4 font-medium text-stone-ink">{{ $application->user->name ?? '-' }}</td>
                                <td class="px-4 py-4 text-stone-gray">{{ $application->panti->name ?? '-' }}</td>
                                <td class="px-4 py-4 text-stone-gray">{{ ucfirst($application->activity_type) }}</td>
                                <td class="px-4 py-4"><x-status-badge :status="$application->status" /></td>
                                <td class="px-4 py-4 text-stone-gray">{{ $application->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <div class="mt-6">{{ $applications->links() }}</div>
    @endif

@endsection
