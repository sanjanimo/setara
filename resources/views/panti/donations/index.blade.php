@extends('layouts.dashboard')

@section('title', 'Donasi Masuk')
@section('page_title', 'Donasi Masuk')
@section('page_description', 'Kelola niat bantuan dari donatur.')

@section('content')

    @if ($donations->isEmpty())
        <x-empty-state
            icon="heart"
            title="Belum ada niat bantuan"
            description="Ketika donatur mengirim niat bantuan, daftarnya akan muncul di sini."
        />
    @else

        <x-card class="overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border-soft text-sm">
                    <thead class="bg-warm-bg text-left text-xs font-semibold uppercase tracking-wide text-stone-gray">
                        <tr>
                            <th class="px-4 py-3">Donatur</th>
                            <th class="px-4 py-3">Kebutuhan</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Detail</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-border-soft">
                        @foreach ($donations as $donation)
                            <tr>
                                <td class="px-4 py-4">
                                    <p class="font-medium text-stone-ink">{{ $donation->donor_name }}</p>
                                    <p class="mt-1 text-xs text-stone-gray">{{ $donation->donor_email }}</p>
                                    @if ($donation->donor_phone)
                                        <p class="text-xs text-stone-gray">{{ $donation->donor_phone }}</p>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-stone-gray">
                                    {{ $donation->need->title ?? 'Bantuan umum' }}
                                </td>

                                <td class="px-4 py-4 text-stone-gray">
                                    {{ ucfirst($donation->type) }}
                                </td>

                                <td class="px-4 py-4 text-stone-gray">
                                    @if ($donation->type === 'barang')
                                        {{ $donation->quantity ?? '-' }} {{ $donation->need->unit ?? '' }}
                                    @else
                                        {{ Str::limit($donation->message, 60) ?: 'Bantuan tenaga' }}
                                    @endif

                                    @if ($donation->message && $donation->type === 'barang')
                                        <p class="mt-1 text-xs text-stone-gray">{{ Str::limit($donation->message, 60) }}</p>
                                    @endif
                                </td>

                                <td class="px-4 py-4">
                                    <x-status-badge :status="$donation->status" />
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center gap-2">

                                        @if ($donation->status === 'diajukan')
                                            <form method="POST" action="{{ route('panti.donations.confirm', $donation) }}">
                                                @csrf
                                                <button type="submit" class="rounded-lg bg-teal-forest px-3 py-1.5 text-xs font-medium text-white transition hover:bg-teal-hover">
                                                    Konfirmasi
                                                </button>
                                            </form>

                                            <form
                                                method="POST"
                                                action="{{ route('panti.donations.reject', $donation) }}"
                                                onsubmit="return confirm('Tandai bantuan ini sebagai belum dapat diterima?');"
                                            >
                                                @csrf
                                                <button type="submit" class="rounded-lg border border-border-soft px-3 py-1.5 text-xs font-medium text-stone-gray transition hover:border-urgency-red hover:text-urgency-red">
                                                    Belum Dapat Diterima
                                                </button>
                                            </form>
                                        @elseif ($donation->status === 'dikonfirmasi')
                                            <form method="POST" action="{{ route('panti.donations.complete', $donation) }}">
                                                @csrf
                                                <button type="submit" class="rounded-lg bg-urgency-green px-3 py-1.5 text-xs font-medium text-white transition hover:opacity-90">
                                                    Tandai Selesai
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-stone-gray">
                                                Tidak ada aksi
                                            </span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

    @endif

@endsection
