@extends('layouts.dashboard')

@section('title', $user->name)
@section('page_title', $user->name)

@section('content')
<x-card>
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <p class="text-xs uppercase tracking-wider text-stone-gray">Email</p>
            <p class="mt-1 font-medium">{{ $user->email }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-stone-gray">Role</p>
            <p class="mt-1 font-medium capitalize">{{ $user->role }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-stone-gray">Telepon</p>
            <p class="mt-1">{{ $user->phone ?: '-' }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-stone-gray">Organisasi</p>
            <p class="mt-1">{{ $user->organization_name ?: '-' }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-stone-gray">Status</p>
            <p class="mt-1">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-stone-gray">Bergabung</p>
            <p class="mt-1">{{ $user->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>
</x-card>

@if ($user->role === 'relawan')
    <x-card class="mt-6">
        <h3 class="font-semibold text-stone-ink">Modul yang Dilulusi</h3>
        <div class="mt-3 space-y-2">
            @forelse ($user->moduleAttempts->where('passed', true) as $att)
                <div class="flex items-center gap-2 text-sm">
                    <x-icon name="check-circle" class="h-4 w-4 text-urgency-green" />
                    <span>{{ $att->module->title }} — skor {{ $att->score }}</span>
                </div>
            @empty
                <p class="text-sm text-stone-gray">Belum lulus modul apa pun.</p>
            @endforelse
        </div>
    </x-card>
@endif
@endsection
