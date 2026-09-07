@extends('layouts.dashboard')

@section('title', 'Modul Pembekalan')
@section('page_title', 'Modul Pembekalan')
@section('page_description', 'Tinjau dan atur ketersediaan modul pembekalan relawan.')

@section('content')

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($modules as $module)
            <x-card class="flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="text-lg font-bold text-stone-ink">{{ $module->title }}</h3>
                        @if ($module->is_published)
                            <span class="rounded-full bg-urgency-green/10 px-3 py-1 text-xs font-semibold uppercase text-urgency-green">Publish</span>
                        @else
                            <span class="rounded-full bg-urgency-red/10 px-3 py-1 text-xs font-semibold uppercase text-urgency-red">Disembunyikan</span>
                        @endif
                    </div>
                    <p class="mt-2 text-sm text-stone-gray">{{ $module->description }}</p>
                    <p class="mt-3 text-xs text-stone-gray">
                        {{ $module->lessons_count }} materi • {{ $module->quizzes_count }} soal • Target: {{ ucfirst($module->target) }}
                    </p>
                </div>

                <div class="mt-5 flex items-center gap-2">
                    <a href="{{ route('admin.modules.show', $module->slug) }}" class="rounded-lg border border-border-soft px-3 py-1.5 text-xs font-medium text-stone-gray hover:border-teal-forest hover:text-teal-forest">
                        Lihat Detail
                    </a>

                    <a href="{{ route('admin.modules.edit', $module->slug) }}" class="rounded-lg border border-border-soft px-3 py-1.5 text-xs font-medium text-stone-gray hover:border-teal-forest hover:text-teal-forest">
                        Kelola Isi
                    </a>

                    <form method="POST" action="{{ route('admin.modules.toggle', $module->slug) }}">
                        @csrf
                        <button class="rounded-lg bg-teal-forest px-3 py-1.5 text-xs font-bold text-white hover:bg-teal-hover">
                            {{ $module->is_published ? 'Sembunyikan' : 'Terbitkan' }}
                        </button>
                    </form>
                </div>
            </x-card>
        @endforeach
    </div>

@endsection
