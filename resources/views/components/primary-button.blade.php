@props(['type' => 'submit'])

<button {{ $attributes->merge(['type' => $type, 'class' => 'inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-teal-forest hover:bg-teal-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-forest rounded-lg transition-colors duration-200 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
