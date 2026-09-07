@php $passed = $attempts[$module->id] ?? false; @endphp
<x-card class="flex flex-col justify-between h-full">
    <div>
        <div class="flex items-center justify-between mb-3">
            <span class="rounded-full {{ $module->level === 'advanced' ? 'bg-teal-forest/10 text-teal-forest' : 'bg-warm-amber/10 text-warm-amber' }} px-3 py-1 text-xs font-semibold uppercase">
                {{ $module->level === 'advanced' ? 'Lanjutan' : 'Dasar' }} • {{ ucfirst($module->target) }}
            </span>
            @if ($passed)
                <x-icon name="check-circle" class="h-6 w-6 text-urgency-green" />
            @endif
        </div>
        <h3 class="text-lg font-bold text-stone-ink">{{ $module->title }}</h3>
        <p class="mt-2 text-sm text-stone-gray">{{ $module->description }}</p>
        <p class="mt-3 text-xs text-stone-gray">{{ $module->lessons_count ?? $module->lessons->count() }} materi • {{ $module->quizzes_count ?? $module->quizzes->count() }} soal</p>
    </div>
    <div class="mt-6">
        <a href="{{ route('relawan.modules.show', $module->slug) }}" class="inline-flex w-full items-center justify-center rounded-lg {{ $passed ? 'border border-border-soft text-stone-gray hover:border-teal-forest hover:text-teal-forest' : 'bg-teal-forest text-white hover:bg-teal-hover' }} px-4 py-2 text-sm font-medium transition">
            {{ $passed ? 'Tinjau Kembali' : 'Mulai Belajar & Kuis' }}
        </a>
    </div>
</x-card>
