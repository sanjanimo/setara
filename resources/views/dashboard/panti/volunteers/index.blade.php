@extends('layouts.dashboard')
@section('title', 'Kunjungan Relawan')
@section('page_title', 'Kunjungan Relawan')

@section('content')
    @if($applications->isEmpty())
        <x-empty-state icon="users" title="Belum ada pengajuan relawan" description="Pengajuan dari relawan akan muncul di sini." />
    @else
        <div class="space-y-4">
            @foreach($applications as $app)
                <x-card class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-stone-ink">{{ $app->user->name }}</h3>
                        <p class="text-sm text-stone-gray">{{ ucfirst($app->activity_type) }} • {{ $app->organization ?: 'Individu' }}</p>
                        @if($app->youthProfile)
                            <p class="text-xs text-teal-forest mt-1">Mentor untuk: {{ $app->youthProfile->initials }}</p>
                        @endif
                        <p class="text-xs text-stone-gray mt-2 italic">"{{ Str::limit($app->motivation, 60) }}"</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-status-badge :status="$app->status" />

                        @if($app->status === 'diajukan')
                            <form method="POST" action="{{ route('panti.volunteers.approve', $app) }}">
                                @csrf
                                <button class="rounded-lg bg-teal-forest px-3 py-1.5 text-xs font-bold text-white hover:bg-teal-hover">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('panti.volunteers.reject', $app) }}" onsubmit="return confirm('Tandai belum dapat diterima?')">
                                @csrf
                                <button class="rounded-lg border border-border-soft px-3 py-1.5 text-xs font-medium text-stone-gray hover:border-urgency-red hover:text-urgency-red">Belum Terima</button>
                            </form>
                        @endif
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
@endsection
