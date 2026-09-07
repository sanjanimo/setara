@extends('layouts.dashboard')

@section('title', 'Log Aktivitas')
@section('page_title', 'Log Aktivitas')
@section('page_description', 'Jejak audit seluruh aksi penting dalam sistem.')

@section('content')
<x-admin-filter :action="route('admin.logs.index')" search-placeholder="Cari deskripsi, aksi, atau nama user..." />

    @if ($logs->isEmpty())
        <x-empty-state icon="check-circle" title="Belum ada log" description="Aktivitas penting sistem akan tercatat di sini." />
    @else
        <x-card class="overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border-soft text-sm">
                    <thead class="bg-warm-bg text-left text-xs font-semibold uppercase tracking-wide text-stone-gray">
                        <tr>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Pengguna</th>
                            <th class="px-4 py-3">Aksi</th>
                            <th class="px-4 py-3">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach ($logs as $log)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4 text-stone-gray">{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-4 text-stone-gray">{{ $log->user->name ?? 'Sistem' }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full bg-warm-amber/10 px-3 py-1 text-xs font-semibold text-warm-amber">{{ $log->action }}</span>
                                </td>
                                <td class="px-4 py-4 text-stone-gray">{{ $log->description ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <div class="mt-6">{{ $logs->links() }}</div>
    @endif

@endsection
