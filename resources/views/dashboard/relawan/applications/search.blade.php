@extends('layouts.dashboard')
@section('title', 'Cari Panti')
@section('page_title', 'Cari Panti')
@section('page_description', 'Pilih panti yang ingin kamu bantu atau dampingi.')

@section('content')
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($pantis as $panti)
            <x-card class="flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-stone-ink">{{ $panti->name }}</h3>
                        <x-urgency-badge :status="$panti->urgency_status" />
                    </div>
                    <p class="text-sm text-stone-gray">{{ ucfirst($panti->type) }} • {{ $panti->city }}</p>
                </div>
                <a href="{{ route('relawan.applications.create', $panti->slug) }}" class="mt-4 inline-flex items-center justify-center rounded-lg border border-teal-forest px-4 py-2 text-sm font-medium text-teal-forest hover:bg-teal-forest hover:text-white transition">
                    Ajukan Kegiatan
                </a>
            </x-card>
        @endforeach
    </div>
@endsection
