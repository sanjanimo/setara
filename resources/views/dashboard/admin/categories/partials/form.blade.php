@php $category = $category ?? null; @endphp

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
    <form method="POST" action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="space-y-6">
        @csrf
        @if ($category) @method('PUT') @endif

        <div>
            <label for="name" class="block text-sm font-medium text-stone-gray">Nama Kategori</label>
            <input id="name" name="name" type="text" value="{{ old('name', $category->name ?? '') }}" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
        </div>

        <div>
            <label for="target" class="block text-sm font-medium text-stone-gray">Target</label>
            <select id="target" name="target" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                <option value="umum" @selected(old('target', $category->target ?? 'umum') === 'umum')>Umum</option>
                <option value="anak" @selected(old('target', $category->target ?? '') === 'anak')>Anak</option>
                <option value="lansia" @selected(old('target', $category->target ?? '') === 'lansia')>Lansia</option>
            </select>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-stone-gray">Deskripsi</label>
            <textarea id="description" name="description" rows="3" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">{{ old('description', $category->description ?? '') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-border-soft px-4 py-2 text-sm font-medium text-stone-gray hover:border-teal-forest hover:text-teal-forest">Batal</a>
            <x-primary-button>Simpan Kategori</x-primary-button>
        </div>
    </form>
</x-card>
