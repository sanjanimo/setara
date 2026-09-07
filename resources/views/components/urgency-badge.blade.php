@props(['status' => 'aman'])

@php
    $colors = match($status) {
        'kritis' => 'bg-urgency-red text-white',
        'waspada' => 'bg-urgency-yellow text-stone-ink',
        'aman' => 'bg-urgency-green text-white',
        default => 'bg-stone-gray text-white',
    };
    $isKritis = $status === 'kritis';
    $label = ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => "relative inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold tracking-wide uppercase {$colors}"]) }}>
    @if($isKritis)
        <span class="absolute inset-0 rounded-full bg-urgency-red pulse-wave"></span>
        <span class="relative w-2 h-2 bg-white rounded-full"></span>
    @else
        <span class="w-2 h-2 rounded-full {{ $status === 'waspada' ? 'bg-stone-ink' : 'bg-white' }}"></span>
    @endif
    <span class="relative">{{ $label }}</span>
</span>
