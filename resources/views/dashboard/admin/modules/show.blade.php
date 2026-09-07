@extends('layouts.dashboard')

@section('title', $module->title)
@section('page_title', $module->title)
@section('page_description', 'Tinjauan read-only materi dan kuis modul.')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.modules.index') }}" class="text-sm text-teal-forest hover:text-teal-hover">← Kembali ke daftar modul</a>

        <form method="POST" action="{{ route('admin.modules.toggle', $module->slug) }}">
            @csrf
            <button class="rounded-lg bg-teal-forest px-3 py-1.5 text-xs font-bold text-white hover:bg-teal-hover">
                {{ $module->is_published ? 'Sembunyikan Modul' : 'Terbitkan Modul' }}
            </button>
        </form>
    </div>

    <x-card class="mb-8">
        <h2 class="text-lg font-bold text-stone-ink mb-4">Materi ({{ $module->lessons->count() }})</h2>
        <div class="space-y-5">
            @foreach ($module->lessons as $lesson)
                <div class="border-l-4 border-teal-forest pl-4">
                    <h3 class="font-semibold text-stone-ink">{{ $lesson->title }}</h3>
                    <p class="mt-1 text-sm text-stone-gray">{{ $lesson->content }}</p>
                </div>
            @endforeach
        </div>
    </x-card>

    <x-card>
        <h2 class="text-lg font-bold text-stone-ink mb-4">Kuis ({{ $module->quizzes->count() }})</h2>
        <div class="space-y-4">
            @foreach ($module->quizzes as $i => $quiz)
                <div class="rounded-xl border border-border-soft p-4">
                    <p class="font-semibold text-stone-ink">{{ $i + 1 }}. {{ $quiz->question }}</p>
                    <ul class="mt-2 space-y-1 text-sm text-stone-gray">
                        @foreach (['a', 'b', 'c', 'd'] as $opt)
                            <li class="{{ $opt === $quiz->correct_option ? 'font-semibold text-urgency-green' : '' }}">
                                {{ strtoupper($opt) }}. {{ $quiz->{'option_' . $opt} }}
                                @if ($opt === $quiz->correct_option) (benar) @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </x-card>

@endsection
