@extends('layouts.app')

@section('title', 'SETARA — Bantuan tepat, karena kebutuhan terlihat')

@section('content')

    {{-- ========== HERO ========== --}}
    <section class="grain pulse-field relative overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 pb-24 pt-16 sm:px-6 lg:px-8 lg:pb-32 lg:pt-24">
            <div class="grid items-center gap-16 lg:grid-cols-[1.05fr_0.95fr]">

                <div data-aos="fade-right" data-aos-duration="1000">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-warm-amber">
                        Ekosistem kepedulian panti asuhan &amp; panti jompo
                    </p>

                    <h1 class="mt-6 text-4xl font-extrabold leading-[1.08] text-stone-ink sm:text-5xl lg:text-[3.4rem]">
                        Kebaikan tidak pernah kurang.
                        <br />
                        Ia hanya butuh
                        <span class="font-accent stroke-amber font-medium italic text-teal-forest">jalan</span>.
                    </h1>

                    <p class="mt-6 max-w-lg text-base leading-relaxed text-stone-gray sm:text-lg">
                        SETARA memetakan kebutuhan nyata panti asuhan dan panti jompo — agar bantuan tiba
                        di tempat yang paling membutuhkan, bukan yang paling terdengar.
                    </p>

                    <div class="mt-9 flex flex-col gap-3 sm:flex-row" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ route('map.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-forest px-6 py-3 text-sm font-semibold text-white shadow-sm outline-none transition hover:bg-teal-hover focus-visible:ring-2 focus-visible:ring-teal-forest focus-visible:ring-offset-2">
                            Lihat Peta Kebutuhan
                            <x-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-border-soft bg-warm-surface px-6 py-3 text-sm font-semibold text-stone-ink outline-none transition hover:border-teal-forest hover:text-teal-forest focus-visible:ring-2 focus-visible:ring-teal-forest focus-visible:ring-offset-2">
                            Saya Pengurus Panti
                        </a>
                    </div>

                    <div class="mt-9 flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-stone-gray" data-aos="fade-up"
                        data-aos-delay="300">
                        <span class="flex items-center gap-2"><span
                                class="h-1.5 w-1.5 rounded-full bg-teal-forest"></span>{{ $pantiCount }} panti
                            terdata</span>
                        <span class="flex items-center gap-2"><span
                                class="h-1.5 w-1.5 rounded-full bg-warm-amber"></span>{{ $needCount }} kebutuhan
                            aktif</span>
                        <span class="flex items-center gap-2"><span
                                class="h-1.5 w-1.5 rounded-full bg-urgency-green"></span>{{ $volunteerReady }} relawan
                            siap</span>
                    </div>

                    <p class="mt-5 text-[11px] leading-relaxed text-stone-gray/80" data-aos="fade-up"
                        data-aos-delay="400">
                        Angka ditarik langsung dari basis data SETARA. Versi ini demo untuk keperluan kompetisi.
                    </p>
                </div>

                {{-- Artefak hidup: kartu urgensi dari data asli --}}
                <div class="relative mx-auto w-full max-w-md lg:max-w-none" data-aos="fade-left" data-aos-duration="1200"
                    data-aos-delay="200">

                    <div
                        class="card-craft relative rotate-[1.2deg] rounded-3xl border border-border-soft bg-warm-surface p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs text-stone-gray">Panti prioritas hari ini</p>
                                <h2 class="mt-1 text-lg font-bold text-stone-ink">
                                    {{ $heroPanti->name ?? 'Panti Anak Cahaya Dago' }}
                                </h2>
                            </div>
                            <x-urgency-badge status="{{ $heroPanti->urgency_status ?? 'kritis' }}" />
                        </div>

                        <div class="relative mt-5 h-44 overflow-hidden rounded-2xl border border-border-soft z-0">
                            <div id="hero-map" class="h-full w-full"
                                data-lat="{{ $heroPanti->latitude ?? -6.9175 }}"
                                data-lng="{{ $heroPanti->longitude ?? 107.6191 }}"
                                data-name="{{ $heroPanti->name ?? 'Panti Prioritas' }}"
                                data-urgency="{{ $heroPanti->urgency_status ?? 'kritis' }}"></div>
                        </div>

                        <div class="mt-5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-semibold text-stone-ink">{{ $heroNeed->title ?? 'Beras' }}</span>
                                <span class="text-stone-gray">± {{ $heroNeed->stock_days_remaining ?? 2 }} hari stok</span>
                            </div>
                            <div class="stock-bar mt-2">
                                <span
                                    style="width: {{ min(100, round((($heroNeed->stock_days_remaining ?? 2) / 14) * 100)) }}%; background: #DC2626;"></span>
                            </div>
                            <p class="mt-3 text-xs text-stone-gray">
                                Diperbarui sendiri oleh panti • {{ now()->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="card-craft absolute -bottom-9 -left-4 hidden -rotate-2 rounded-2xl border border-border-soft bg-warm-surface px-5 py-4 sm:block"
                        data-aos="zoom-in-up" data-aos-delay="600">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-urgency-green/10 text-urgency-green">
                                <x-icon name="check-circle" class="h-4 w-4" />
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-stone-ink">20 kg beras • selesai</p>
                                <p class="text-[11px] text-stone-gray">kebaikan tercatat sampai tuntas</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- ========== ANGKA TENANG ========== --}}
    <!-- Menggunakan Teal Forest sebagai dasar (Section Breaker) -->
    <section class="relative overflow-hidden bg-teal-forest py-16 text-white lg:py-24">

        <!-- 1. Ambient Background: Bias cahaya lembut menggunakan versi transparan dari warna sekitarnya (Teal Hover dan putih) -->
        <div
            class="pointer-events-none absolute left-0 top-0 h-full w-full bg-[radial-gradient(ellipse_at_top_right,var(--tw-gradient-stops))] from-teal-hover/40 via-transparent to-transparent">
        </div>
        <div
            class="pointer-events-none absolute bottom-0 right-0 h-full w-full bg-[radial-gradient(ellipse_at_bottom_left,var(--tw-gradient-stops))] from-teal-900/40 via-transparent to-transparent">
        </div>

        <!-- 2. Tekstur Halus Opsional: Menambah kesan "bukan sekadar blok warna solid" -->
        <div class="pointer-events-none absolute inset-0 opacity-[0.03]"
            style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mx-auto max-w-3xl text-center" data-aos="fade-up">
                <!-- Teks pendukung menggunakan warna off-white transparan -->
                <p class="font-accent text-lg font-medium italic leading-relaxed text-white/80 sm:text-xl">
                    “Di balik setiap angka, ada nama.<br class="hidden sm:block" /> Di balik setiap nama, ada yang
                    menunggu.”
                </p>
            </div>

            <!-- 3. Grid Data: Menghilangkan card putih (karena BG sudah gelap) dan menggunakan border transparan membulat -->
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">

                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all duration-300 hover:bg-white/10"
                    data-aos="fade-up" data-aos-delay="100">
                    <p class="text-4xl font-bold tracking-tight text-white">{{ $pantiCount }}</p>
                    <p class="mt-2 text-sm font-medium text-white/70">Panti terdata &amp; terverifikasi</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all duration-300 hover:bg-white/10"
                    data-aos="fade-up" data-aos-delay="200">
                    <p class="text-4xl font-bold tracking-tight text-white">{{ $needCount }}</p>
                    <p class="mt-2 text-sm font-medium text-white/70">Kebutuhan aktif hari ini</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all duration-300 hover:bg-white/10"
                    data-aos="fade-up" data-aos-delay="300">
                    <!-- Aksen angka kuning diredam menggunakan Warm Amber terang agar pas dengan background gelap -->
                    <p class="text-4xl font-bold tracking-tight text-warm-amber sm:text-white">{{ $volunteerReady }}</p>
                    <p class="mt-2 text-sm font-medium text-white/70">Relawan lulus pembekalan</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all duration-300 hover:bg-white/10"
                    data-aos="fade-up" data-aos-delay="400">
                    <p class="text-4xl font-bold tracking-tight text-white">{{ $completedCount }}</p>
                    <p class="mt-2 text-sm font-medium text-white/70">Kebaikan dituntaskan</p>
                </div>

            </div>

            <div class="mt-12 flex items-center justify-center gap-3 border-t border-white/10 pt-6 text-xs text-white/60"
                data-aos="fade-in" data-aos-delay="500">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-40"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-white/70"></span>
                </span>
                <p>Angka diperbarui langsung saat panti memperbarui kondisinya — bukan estimasi.</p>
            </div>

        </div>
    </section>

    {{-- ========== MASALAH ========== --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
        <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr]">
            <div data-aos="fade-right">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-warm-amber">Masalahnya</p>
                <h2 class="mt-4 text-3xl font-bold leading-tight text-stone-ink sm:text-4xl">
                    Kebaikan sering menumpuk
                    <span class="font-accent italic text-teal-forest">di tempat yang terlihat</span>.
                </h2>
            </div>

            <div class="space-y-9">
                <div class="border-l-2 border-dashed border-border-soft pl-6" data-aos="fade-up" data-aos-delay="100">
                    <p class="font-accent text-lg italic text-warm-amber">01</p>
                    <h3 class="mt-1 text-base font-semibold text-stone-ink">Yang mendesak, sering tak terlihat</h3>
                    <p class="mt-2 text-sm leading-relaxed text-stone-gray">
                        Panti dengan kebutuhan paling kritis justru sering paling sepi — karena bukan mereka yang paling
                        terdengar.
                    </p>
                </div>
                <div class="border-l-2 border-dashed border-border-soft pl-6" data-aos="fade-up" data-aos-delay="200">
                    <p class="font-accent text-lg italic text-warm-amber">02</p>
                    <h3 class="mt-1 text-base font-semibold text-stone-ink">Donatur ingin membantu, tapi buta arah</h3>
                    <p class="mt-2 text-sm leading-relaxed text-stone-gray">
                        Niat baik berhenti di bingung: kebutuhan apa yang benar-benar diperlukan hari ini?
                    </p>
                </div>
                <div class="border-l-2 border-dashed border-border-soft pl-6" data-aos="fade-up" data-aos-delay="300">
                    <p class="font-accent text-lg italic text-warm-amber">03</p>
                    <h3 class="mt-1 text-base font-semibold text-stone-ink">Niat baik butuh pembekalan</h3>
                    <p class="mt-2 text-sm leading-relaxed text-stone-gray">
                        Relawan datang dengan hati terbuka — tapi anak dan lansia berhak didampingi dengan cara yang tepat.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== CARA KERJA ========== --}}
    <section id="cara-kerja" class="border-y border-border-soft bg-warm-surface">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="max-w-2xl" data-aos="fade-up">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-warm-amber">Cara kerja</p>
                <h2 class="mt-4 text-3xl font-bold text-stone-ink">Sebuah benang yang menyambungkan empat tangan.</h2>
            </div>

            <div class="mt-10" data-aos="fade-up" data-aos-delay="200">
                <img src="{{ asset('images/home/triase.jpeg') }}" alt="Ilustrasi alur kerja SETARA"
                    class="w-full rounded-2xl border border-border-soft" />
                <p class="mt-2 text-[11px] text-stone-gray/80">Ilustrasi konseptual SETARA — bukan foto individu nyata.</p>
            </div>

            <div class="mt-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([['t' => 'Panti bersuara', 'd' => 'Pengurus memperbarui kebutuhan panti sendiri — secara bermartabat.'], ['t' => 'SETARA mengukur', 'd' => 'Status aman, waspada, kritis — selalu segar, selalu terlihat.'], ['t' => 'Kamu bertindak', 'd' => 'Donasi barang, tenaga, atau jadilah mentor bagi seorang remaja.'], ['t' => 'Kebutuhan reda', 'd' => 'Status diperbarui. Kebaikan tercatat sampai tuntas.']] as $i => $step)
                    <div class="relative flex flex-col items-center text-center" data-aos="fade-up"
                        data-aos-delay="{{ ($i + 1) * 150 }}">
                        <!-- Garis Penghubung antar Titik Tengah -->
                        @if ($i < 3)
                            <div class="absolute top-3 z-0 hidden border-t-2 border-dashed border-teal-forest/25 lg:block"
                                style="left: calc(50% + 12px); width: calc(100% + 2.5rem - 24px);"></div>
                        @endif

                        <!-- Bulatan Angka (Center) -->
                        <span
                            class="relative z-10 inline-flex h-6 w-6 items-center justify-center rounded-full bg-teal-forest text-[11px] font-bold text-white shadow-sm">
                            {{ $i + 1 }}
                        </span>

                        <!-- Judul & Deskripsi -->
                        <h3 class="mt-4 text-base font-semibold text-stone-ink">{{ $step['t'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-stone-gray">{{ $step['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        </div>
    </section>

    {{-- ========== HARI INI MEREKA MENUNGGU (bento utuh) ========== --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
        <!-- Header Section (Link pojok kanan sudah dihapus) -->
        <div data-aos="fade-right">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-warm-amber">Kebutuhan nyata</p>
            <h2 class="mt-2 text-3xl font-bold text-stone-ink">Hari ini, mereka menunggu.</h2>
        </div>

        @if ($waitingNeeds->isEmpty())
            <p class="mt-10 text-sm text-stone-gray" data-aos="fade-up">
                Saat ini tidak ada kebutuhan kritis. Sebuah kabar baik yang tenang.
            </p>
        @else
            <!-- Grid Container -->
            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                <!-- 1. CARD PETA KEBUTUHAN (Berdiri sendiri di atas, Lebar Penuh & Center) -->
                <div class="relative min-h-70 overflow-hidden rounded-2xl border border-border-soft shadow-sm md:col-span-2 lg:col-span-3"
                    data-aos="fade-up" data-aos-delay="100">
                    <!-- Layer 1: Peta Leaflet (Di Belakang) -->
                    <div id="preview-map" class="pointer-events-none absolute inset-0 z-0 h-full w-full"></div>

                    <!-- Layer 2: Overlay Teal 30% Opacity + Content Center -->
                    <div
                        class="relative z-10 flex h-full flex-col items-center justify-between p-6 text-center sm:p-10 text-white bg-teal-950/30 backdrop-blur-sm">
                        <div class="flex flex-col items-center">
                            <!-- Badge Indikator -->
                            <div
                                class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-black/20 px-3 py-1">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                </span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-300">Peta
                                    Kebutuhan</span>
                            </div>

                            <h3 class="text-2xl font-bold text-white sm:text-3xl drop-shadow-md">
                                {{ $waitingNeeds->count() }} panti terverifikasi menunggu di peta.
                            </h3>

                            <p class="mt-2 text-sm text-white/90 drop-shadow-md max-w-xl">
                                Pin merah yang berdenyut menandai lokasi panti dengan kebutuhan paling mendesak hari ini.
                            </p>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('map.index') }}"
                                class="inline-flex items-center gap-2 rounded-2xl border border-white/30 bg-white/20 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-white/30">
                                Buka peta lengkap
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. CARD KEBUTUHAN PANTI (3 Card Berjejer Sejajar di Bawah) -->
                @foreach ($waitingNeeds as $index => $need)
                    @php
                        $days = $need->stock_days_remaining;
                        $barColor = $days <= 3 ? '#DC2626' : ($days <= 7 ? '#F59E0B' : '#16A34A');
                    @endphp
                    <a href="{{ route('pantis.show', $need->panti->slug) }}" data-aos="fade-up"
                        data-aos-delay="{{ 200 + $index * 100 }}"
                        class="card-craft group flex flex-col justify-between rounded-2xl border border-border-soft bg-warm-surface p-6 outline-none transition hover:-translate-y-0.5 focus-visible:ring-2 focus-visible:ring-teal-forest focus-visible:ring-offset-2">
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs text-stone-gray">{{ $need->panti->name }} •
                                        {{ $need->panti->city }}</p>
                                    <h3 class="mt-1 text-base font-bold text-stone-ink">{{ $need->title }}</h3>
                                </div>
                                <span class="shrink-0 rounded-full px-3 py-1 text-[10px] font-bold uppercase"
                                    style="background: {{ $barColor }}1a; color: {{ $barColor }};">
                                    {{ ucfirst($need->priority) }}
                                </span>
                            </div>

                            @if ($need->description)
                                <p class="mt-3 text-sm leading-relaxed text-stone-gray line-clamp-2">
                                    {{ $need->description }}</p>
                            @endif
                        </div>

                        <div class="mt-5">
                            <div class="stock-bar">
                                <span
                                    style="width: {{ min(100, round(($days / 14) * 100)) }}%; background: {{ $barColor }};"></span>
                            </div>
                            <p class="mt-2 text-xs text-stone-gray">± {{ $days }} hari stok tersisa</p>

                            <p
                                class="mt-4 text-sm font-semibold text-teal-forest opacity-0 transition group-hover:opacity-100">
                                Lihat kebutuhan →
                            </p>
                        </div>
                    </a>
                @endforeach

            </div>
        @endif
    </section>

    {{-- ========== PEMBEKALAN & PENDAMPINGAN ========== --}}
    <section class="border-y border-border-soft bg-warm-surface">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div data-aos="fade-right" data-aos-duration="800">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-warm-amber">Pembekalan &amp;
                        Pendampingan</p>
                    <h2 class="mt-4 text-3xl font-bold text-stone-ink">
                        Niat baik butuh kesiapan, bukan cuma keberanian.
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-stone-gray">
                        Sebelum berkunjung, relawan mengikuti modul singkat agar kegiatan berjalan aman,
                        hangat, dan bermartabat bagi semua pihak.
                    </p>
                </div>

                <div data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
                    <img src="{{ asset('images/home/mentoring.jpeg') }}"
                        alt="Ilustrasi relawan mendampingi anak membaca buku"
                        class="w-full rounded-2xl border border-border-soft" />
                    <p class="mt-2 text-[11px] text-stone-gray/80">Ilustrasi konseptual SETARA — bukan foto individu nyata.
                    </p>
                </div>
            </div>

            <!-- Container Utama -->
            <div
                class="relative mt-8 space-y-10 before:absolute before:inset-0 before:left-8 before:h-full before:w-0.5 before:-translate-x-1/2 before:border-l-2 before:border-dashed before:border-emerald-200">

                <!-- Item 1 -->
                <div class="relative flex items-start gap-8" data-aos="fade-up" data-aos-delay="100">
                    <!-- Opsitas Solid (bg-emerald-100 tanpa /80) -->
                    <div
                        class="relative z-10 flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 shadow-sm">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="pt-2">
                        <h3 class="text-lg font-bold text-stone-ink">Etika Berkunjung ke Panti</h3>
                        <p class="mt-1 text-sm text-stone-gray">Izin, jadwal, privasi, dan batasan yang wajib dihormati —
                            sebelum langkah pertama.</p>
                    </div>
                </div>

                <!-- Item 2 (Kuning/Amber Solid) -->
                <div class="relative flex items-start gap-8" data-aos="fade-up" data-aos-delay="250">
                    <!-- Opsitas Solid (bg-amber-100 tanpa /80) -->
                    <div
                        class="relative z-10 flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700 shadow-sm">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="pt-2">
                        <h3 class="text-lg font-bold text-stone-ink">Komunikasi dengan Anak & Remaja</h3>
                        <p class="mt-1 text-sm text-stone-gray">Bahasa yang aman, inklusif, dan tidak memaksa — termasuk
                            mendampingi minat keterampilan remaja usia produktif menuju kemandirian.</p>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="relative flex items-start gap-8" data-aos="fade-up" data-aos-delay="400">
                    <!-- Opsitas Solid (bg-emerald-100 tanpa /80) -->
                    <div
                        class="relative z-10 flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 shadow-sm">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="pt-2">
                        <h3 class="text-lg font-bold text-stone-ink">Pendampingan Lansia</h3>
                        <p class="mt-1 text-sm text-stone-gray">Interaksi yang sabar, penuh hormat, dan menyenangkan —
                            lansia didampingi, bukan dikasihani.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ========== ADA TEMPAT UNTUKMU ========== --}}
    <section id="relawan" class="bg-warm-bg">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div data-aos="fade-right">
                <h2 class="text-3xl font-bold text-stone-ink">Ada tempat untukmu di sini.</h2>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-stone-gray">
                    SETARA bukan hanya untuk donatur. Ia ekosistem — dan setiap peran sama pentingnya.
                </p>
            </div>

            <div class="mt-10 space-y-5">

                <!-- Card 1: Donatur -->
                <!-- Card 1: Donatur -->
                <div data-aos="fade-up" data-aos-delay="100"
                    class="card-craft flex flex-col gap-4 rounded-2xl border border-border-soft bg-warm-surface p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md sm:flex-row sm:items-center sm:justify-between sm:p-8">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-warm-amber">Donatur</p>
                        <h3 class="mt-1 text-lg font-bold text-stone-ink">Kebaikanmu, tepat sasaran.</h3>
                        <p class="mt-1 text-sm text-stone-gray">Lihat apa yang benar-benar dibutuhkan sebelum berdonasi —
                            dan ikuti sampai tuntas.</p>
                    </div>
                    <a href="{{ route('register') }}"
                        class="inline-flex shrink-0 items-center justify-center rounded-2xl border border-warm-amber px-5 py-2.5 text-sm font-semibold text-warm-amber shadow-sm transition hover:bg-warm-amber hover:text-white focus-visible:ring-2 focus-visible:ring-warm-amber focus-visible:ring-offset-2">
                        Daftar sebagai Donatur
                    </a>
                </div>

                <!-- Card 2: Relawan -->
                <div data-aos="fade-up" data-aos-delay="200"
                    class="card-craft flex flex-col gap-4 rounded-2xl border border-border-soft bg-warm-surface p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md sm:flex-row sm:items-center sm:justify-between sm:p-8">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-forest">Relawan</p>
                        <h3 class="mt-1 text-lg font-bold text-stone-ink">Datang siap, pulang berarti.</h3>
                        <p class="mt-1 text-sm text-stone-gray">Modul pembekalan etika &amp; komunikasi menemanimu sebelum
                            langkah pertamamu.</p>
                    </div>
                    <a href="{{ route('register') }}"
                        class="inline-flex shrink-0 items-center justify-center rounded-2xl border border-teal-forest px-5 py-2.5 text-sm font-semibold text-teal-forest shadow-sm transition hover:bg-teal-forest hover:text-white focus-visible:ring-2 focus-visible:ring-teal-forest focus-visible:ring-offset-2">
                        Jadi Relawan
                    </a>
                </div>

                <!-- Card 3: Pengurus Panti -->
                <div data-aos="fade-up" data-aos-delay="300"
                    class="card-craft flex flex-col gap-4 rounded-2xl border border-border-soft bg-warm-surface p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md sm:flex-row sm:items-center sm:justify-between sm:p-8">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-600">Pengurus panti</p>
                        <h3 class="mt-1 text-lg font-bold text-stone-ink">Pantimu layak terlihat — dengan martabatnya.</h3>
                        <p class="mt-1 max-w-xl text-sm text-stone-gray">
                            Daftarkan pantimu, perbarui kebutuhanmu sendiri, dan biarkan kebaikan menemukan jalannya —
                            tanpa foto yang dieksploitasi, tanpa cerita yang dijual.
                        </p>
                    </div>
                    <a href="{{ route('register') }}"
                        class="inline-flex shrink-0 items-center justify-center rounded-2xl border border-teal-forest px-5 py-2.5 text-sm font-semibold text-teal-forest shadow-sm transition hover:bg-teal-forest hover:text-white focus-visible:ring-2 focus-visible:ring-teal-forest focus-visible:ring-offset-2">
                        Daftarkan Panti
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ========== JANJI KAMI ========== --}}
    <section class="stitch bg-warm-bg">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
            <p class="font-accent text-xl italic text-stone-ink" data-aos="fade-right">Janji kami</p>

            <!-- Grid Container: Mengubah gap dari 8 ke 6 agar jarak antar card tidak terlalu renggang -->
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

                <!-- Card 1 -->
                <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm transition-shadow duration-300 hover:shadow-md"
                    data-aos="fade-up" data-aos-delay="100">
                    <x-icon name="shield" class="h-8 w-8 text-teal-forest" />
                    <h3 class="mt-4 text-base font-bold text-stone-ink">Tanpa foto yang dieksploitasi. Selamanya.</h3>
                    <p class="mt-2 text-sm leading-relaxed text-stone-gray">Warga panti bukan objek iba. Data tampil
                        agregat dan bermartabat.</p>
                </div>

                <!-- Card 2 -->
                <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm transition-shadow duration-300 hover:shadow-md"
                    data-aos="fade-up" data-aos-delay="200">
                    <x-icon name="check-circle" class="h-8 w-8 text-teal-forest" />
                    <h3 class="mt-4 text-base font-bold text-stone-ink">Data diverifikasi, diperbarui panti sendiri.</h3>
                    <p class="mt-2 text-sm leading-relaxed text-stone-gray">Urgensi bukan tebakan — ia suara panti yang
                        diukur.</p>
                </div>

                <!-- Card 3 -->
                <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm transition-shadow duration-300 hover:shadow-md"
                    data-aos="fade-up" data-aos-delay="300">
                    <x-icon name="book-open" class="h-8 w-8 text-teal-forest" />
                    <h3 class="mt-4 text-base font-bold text-stone-ink">Relawan dibekali sebelum berkunjung.</h3>
                    <p class="mt-2 text-sm leading-relaxed text-stone-gray">Anak dan lansia berhak didampingi dengan cara
                        yang tepat.</p>
                </div>

                <!-- Card 4 -->
                <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm transition-shadow duration-300 hover:shadow-md"
                    data-aos="fade-up" data-aos-delay="400">
                    <x-icon name="heart" class="h-8 w-8 text-teal-forest" />
                    <h3 class="mt-4 text-base font-bold text-stone-ink">Setiap kebaikan tercatat sampai tuntas.</h3>
                    <p class="mt-2 text-sm leading-relaxed text-stone-gray">Diajukan, dikonfirmasi, selesai — transparan
                        bagi semua pihak.</p>
                </div>

            </div>

            <!-- Footer Teks -->
            <div class="mt-12 max-w-2xl rounded-lg border-l-4 border-teal-forest bg-white p-4 shadow-sm">
                <p class="text-base leading-relaxed text-stone-gray">
                    Karena yang kami dampingi bukan objek belas kasihan — mereka adalah <span
                        class="font-accent stroke-amber italic text-teal-forest">setara</span>.
                </p>
            </div>
        </div>
    </section>

    {{-- ========== PENUTUP ========== --}}
    <section class="relative w-full overflow-hidden bg-warm-surface py-20 text-center sm:py-28">

        <div
            class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-75 w-125 rounded-full bg-teal-500/20 blur-[100px]">
        </div>
        <div
            class="pointer-events-none absolute left-1/3 top-1/2 -translate-x-1/2 -translate-y-1/2 h-50 w-75 rounded-full bg-amber-400/20 blur-[90px]">
        </div>

        <div class="relative w-full border-y border-white/60 bg-teal-950/20 py-16 backdrop-blur-md shadow-lg sm:py-24">

            <div class="pointer-events-none absolute inset-0 bg-linear-to-r from-white/15 via-transparent to-white/10">
            </div>

            <div class="absolute inset-x-0 top-0 h-px bg-linear-to-r from-transparent via-white/80 to-transparent">
            </div>
            <div class="absolute inset-x-0 bottom-0 h-px bg-linear-to-r from-transparent via-white/40 to-transparent">
            </div>

            <div class="relative z-10 mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

                <div data-aos="fade-up" data-aos-duration="1000">
                    <div
                        class="mb-6 inline-flex items-center justify-center rounded-full border border-amber-400/40 bg-amber-400/10 px-3.5 py-1 text-amber-500 backdrop-blur-md">
                        <svg class="mr-1.5 h-4 w-4 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 9.5L12 2Z" />
                        </svg>
                        <span class="text-xs font-semibold tracking-wider uppercase text-amber-600">Langkah Kecil</span>
                    </div>

                    <h2
                        class="font-serif text-3xl font-light italic leading-relaxed tracking-wide text-stone-ink sm:text-4xl lg:text-5xl">
                        “Suatu hari, semua kebaikan akan menemukan jalannya pulang.”
                    </h2>

                    <p class="mt-4 text-base font-normal leading-relaxed text-stone-gray sm:text-lg">
                        Jalan itu bisa dimulai dari kamu — hari ini.
                    </p>
                </div>

                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row" data-aos="fade-up"
                    data-aos-delay="300" data-aos-duration="1000">
                    <a href="{{ route('map.index') }}"
                        class="inline-flex items-center gap-2 rounded-2xl bg-teal-forest px-7 py-3.5 text-sm font-bold text-white shadow-md transition duration-300 hover:bg-teal-700 hover:-translate-y-0.5">
                        Lihat Peta Kebutuhan
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>

                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 rounded-2xl border border-teal-forest/30 bg-white/40 px-7 py-3.5 text-sm font-semibold text-teal-forest shadow-sm backdrop-blur-md transition duration-300 hover:border-teal-forest hover:bg-white/70 hover:-translate-y-0.5">
                        Daftar sebagai Relawan
                    </a>
                </div>

            </div>
        </div>
    </section>



@endsection

