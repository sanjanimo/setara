@extends('layouts.dashboard')

@section('title', 'Verifikasi Panti')
@section('page_title', 'Tinjauan Verifikasi')
@section('page_description', $panti->name)

@section('content')

<div class="space-y-6">

    <x-card class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-stone-ink">{{ $panti->name }}</h2>
            <p class="mt-1 text-sm text-stone-gray">
                {{ ucfirst($panti->type) }} • {{ $panti->district ? $panti->district . ', ' : '' }}{{ $panti->city }}, {{ $panti->province }}
            </p>
            <p class="mt-1 text-xs text-stone-gray">Akun pendaftar: {{ $panti->user->email ?? '-' }} • Diperbarui {{ $panti->updated_at->diffForHumans() }}</p>
        </div>
        <x-status-badge :status="$panti->verification_status" />
    </x-card>

    <div class="grid gap-6 lg:grid-cols-2">

        <x-card>
            <h3 class="text-base font-semibold text-stone-ink mb-4">Kapasitas & Penghuni</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-xs text-stone-gray">Kapasitas</p><p class="font-bold text-stone-ink">{{ $panti->capacity }}</p></div>
                <div><p class="text-xs text-stone-gray">Total Penghuni</p><p class="font-bold text-stone-ink">{{ $panti->total_residents }}</p></div>
                <div><p class="text-xs text-stone-gray">Anak</p><p class="font-bold text-stone-ink">{{ $panti->children_count }}</p></div>
                <div><p class="text-xs text-stone-gray">Lansia</p><p class="font-bold text-stone-ink">{{ $panti->elderly_count }}</p></div>
                <div><p class="text-xs text-stone-gray">Staff</p><p class="font-bold text-stone-ink">{{ $panti->staff_count }}</p></div>
                <div><p class="text-xs text-stone-gray">Rasio Okupansi</p><p class="font-bold text-stone-ink">{{ $panti->capacity > 0 ? round($panti->total_residents / $panti->capacity * 100) : 0 }}%</p></div>
            </div>
        </x-card>

        <x-card>
            <h3 class="text-base font-semibold text-stone-ink mb-4">Lokasi & Kontak</h3>
            <div class="space-y-3 text-sm">
                <div><p class="text-xs text-stone-gray">Alamat</p><p class="text-stone-ink">{{ $panti->address ?: '-' }}</p></div>
                <div><p class="text-xs text-stone-gray">Koordinat</p><p class="font-mono text-stone-ink">{{ $panti->latitude ?? '-' }}, {{ $panti->longitude ?? '-' }}</p></div>
                <div><p class="text-xs text-stone-gray">Tampilan Publik</p><p class="text-stone-ink">{{ $panti->location_precision === 'exact' ? 'Alamat detail' : 'Area umum saja' }}</p></div>
                <div><p class="text-xs text-stone-gray">Pengelola</p><p class="text-stone-ink">{{ $panti->manager_name ?? '-' }} • {{ $panti->manager_phone ?? '-' }}</p></div>
            </div>
        </x-card>

    </div>

    <x-card>
        <h3 class="text-base font-semibold text-stone-ink mb-2">Deskripsi Panti</h3>
        <p class="text-sm leading-relaxed text-stone-gray">{{ $panti->description ?: 'Tidak ada deskripsi.' }}</p>

        <h3 class="text-base font-semibold text-stone-ink mt-6 mb-3">Kebutuhan yang Diajukan ({{ $panti->needs->count() }})</h3>
        @forelse ($panti->needs as $need)
            <div class="mb-3 rounded-xl border border-border-soft bg-warm-bg p-4">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold text-stone-ink">{{ $need->title }} <span class="text-xs font-normal text-stone-gray">({{ $need->category->name ?? 'Umum' }})</span></p>
                    <span class="rounded-full bg-warm-amber/10 px-3 py-1 text-[10px] font-bold uppercase text-warm-amber">{{ ucfirst($need->priority) }}</span>
                </div>
                <p class="mt-1 text-xs text-stone-gray">Stok {{ $need->current_stock }} {{ $need->unit }} • ± {{ $need->stock_days_remaining }} hari • butuh {{ $need->quantity_needed }} {{ $need->unit }}</p>
            </div>
        @empty
            <p class="text-sm text-stone-gray">Belum ada kebutuhan diajukan.</p>
        @endforelse

        <div class="mt-4 flex items-start gap-3 rounded-xl border border-teal-forest/30 bg-teal-forest/5 p-4">
            <x-icon name="shield" class="h-5 w-5 shrink-0 text-teal-forest" />
            <p class="text-xs leading-relaxed text-stone-gray">
                Persetujuan (consent) data: <strong class="text-stone-ink">{{ $panti->consent_agreement ? 'DISETUJUI pengurus' : 'BELUM disetujui' }}</strong>.
                Dengan memverifikasi, panti ini akan tampil di peta & halaman publik.
            </p>
        </div>
    </x-card>

    @if ($panti->verification_status !== 'verified')
        <x-card class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('admin.verification.approve', $panti) }}">
                @csrf
                <button class="rounded-lg bg-teal-forest px-6 py-2.5 text-sm font-bold text-white hover:bg-teal-hover">✓ Setujui & Verifikasi</button>
            </form>

            <form method="POST" action="{{ route('admin.verification.reject', $panti) }}" class="flex flex-1 items-center gap-2 sm:justify-end">
                @csrf
                <input type="text" name="note" placeholder="Catatan penolakan (opsional)" class="flex-1 rounded-lg border-border-soft bg-warm-surface text-sm sm:max-w-xs">
                <button class="rounded-lg border border-urgency-red px-4 py-2.5 text-sm font-semibold text-urgency-red hover:bg-urgency-red hover:text-white">Tolak</button>
            </form>
        </x-card>
    @else
        <p class="text-sm text-stone-gray">Panti ini sudah terverifikasi.</p>
    @endif

</div>

@endsection
