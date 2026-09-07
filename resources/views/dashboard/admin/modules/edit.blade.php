@extends('layouts.dashboard')

@section('title', 'Kelola Modul')
@section('page_title', 'Kelola Modul: ' . $module->title)

@section('content')

<div class="space-y-8" x-data="{ editLesson: null, editQuiz: null }">

    {{-- ===== META MODUL ===== --}}
    <x-card>
        <h2 class="text-base font-semibold text-stone-ink mb-4">Informasi Modul</h2>
        <form method="POST" action="{{ route('admin.modules.update', $module->slug) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-stone-gray">Judul</label>
                    <input name="title" value="{{ $module->title }}" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-stone-gray">Target</label>
                        <select name="target" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                            <option value="umum" @selected($module->target === 'umum')>Umum</option>
                            <option value="anak" @selected($module->target === 'anak')>Anak</option>
                            <option value="lansia" @selected($module->target === 'lansia')>Lansia</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-gray">Level</label>
                        <select name="level" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                            <option value="basic" @selected($module->level === 'basic')>Dasar</option>
                            <option value="advanced" @selected($module->level === 'advanced')>Lanjutan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-gray">Urutan</label>
                        <input name="sort_order" type="number" value="{{ $module->sort_order }}" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-gray">Deskripsi</label>
                <textarea name="description" rows="2" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm">{{ $module->description }}</textarea>
            </div>
            <div class="flex justify-end">
                <x-primary-button>Simpan Modul</x-primary-button>
            </div>
        </form>
    </x-card>

    {{-- ===== MATERI ===== --}}
    <x-card>
        <h2 class="text-base font-semibold text-stone-ink mb-4">Materi ({{ $module->lessons->count() }})</h2>

        <div class="space-y-4">
            @foreach ($module->lessons as $lesson)
                <div class="rounded-xl border border-border-soft p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-stone-ink">{{ $lesson->sort_order }}. {{ $lesson->title }}</p>
                            <p class="mt-1 text-xs text-stone-gray">{{ Str::limit($lesson->content, 140) }}</p>
                        </div>
                        <div class="flex shrink-0 gap-2">
                            <button @click="editLesson = editLesson === {{ $lesson->id }} ? null : {{ $lesson->id }}" class="text-xs font-semibold text-teal-forest">Edit</button>
                            <form method="POST" action="{{ route('admin.modules.lessons.destroy', [$module->slug, $lesson]) }}" onsubmit="return confirm('Hapus materi ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-semibold text-urgency-red">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <form x-show="editLesson === {{ $lesson->id }}" x-cloak method="POST" action="{{ route('admin.modules.lessons.update', [$module->slug, $lesson]) }}" class="mt-4 space-y-3 border-t border-border-soft pt-4">
                        @csrf @method('PUT')
                        <input name="title" value="{{ $lesson->title }}" required placeholder="Judul materi" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                        <textarea name="content" rows="4" required placeholder="Isi materi" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">{{ $lesson->content }}</textarea>
                        <x-primary-button>Simpan Materi</x-primary-button>
                    </form>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('admin.modules.lessons.store', $module->slug) }}" class="mt-6 space-y-3 rounded-xl bg-warm-bg p-4">
            @csrf
            <p class="text-sm font-semibold text-stone-ink">+ Tambah Materi</p>
            <input name="title" required placeholder="Judul materi baru" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
            <textarea name="content" rows="3" required placeholder="Isi materi baru" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm"></textarea>
            <x-primary-button>Tambahkan</x-primary-button>
        </form>
    </x-card>

    {{-- ===== KUIS ===== --}}
    <x-card>
        <h2 class="text-base font-semibold text-stone-ink mb-4">Kuis ({{ $module->quizzes->count() }})</h2>

        <div class="space-y-4">
            @foreach ($module->quizzes as $quiz)
                <div class="rounded-xl border border-border-soft p-4">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-sm font-semibold text-stone-ink">{{ $quiz->question }} <span class="text-urgency-green">(kunci: {{ strtoupper($quiz->correct_option) }})</span></p>
                        <div class="flex shrink-0 gap-2">
                            <button @click="editQuiz = editQuiz === {{ $quiz->id }} ? null : {{ $quiz->id }}" class="text-xs font-semibold text-teal-forest">Edit</button>
                            <form method="POST" action="{{ route('admin.modules.quizzes.destroy', [$module->slug, $quiz]) }}" onsubmit="return confirm('Hapus kuis ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-semibold text-urgency-red">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <form x-show="editQuiz === {{ $quiz->id }}" x-cloak method="POST" action="{{ route('admin.modules.quizzes.update', [$module->slug, $quiz]) }}" class="mt-4 space-y-3 border-t border-border-soft pt-4">
                        @csrf @method('PUT')
                        <textarea name="question" rows="2" required class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">{{ $quiz->question }}</textarea>
                        <div class="grid gap-2 sm:grid-cols-2">
                            <input name="option_a" value="{{ $quiz->option_a }}" required placeholder="Opsi A" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                            <input name="option_b" value="{{ $quiz->option_b }}" required placeholder="Opsi B" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                            <input name="option_c" value="{{ $quiz->option_c }}" required placeholder="Opsi C" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                            <input name="option_d" value="{{ $quiz->option_d }}" required placeholder="Opsi D" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                        </div>
                        <select name="correct_option" class="w-40 rounded-lg border-border-soft bg-warm-surface text-sm">
                            @foreach (['a','b','c','d'] as $o)
                                <option value="{{ $o }}" @selected($quiz->correct_option === $o)>Kunci: {{ strtoupper($o) }}</option>
                            @endforeach
                        </select>
                        <div><x-primary-button>Simpan Kuis</x-primary-button></div>
                    </form>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('admin.modules.quizzes.store', $module->slug) }}" class="mt-6 space-y-3 rounded-xl bg-warm-bg p-4">
            @csrf
            <p class="text-sm font-semibold text-stone-ink">+ Tambah Kuis</p>
            <textarea name="question" rows="2" required placeholder="Pertanyaan" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm"></textarea>
            <div class="grid gap-2 sm:grid-cols-2">
                <input name="option_a" required placeholder="Opsi A" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                <input name="option_b" required placeholder="Opsi B" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                <input name="option_c" required placeholder="Opsi C" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                <input name="option_d" required placeholder="Opsi D" class="w-full rounded-lg border-border-soft bg-warm-surface text-sm">
            </div>
            <select name="correct_option" class="w-40 rounded-lg border-border-soft bg-warm-surface text-sm">
                <option value="a">Kunci: A</option>
                <option value="b">Kunci: B</option>
                <option value="c">Kunci: C</option>
                <option value="d">Kunci: D</option>
            </select>
            <div><x-primary-button>Tambahkan Kuis</x-primary-button></div>
        </form>
    </x-card>

</div>

@endsection
