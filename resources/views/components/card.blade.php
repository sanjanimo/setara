@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-warm-surface border border-border-soft rounded-2xl shadow-sm p-6 transition-all duration-200 hover:shadow-md ' . $class]) }}>
    {{ $slot }}
</div>
