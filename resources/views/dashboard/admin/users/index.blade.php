@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')
@section('page_description', 'Kelola status aktif akun pengguna.')

@section('content')
<x-admin-filter :action="route('admin.users.index')" search-placeholder="Cari nama atau email...">
    <select name="role" class="rounded-lg border-border-soft bg-warm-surface text-sm">
        <option value="">Semua Role</option>
        @foreach (['admin', 'panti', 'relawan', 'donatur'] as $r)
            <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst($r) }}</option>
        @endforeach
    </select>
</x-admin-filter>

    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border-soft text-sm">
                <thead class="bg-warm-bg text-left text-xs font-semibold uppercase tracking-wide text-stone-gray">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Organisasi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-4 py-4">
                                <p class="font-medium text-stone-ink">{{ $user->name }}</p>
                                <p class="mt-1 text-xs text-stone-gray">{{ $user->email }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="rounded-full bg-teal-forest/10 px-3 py-1 text-xs font-semibold uppercase text-teal-forest">{{ $user->role }}</span>
                            </td>
                            <td class="px-4 py-4 text-stone-gray">{{ $user->organization_name ?? '-' }}</td>
                            <td class="px-4 py-4">
                                @if ($user->is_active)
                                    <span class="rounded-full bg-urgency-green/10 px-3 py-1 text-xs font-semibold uppercase text-urgency-green">Aktif</span>
                                @else
                                    <span class="rounded-full bg-urgency-red/10 px-3 py-1 text-xs font-semibold uppercase text-urgency-red">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}" onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?');">
                                        @csrf
                                        <button class="rounded-lg border border-border-soft px-3 py-1.5 text-xs font-medium text-stone-gray hover:border-teal-forest hover:text-teal-forest">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-stone-gray">Akun kamu</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-6">{{ $users->links() }}</div>

@endsection
