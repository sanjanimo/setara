@props(['action', 'searchPlaceholder' => 'Cari...'])

<form method="GET" action="{{ $action }}" class="mb-4 flex flex-col gap-3 sm:flex-row">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="{{ $searchPlaceholder }}"
        class="flex-1 rounded-lg border-border-soft bg-warm-surface text-sm"
    >
    <button type="submit" class="rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white hover:bg-teal-hover">
        Filter
    </button>
    @if (request('search') || request()->except('page'))
        <a href="{{ $action }}" class="rounded-lg border border-border-soft px-4 py-2 text-sm font-medium text-stone-gray hover:border-teal-forest hover:text-teal-forest">
            Reset
        </a>
    @endif
    {{ $slot }}
</form>
