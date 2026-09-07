@props(['status'])

@php
    $labels = [
        'diajukan' => 'Menunggu Konfirmasi',
        'dikonfirmasi' => 'Siap Dikoordinasikan',
        'ditolak' => 'Belum Dapat Diterima',
        'selesai' => 'Selesai',
        'disetujui' => 'Disetujui',
        'verified' => 'Terverifikasi',
        'rejected' => 'Ditolak',
        'pending' => 'Menunggu Verifikasi',
    ];

    $label = $labels[$status] ?? ucfirst($status);

    $classes = match ($status) {
        'diajukan' => 'bg-warm-amber/10 text-warm-amber',
        'dikonfirmasi', 'disetujui', 'verified' => 'bg-teal-forest/10 text-teal-forest',
        'ditolak', 'rejected' => 'bg-urgency-red/10 text-urgency-red',
        'selesai' => 'bg-urgency-green/10 text-urgency-green',
        default => 'bg-warm-bg text-stone-gray',
    };
@endphp

<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $classes }}">
    {{ $label }}
</span>
