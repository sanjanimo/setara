@extends('layouts.dashboard')

@section('title', 'Youth / Usia Produktif')
@section('page_title', 'Youth / Usia Produktif')
@section('page_description', 'Kelola profil remaja panti secara anonim dan bermartabat.')

@section('content')

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-stone-ink">Profil Youth</h2>
            <p class="text-sm text-stone-gray">Data ditampilkan anonim kepada relawan terverifikasi.</p>
        </div>
        <a href="{{ route('panti.youth.create') }}" class="inline-flex items-center justify-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">
            Tambah Youth
        </a>
    </div>

    @if ($youths->isEmpty())
        <x-empty-state icon="book-open" title="Belum ada profil youth" description="Tambahkan profil remaja panti agar relawan dapat menjadi mentor yang tepat.">
            <a href="{{ route('panti.youth.create') }}" class="inline-flex items-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">
                Tambah Youth
            </a>
        </x-empty-state>
    @else
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($youths as $youth)
                <x-card class="flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-lg font-bold text-stone-ink">{{ $youth->initials }} ({{ $youth->age }} thn)</h3>
                            <span class="rounded-full bg-teal-forest/10 px-3 py-1 text-xs font-semibold uppercase text-teal-forest">{{ ucfirst($youth->status) }}</span>
                        </div>
                        <p class="mt-3 text-sm text-stone-gray"><span class="font-medium text-stone-ink">Minat:</span> {{ $youth->interests }}</p>
                        @if ($youth->skill_goals)
                            <p class="mt-1 text-sm text-stone-gray"><span class="font-medium text-stone-ink">Tujuan:</span> {{ $youth->skill_goals }}</p>
                        @endif
                        @if ($youth->training_needs)
                            <p class="mt-1 text-sm text-stone-gray"><span class="font-medium text-stone-ink">Butuh:</span> {{ $youth->training_needs }}</p>
                        @endif
                    </div>
                    <div class="mt-5 flex items-center justify-between">
                        <span class="text-xs {{ $youth->mentor_needed ? 'font-semibold text-warm-amber' : 'text-stone-gray' }}">
                            {{ $youth->mentor_needed ? 'Membutuhkan mentor' : 'Tidak membutuhkan mentor' }}
                        </span>
                        <a href="{{ route('panti.youth.edit', $youth) }}" class="text-sm font-medium text-teal-forest hover:text-teal-hover">Edit</a>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif

@endsection
