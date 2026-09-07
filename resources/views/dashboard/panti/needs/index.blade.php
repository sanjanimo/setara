@extends('layouts.dashboard')

@section('title', 'Kebutuhan Panti')
@section('page_title', 'Kebutuhan Panti')
@section('page_description', 'Kelola kebutuhan yang ditampilkan kepada donatur dan relawan.')

@section('content')

    @if (! $panti)
        <x-empty-state
            icon="building"
            title="Lengkapi profil panti terlebih dahulu"
            description="Kamu harus melengkapi profil panti sebelum mengelola kebutuhan."
        >
            <a href="{{ route('panti.profile.edit') }}" class="inline-flex items-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">
                Lengkapi Profil Panti
            </a>
        </x-empty-state>
    @else

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-stone-ink">Daftar Kebutuhan</h2>
                <p class="text-sm text-stone-gray">
                    Kebutuhan aktif akan memengaruhi status urgensi panti.
                </p>
            </div>

            <a href="{{ route('panti.needs.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">
                Tambah Kebutuhan
            </a>
        </div>

        @if ($needs->isEmpty())
            <x-empty-state
                icon="package"
                title="Belum ada kebutuhan"
                description="Tambahkan kebutuhan panti agar donatur dan relawan dapat melihat prioritas bantuan."
            >
                <a href="{{ route('panti.needs.create') }}" class="inline-flex items-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">
                    Tambah Kebutuhan
                </a>
            </x-empty-state>
        @else

            <x-card class="overflow-hidden p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border-soft text-sm">
                        <thead class="bg-warm-bg text-left text-xs font-semibold uppercase tracking-wide text-stone-gray">
                            <tr>
                                <th class="px-4 py-3">Kebutuhan</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Stok</th>
                                <th class="px-4 py-3">Prioritas</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-border-soft">
                            @foreach ($needs as $need)
                                @php
                                    $priorityClasses = match ($need->priority) {
                                        'kritis' => 'bg-urgency-red/10 text-urgency-red',
                                        'tinggi' => 'bg-warm-amber/10 text-warm-amber',
                                        'sedang' => 'bg-teal-forest/10 text-teal-forest',
                                        default => 'bg-warm-bg text-stone-gray',
                                    };

                                    $statusClasses = match ($need->status) {
                                        'aktif' => 'bg-teal-forest/10 text-teal-forest',
                                        'terpenuhi' => 'bg-urgency-green/10 text-urgency-green',
                                        default => 'bg-warm-bg text-stone-gray',
                                    };
                                @endphp

                                <tr>
                                    <td class="px-4 py-4">
                                        <p class="font-medium text-stone-ink">{{ $need->title }}</p>
                                        <p class="mt-1 text-xs text-stone-gray">
                                            {{ $need->unit }} • dibutuhkan {{ $need->quantity_needed }} • stok {{ $need->current_stock }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-4 text-stone-gray">
                                        {{ $need->category->name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-stone-gray">
                                        {{ $need->stock_days_remaining }} hari
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase {{ $priorityClasses }}">
                                            {{ ucfirst($need->priority) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase {{ $statusClasses }}">
                                            {{ ucfirst($need->status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('panti.needs.edit', $need) }}" class="text-teal-forest hover:text-teal-hover">
                                                Edit
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('panti.needs.destroy', $need) }}"
                                                onsubmit="return confirm('Hapus kebutuhan ini?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-urgency-red hover:opacity-80">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>

        @endif

    @endif

@endsection
