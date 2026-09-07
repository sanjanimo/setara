@props([
    'code' => 'VISUAL-SETARA',
    'brief' => 'Visual pendukung belum tersedia.',
    'ratio' => '16/9',
])

@php
    $ratioClass = match($ratio) {
        '21/9' => 'aspect-[21/9]',
        '16/9' => 'aspect-[16/9]',
        '16/10' => 'aspect-[16/10]',
        '4/3' => 'aspect-[4/3]',
        '1/1' => 'aspect-square',
        default => 'aspect-[16/9]',
    };
@endphp
<div
    {{ $attributes->merge(['class' => "{$ratioClass} relative overflow-hidden rounded-2xl border border-dashed border-border-soft bg-warm-bg"]) }}
    role="img"
    aria-label="{{ $brief }}"
>
    <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 p-6 text-center">
        <span class="flex h-11 w-11 items-center justify-center rounded-full border border-border-soft bg-warm-surface text-stone-gray shadow-sm">
            <x-icon name="image" class="h-5 w-5" />
        </span>

        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-teal-forest">
            {{ $code }}
        </p>

        <p class="max-w-md text-sm leading-relaxed text-stone-gray">
            {{ $brief }}
        </p>
    </div>
</div>
