@props([
    'href',
    'active' => false,
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition ' .
            ($active
                ? 'bg-teal-forest/10 text-teal-forest'
                : 'text-stone-gray hover:bg-warm-bg hover:text-teal-forest')
    ]) }}
    @if($active) aria-current="page" @endif
>
    {{ $slot }}
</a>
