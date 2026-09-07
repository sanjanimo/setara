@extends('layouts.app')

@section('title', 'Peta Kebutuhan — SETARA')

@section('content')

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

        <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-warm-amber">
                Peta Kebutuhan
            </p>

            <h1 class="mt-3 text-3xl font-bold text-stone-ink">
                Lihat panti yang paling membutuhkan.
            </h1>

            <p class="mt-4 text-base leading-relaxed text-stone-gray">
                Peta ini menampilkan panti terverifikasi berdasarkan status urgensi. Klik marker untuk melihat detail panti dan kebutuhan prioritasnya.
            </p>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:max-w-xl">

            <div>
                <label for="filter-status" class="block text-sm font-medium text-stone-gray">
                    Status Urgensi
                </label>

                <select
                    id="filter-status"
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface px-3 py-2 text-sm focus:border-teal-forest focus:ring-teal-forest"
                >
                    <option value="all">Semua Status</option>
                    <option value="aman">Aman</option>
                    <option value="waspada">Waspada</option>
                    <option value="kritis">Kritis</option>
                </select>
            </div>

            <div>
                <label for="filter-type" class="block text-sm font-medium text-stone-gray">
                    Tipe Panti
                </label>

                <select
                    id="filter-type"
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface px-3 py-2 text-sm focus:border-teal-forest focus:ring-teal-forest"
                >
                    <option value="all">Semua Tipe</option>
                    <option value="anak">Panti Anak</option>
                    <option value="jompo">Panti Jompo</option>
                    <option value="campuran">Panti Campuran</option>
                </select>
            </div>

        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1.5fr_1fr]">

            <div>
                <div
                    id="setara-map"
                    data-url="{{ route('map.data') }}"
                    class="h-[65vh] min-h-105 w-full overflow-hidden rounded-2xl border border-border-soft bg-warm-surface shadow-sm"
                ></div>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <x-urgency-badge status="aman" />
                    <x-urgency-badge status="waspada" />
                    <x-urgency-badge status="kritis" />
                </div>
            </div>

            <div>
                <p id="map-result-count" class="text-sm text-stone-gray">
                    Memuat data panti...
                </p>

                <div id="panti-map-list" class="mt-4 space-y-4">
                </div>
            </div>

        </div>

    </section>

@endsection
