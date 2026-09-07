@extends('layouts.app')

@section('title', $panti->name . ' — SETARA')

@section('content')

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

        <div class="grid gap-10 lg:grid-cols-[1fr_360px]">

            <div>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-stone-ink">
                            {{ $panti->name }}
                        </h1>

                        <p class="mt-2 text-sm text-stone-gray">
                            {{ ucfirst($panti->type) }} • {{ $panti->city }}
                            @if ($panti->district)
                                • {{ $panti->district }}
                            @endif
                        </p>
                    </div>

                    <x-urgency-badge :status="$panti->urgency_status" />
                </div>

                <x-card class="mt-8">
                    <h2 class="text-lg font-semibold text-stone-ink">Tentang Panti</h2>

                    <p class="mt-3 text-sm leading-relaxed text-stone-gray">
                        {{ $panti->description ?: 'Deskripsi panti belum diisi.' }}
                    </p>

                    @if ($panti->location_precision === 'exact' && $panti->address)
                        <p class="mt-4 text-sm text-stone-gray">
                            Alamat: {{ $panti->address }}
                        </p>
                    @endif
                </x-card>

                <div class="mt-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-stone-ink">Kebutuhan Aktif</h2>
                    </div>

                    @if ($needs->isEmpty())
                        <x-empty-state icon="check-circle" title="Tidak ada kebutuhan aktif"
                            description="Saat ini panti tidak menampilkan kebutuhan aktif." />
                    @else
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            @foreach ($needs as $need)
                                @php
                                    $priorityClasses = match ($need->priority) {
                                        'kritis' => 'bg-urgency-red/10 text-urgency-red',
                                        'tinggi' => 'bg-warm-amber/10 text-warm-amber',
                                        'sedang' => 'bg-teal-forest/10 text-teal-forest',
                                        default => 'bg-warm-bg text-stone-gray',
                                    };
                                @endphp

                                <x-card class="h-full">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-xs font-medium uppercase tracking-wide text-stone-gray">
                                                {{ $need->category->name ?? 'Kebutuhan' }}
                                            </p>
                                            <h3 class="mt-1 text-base font-semibold text-stone-ink">
                                                {{ $need->title }}
                                            </h3>
                                        </div>

                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-semibold uppercase {{ $priorityClasses }}">
                                            {{ ucfirst($need->priority) }}
                                        </span>
                                    </div>

                                    @if ($need->description)
                                        <p class="mt-3 text-sm leading-relaxed text-stone-gray">
                                            {{ $need->description }}
                                        </p>
                                    @endif

                                    <div class="mt-4 grid gap-2 text-sm text-stone-gray">
                                        <p>
                                            Dibutuhkan: <span
                                                class="font-medium text-stone-ink">{{ $need->quantity_needed }}
                                                {{ $need->unit }}</span>
                                        </p>
                                        <p>
                                            Stok saat ini: <span
                                                class="font-medium text-stone-ink">{{ $need->current_stock }}
                                                {{ $need->unit }}</span>
                                        </p>
                                        <p>
                                            Perkiraan stok cukup: <span
                                                class="font-medium text-stone-ink">{{ $need->stock_days_remaining }}
                                                hari</span>
                                        </p>
                                    </div>
                                    @if (auth()->guest() || auth()->user()->isDonatur())
                                        <div class="mt-5">
                                            <a href="{{ route('donatur.donations.create', ['panti' => $panti->slug, 'need_id' => $need->id]) }}"
                                                class="inline-flex w-full items-center justify-center rounded-lg border border-teal-forest px-4 py-2 text-sm font-medium text-teal-forest transition hover:bg-teal-forest hover:text-white">
                                                Bantu Kebutuhan Ini
                                            </a>
                                        </div>
                                    @endif
                                </x-card>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <x-card>
                    <h2 class="text-base font-semibold text-stone-ink">Ringkasan</h2>

                    <div class="mt-4 space-y-3 text-sm text-stone-gray">
                        <div class="flex justify-between">
                            <span>Tipe panti</span>
                            <span class="font-medium text-stone-ink">{{ ucfirst($panti->type) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Kapasitas</span>
                            <span class="font-medium text-stone-ink">{{ $panti->capacity }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Total penghuni</span>
                            <span class="font-medium text-stone-ink">{{ $panti->total_residents }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Anak</span>
                            <span class="font-medium text-stone-ink">{{ $panti->children_count }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Lansia</span>
                            <span class="font-medium text-stone-ink">{{ $panti->elderly_count }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Status urgensi</span>
                            <x-urgency-badge :status="$panti->urgency_status" />
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <h2 class="text-base font-semibold text-stone-ink">Aksi</h2>

                    <p class="mt-3 text-sm leading-relaxed text-stone-gray">
                        Salurkan bantuan atau ajukan kegiatan relawan sesuai kebutuhan panti.
                    </p>

                    <div class="mt-5 space-y-3">
                        @if (auth()->guest() || auth()->user()->isDonatur())
                            <a href="{{ route('donatur.donations.create', $panti->slug) }}"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">
                                Bantu Kebutuhan
                            </a>
                        @else
                            <span
                                class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-lg bg-teal-forest/50 px-4 py-2 text-sm font-medium text-white">
                                Bantu Kebutuhan — khusus donatur
                            </span>
                        @endif

                        <a href="{{ route('register') }}"
                            class="inline-flex w-full items-center justify-center rounded-lg border border-teal-forest px-4 py-2 text-sm font-medium text-teal-forest transition hover:bg-teal-forest hover:text-white">
                            Daftar sebagai Relawan
                        </a>
                    </div>
                </x-card>
            </div>

        </div>

    </section>

@endsection
