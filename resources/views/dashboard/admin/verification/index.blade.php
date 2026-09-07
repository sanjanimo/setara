@extends('layouts.dashboard')

@section('title', 'Verifikasi Panti')
@section('page_title', 'Verifikasi Panti')
@section('page_description', 'Tinjau dan verifikasi panti yang mendaftar.')

@section('content')
<x-admin-filter :action="route('admin.verification.index')" search-placeholder="Cari nama panti, kota, provinsi...">
        <select name="status" class="rounded-lg border-border-soft bg-warm-surface text-sm">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="verified" @selected(request('status') === 'verified')>Verified</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
        </select>
    </x-admin-filter>

    @if ($pantis->isEmpty())
        <x-empty-state icon="shield" title="Tidak ada panti" description="Belum ada panti yang terdaftar." />
    @else
        <x-card class="overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border-soft text-sm">
                    <thead class="bg-warm-bg text-left text-xs font-semibold uppercase tracking-wide text-stone-gray">
                        <tr>
                            <th class="px-4 py-3">Panti</th>
                            <th class="px-4 py-3">Pengelola</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Diperbarui</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @foreach ($pantis as $panti)
                            <tr>
                                <td class="px-4 py-4">
                                    <p class="font-medium text-stone-ink">{{ $panti->name }}</p>
                                    <p class="mt-1 text-xs text-stone-gray">{{ ucfirst($panti->type) }} • {{ $panti->city }}</p>
                                </td>
                                <td class="px-4 py-4 text-stone-gray">{{ $panti->user->name ?? '-' }}</td>
                                <td class="px-4 py-4"><x-status-badge :status="$panti->verification_status" /></td>
                                <td class="px-4 py-4 text-stone-gray">{{ $panti->updated_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('admin.verification.show', $panti) }}" class="rounded-lg bg-teal-forest px-3 py-1.5 text-xs font-bold text-white hover:bg-teal-hover">
                                        Tinjau Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <div class="mt-6">{{ $pantis->links() }}</div>
    @endif

@endsection
