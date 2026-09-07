@extends('layouts.app')

@section('title', 'Bantu ' . $panti->name . ' — SETARA')

@section('content')

    <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">

        <div class="mb-8">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-warm-amber">
                Niat Bantuan
            </p>

            <h1 class="mt-3 text-3xl font-bold text-stone-ink">
                Bantu {{ $panti->name }}
            </h1>

            <p class="mt-3 text-sm leading-relaxed text-stone-gray">
                SETARA tidak memproses pembayaran. Bantuan berupa barang atau tenaga akan dikonfirmasi oleh panti, lalu dikoordinasikan langsung dengan kamu.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-urgency-red/30 bg-urgency-red/10 px-4 py-3 text-sm text-urgency-red">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-card>
            <div class="mb-6 rounded-xl border border-border-soft bg-warm-bg p-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-stone-ink">{{ $panti->name }}</h2>
                        <p class="mt-1 text-sm text-stone-gray">
                            {{ ucfirst($panti->type) }} • {{ $panti->city }}
                        </p>
                    </div>

                    <x-urgency-badge :status="$panti->urgency_status" />
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('donatur.donations.store', $panti->slug) }}"
                class="space-y-6"
                x-data="{ type: '{{ old('type', 'barang') }}' }"
            >
                @csrf

                <div>
                    <label for="type" class="block text-sm font-medium text-stone-gray">Jenis Bantuan</label>

                    <select
                        id="type"
                        name="type"
                        x-model="type"
                        required
                        class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                    >
                        <option value="barang" @selected(old('type', 'barang') === 'barang')>Barang</option>
                        <option value="tenaga" @selected(old('type') === 'tenaga')>Tenaga</option>
                    </select>
                </div>

                <div x-show="type === 'barang'" x-cloak>
                    <label for="panti_need_id" class="block text-sm font-medium text-stone-gray">Kebutuhan Panti</label>

                    <select
                        id="panti_need_id"
                        name="panti_need_id"
                        class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                    >
                        <option value="">Pilih kebutuhan</option>

                        @foreach ($needs as $need)
                            <option
                                value="{{ $need->id }}"
                                @selected(old('panti_need_id', $selectedNeedId ?? null) == $need->id)
                            >
                                {{ $need->title }} — {{ $need->category->name ?? 'Kebutuhan' }} (stok {{ $need->stock_days_remaining }} hari)
                            </option>
                        @endforeach
                    </select>

                    @if ($needs->isEmpty())
                        <p class="mt-2 text-xs text-stone-gray">
                            Saat ini panti tidak memiliki kebutuhan barang aktif. Kamu tetap dapat membantu sebagai relawan tenaga.
                        </p>
                    @endif
                </div>

                <div x-show="type === 'barang'" x-cloak>
                    <label for="quantity" class="block text-sm font-medium text-stone-gray">Jumlah Bantuan</label>

                    <input
                        id="quantity"
                        name="quantity"
                        type="number"
                        min="1"
                        value="{{ old('quantity') }}"
                        class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                    >
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-stone-gray">Pesan / Keterangan</label>

                    <textarea
                        id="message"
                        name="message"
                        rows="4"
                        class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                        placeholder="Contoh: Saya dapat mengantarkan barang pada akhir pekan."
                    >{{ old('message') }}</textarea>
                </div>

                <div class="rounded-xl border border-border-soft bg-warm-bg p-4 text-xs leading-relaxed text-stone-gray">
                    Dengan mengirim niat bantuan, kamu memahami bahwa bantuan akan dikonfirmasi oleh panti. Pengelola panti dapat menghubungi kamu melalui kontak akun untuk koordinasi.
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('pantis.show', $panti->slug) }}" class="inline-flex items-center rounded-lg border border-border-soft px-4 py-2 text-sm font-medium text-stone-gray transition hover:border-teal-forest hover:text-teal-forest">
                        Batal
                    </a>

                    <x-primary-button>
                        Kirim Niat Bantuan
                    </x-primary-button>
                </div>
            </form>
        </x-card>

    </section>

@endsection
