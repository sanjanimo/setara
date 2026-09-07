@props([
    'icon' => 'clipboard',
    'title',
    'description' => null,
])

<x-card {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center px-6 py-12 text-center']) }}>
    <span class="flex h-12 w-12 items-center justify-center rounded-2xl border border-border-soft bg-warm-bg text-stone-gray">
        <x-icon :name="$icon" class="h-6 w-6" />
    </span>

    <h3 class="mt-4 text-base font-semibold text-stone-ink">
        {{ $title }}
    </h3>

    @if ($description)
        <p class="mt-2 max-w-md text-sm leading-relaxed text-stone-gray">
            {{ $description }}
        </p>
    @endif

    @if (trim($slot))
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</x-card>
