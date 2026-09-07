@php
    $need = $need ?? null;
@endphp

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
    <form
        method="POST"
        action="{{ $need ? route('panti.needs.update', $need) : route('panti.needs.store') }}"
        class="space-y-6"
    >
        @csrf

        @if ($need)
            @method('PUT')
        @endif

        <div class="grid gap-4 md:grid-cols-2">

            <div>
                <label for="need_category_id" class="block text-sm font-medium text-stone-gray">Kategori</label>
                <select
                    id="need_category_id"
                    name="need_category_id"
                    required
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                >
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('need_category_id', $need->need_category_id ?? '') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
    <label for="title" class="block text-sm font-medium text-stone-gray">Judul Kebutuhan</label>
    <div class="mb-6 rounded-xl border border-teal-forest/30 bg-teal-forest/5 px-4 py-3 text-sm text-teal-forest">
    💡 Kebutuhan bisa berupa apa saja: pangan, pakaian, peralatan rumah tangga, perlengkapan sekolah, jasa, atau pendampingan.
</div>
    <input
        id="title"
        name="title"
        type="text"
        value="{{ old('title', $need->title ?? '') }}"
        required
        placeholder="contoh: Beras 5kg, Sabun Mandi, Buku Tulis, Bimbingan Mengaji"
        class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm"
    >
</div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-stone-gray">Deskripsi</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                >{{ old('description', $need->description ?? '') }}</textarea>
            </div>

            <div>
    <label for="unit" class="block text-sm font-medium text-stone-gray">Satuan</label>
    <input
        id="unit"
        name="unit"
        type="text"
        list="unit-suggestions"
        value="{{ old('unit', $need->unit ?? '') }}"
        required
        placeholder="contoh: kg, dus, buah, liter, set"
        class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm"
    >
    <datalist id="unit-suggestions">
        <option value="kg">
        <option value="gram">
        <option value="liter">
        <option value="ml">
        <option value="dus">
        <option value="pack">
        <option value="buah">
        <option value="set">
        <option value="unit">
        <option value="lembar">
        <option value="pasang">
        <option value="orang">
        <option value="sesi">
        <option value="jam">
    </datalist>
    <p class="mt-1 text-xs text-stone-gray">Ketik satuan bebas atau pilih dari daftar.</p>
</div>

            <div>
                <label for="quantity_needed" class="block text-sm font-medium text-stone-gray">Jumlah Dibutuhkan</label>
                <input
                    id="quantity_needed"
                    name="quantity_needed"
                    type="number"
                    min="0"
                    value="{{ old('quantity_needed', $need->quantity_needed ?? 0) }}"
                    required
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                >
            </div>

            <div>
                <label for="current_stock" class="block text-sm font-medium text-stone-gray">Stok Saat Ini</label>
                <input
                    id="current_stock"
                    name="current_stock"
                    type="number"
                    min="0"
                    value="{{ old('current_stock', $need->current_stock ?? 0) }}"
                    required
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                >
            </div>

            <div>
                <label for="stock_days_remaining" class="block text-sm font-medium text-stone-gray">Stok Cukup Untuk (Hari)</label>
                <input
                    id="stock_days_remaining"
                    name="stock_days_remaining"
                    type="number"
                    min="0"
                    value="{{ old('stock_days_remaining', $need->stock_days_remaining ?? 0) }}"
                    required
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                >
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-stone-gray">Prioritas</label>
                <select
                    id="priority"
                    name="priority"
                    required
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                >
                    <option value="rendah" @selected(old('priority', $need->priority ?? '') === 'rendah')>Rendah</option>
                    <option value="sedang" @selected(old('priority', $need->priority ?? '') === 'sedang')>Sedang</option>
                    <option value="tinggi" @selected(old('priority', $need->priority ?? '') === 'tinggi')>Tinggi</option>
                    <option value="kritis" @selected(old('priority', $need->priority ?? '') === 'kritis')>Kritis</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-stone-gray">Status</label>
                <select
                    id="status"
                    name="status"
                    required
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                >
                    <option value="aktif" @selected(old('status', $need->status ?? 'aktif') === 'aktif')>Aktif</option>
                    <option value="terpenuhi" @selected(old('status', $need->status ?? '') === 'terpenuhi')>Terpenuhi</option>
                    <option value="ditunda" @selected(old('status', $need->status ?? '') === 'ditunda')>Ditunda</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="note" class="block text-sm font-medium text-stone-gray">Catatan</label>
                <textarea
                    id="note"
                    name="note"
                    rows="3"
                    class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"
                >{{ old('note', $need->note ?? '') }}</textarea>
            </div>

        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('panti.needs.index') }}" class="inline-flex items-center rounded-lg border border-border-soft px-4 py-2 text-sm font-medium text-stone-gray transition hover:border-teal-forest hover:text-teal-forest">
                Batal
            </a>

            <x-primary-button>
                Simpan Kebutuhan
            </x-primary-button>
        </div>

    </form>
</x-card>
