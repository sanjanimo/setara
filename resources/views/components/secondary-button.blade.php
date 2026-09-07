@props(['type' => 'button'])

<button {{ $attributes->merge(['type' => $type, 'class' => 'inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-teal-forest bg-white border border-teal-forest hover:bg-teal-forest hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-forest rounded-lg transition-colors duration-200']) }}>
    {{ $slot }}
</button>
