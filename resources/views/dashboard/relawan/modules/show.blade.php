@extends('layouts.dashboard')

@section('title', $module->title)
@section('page_title', $module->title)

@section('content')

@php
    $attempt = auth()->user()->moduleAttempts()->where('module_id', $module->id)->first();
    $passed = $attempt && $attempt->passed;
@endphp

<div class="mb-6 flex items-center justify-between">
    <span class="inline-flex rounded-full bg-teal-forest/10 px-3 py-1 text-xs font-semibold uppercase text-teal-forest">
        {{ ucfirst($module->level ?? 'basic') }} • Target {{ ucfirst($module->target) }}
    </span>

    @if ($passed)
        <span class="inline-flex items-center gap-2 rounded-full bg-urgency-green/10 px-3 py-1 text-xs font-semibold text-urgency-green">
            <x-icon name="check-circle" class="h-4 w-4" /> Lulus • Skor {{ $attempt->score }}
        </span>
    @endif
</div>

{{-- ILUSTRASI MODUL --}}
@if ($module->illustration)
    <img src="{{ asset('images/modules/' . $module->illustration) }}" class="w-full rounded-xl mb-6" alt="{{ $module->title }}">
@endif

{{-- MATERI --}}
<x-card class="mb-8">
    <h2 class="text-xl font-bold text-stone-ink mb-6">Materi Pembelajaran</h2>

    <div class="space-y-8">
        @foreach ($module->lessons as $i => $lesson)
            <div class="relative border-l-4 border-teal-forest pl-6">
                <div class="absolute -left-4 top-0 flex h-8 w-8 items-center justify-center rounded-full bg-teal-forest text-xs font-bold text-white">
                    {{ $i + 1 }}
                </div>
                <h3 class="text-lg font-semibold text-stone-ink">{{ $lesson->title }}</h3>
                <div class="mt-3 space-y-3 text-sm leading-relaxed text-stone-gray">
                    {!! nl2br(e($lesson->content)) !!}
                </div>
            </div>
        @endforeach
    </div>
</x-card>

{{-- ===== KUIS ===== --}}
@php
    $result = session('quizResult');
    $showForm = ! $passed || request()->boolean('retake');
@endphp

@if ($result && $result['passed'])
    {{-- LULUS: layar hasil + countdown --}}
    <x-card x-data="{ count: 6 }"
        x-init="const t = setInterval(() => { count--; if (count <= 0) { clearInterval(t); window.location.href = '{{ route('relawan.modules.index') }}'; } }, 1000)">
        <div class="flex flex-col items-center py-6 text-center">
            <span class="flex h-16 w-16 items-center justify-center rounded-full bg-urgency-green/10 text-urgency-green">
                <x-icon name="check-circle" class="h-9 w-9" />
            </span>
            <h2 class="mt-4 text-2xl font-bold text-stone-ink">Selamat, kamu lulus!</h2>
            <p class="mt-2 text-sm text-stone-gray">
                Skor kamu <strong class="text-urgency-green">{{ $result['score'] }}</strong>/100.
                Status "Relawan Siap" untuk modul ini sudah aktif.
            </p>
            <p class="mt-4 text-xs text-stone-gray">
                Kembali ke daftar modul dalam <span class="font-bold text-teal-forest" x-text="count">6</span> detik…
            </p>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('relawan.modules.index') }}" class="rounded-lg bg-teal-forest px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-hover">Lanjut Sekarang</a>
                <a href="{{ route('relawan.applications.search') }}" class="rounded-lg border border-teal-forest px-5 py-2.5 text-sm font-semibold text-teal-forest hover:bg-teal-forest hover:text-white">Cari Panti</a>
            </div>
        </div>
    </x-card>

@elseif (! $showForm)
    {{-- SUDAH LULUS: tanpa form --}}
    <x-card class="py-8 text-center">
        <span class="inline-flex items-center gap-2 rounded-full bg-urgency-green/10 px-4 py-1.5 text-sm font-semibold text-urgency-green">
            <x-icon name="check-circle" class="h-5 w-5" /> Lulus • Skor {{ $attempt->score }}
        </span>
        <h2 class="mt-4 text-xl font-bold text-stone-ink">Kamu sudah menyelesaikan modul ini.</h2>
        <p class="mt-2 text-sm text-stone-gray">Materi tetap bisa kamu baca ulang kapan saja.</p>
        <div class="mt-6 flex justify-center gap-3">
            <a href="{{ route('relawan.modules.index') }}" class="rounded-lg bg-teal-forest px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-hover">Modul Lainnya</a>
            <a href="{{ route('relawan.modules.show', [$module->slug, 'retake' => 1]) }}" class="rounded-lg border border-border-soft px-5 py-2.5 text-sm font-semibold text-stone-gray hover:border-teal-forest hover:text-teal-forest">Ulangi Kuis</a>
        </div>
    </x-card>

@else
    {{-- FORM KUIS (baru / gagal / retake) --}}
    <x-card x-data="{ sending: false }">
        @if ($result && ! $result['passed'])
            <div class="mb-6 rounded-xl border border-warm-amber/40 bg-warm-amber/10 p-4 text-sm text-warm-amber">
                Skor kamu <strong>{{ $result['score'] }}</strong>/100 — minimal 80.
                Baca kembali materi di atas, lalu coba lagi. Kamu pasti bisa.
            </div>
        @endif

        <h2 class="text-xl font-bold text-stone-ink mb-2">Kuis Kelulusan</h2>
        <p class="text-sm text-stone-gray mb-6">Jawab minimal 80% benar untuk lulus modul ini.</p>

        <form method="POST" action="{{ route('relawan.modules.attempt', $module->slug) }}" class="space-y-6" @submit="sending = true">
            @csrf
            @foreach ($module->quizzes as $i => $quiz)
                <div class="rounded-xl border border-border-soft p-5">
                    <p class="font-semibold text-stone-ink mb-4">
                        <span class="text-warm-amber">{{ $i + 1 }}.</span> {{ $quiz->question }}
                    </p>
                    <div class="space-y-2">
                        @foreach (['a', 'b', 'c', 'd'] as $opt)
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg p-2 transition hover:bg-warm-bg">
                                <input type="radio" name="answers[{{ $quiz->id }}]" value="{{ $opt }}" required class="text-teal-forest">
                                <span class="text-sm text-stone-gray">
                                    <span class="font-medium text-stone-ink">{{ strtoupper($opt) }}.</span>
                                    {{ $quiz->{'option_' . $opt} }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit" :disabled="sending"
                    class="rounded-lg bg-teal-forest px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-hover disabled:cursor-not-allowed disabled:opacity-50">
                    <span x-show="!sending">Kirim Jawaban</span>
                    <span x-show="sending" x-cloak>Menilai…</span>
                </button>
            </div>
        </form>
    </x-card>
@endif

@endsection
