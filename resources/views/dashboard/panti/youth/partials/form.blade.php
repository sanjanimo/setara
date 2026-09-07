@php $youth = $youth ?? null; @endphp

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
    <div class="mb-6 rounded-xl border border-warm-amber/30 bg-warm-amber/10 px-4 py-3 text-xs leading-relaxed text-warm-amber">
        Etika: gunakan inisial, bukan nama lengkap. Jangan menyimpan foto, alamat, sekolah, atau data sensitif lainnya.
    </div>

    <form method="POST" action="{{ $youth ? route('panti.youth.update', $youth) : route('panti.youth.store') }}" class="space-y-6">
        @csrf
        @if ($youth) @method('PUT') @endif

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="initials" class="block text-sm font-medium text-stone-gray">Inisial</label>
                <input id="initials" name="initials" type="text" maxlength="5" value="{{ old('initials', $youth->initials ?? '') }}" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
            </div>
            <div>
                <label for="age" class="block text-sm font-medium text-stone-gray">Usia</label>
                <input id="age" name="age" type="number" min="13" max="22" value="{{ old('age', $youth->age ?? '') }}" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-stone-gray">Status</label>
                <select id="status" name="status" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    <option value="baru" @selected(old('status', $youth->status ?? 'baru') === 'baru')>Baru</option>
                    <option value="didampingi" @selected(old('status', $youth->status ?? '') === 'didampingi')>Didampingi</option>
                    <option value="selesai" @selected(old('status', $youth->status ?? '') === 'selesai')>Selesai</option>
                </select>
            </div>
        </div>

        <div>
            <label for="interests" class="block text-sm font-medium text-stone-gray">Minat</label>
            <input id="interests" name="interests" type="text" value="{{ old('interests', $youth->interests ?? '') }}" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="skill_goals" class="block text-sm font-medium text-stone-gray">Tujuan Keterampilan</label>
                <input id="skill_goals" name="skill_goals" type="text" value="{{ old('skill_goals', $youth->skill_goals ?? '') }}" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
            </div>
            <div>
                <label for="training_needs" class="block text-sm font-medium text-stone-gray">Kebutuhan Pelatihan</label>
                <input id="training_needs" name="training_needs" type="text" value="{{ old('training_needs', $youth->training_needs ?? '') }}" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
            </div>
        </div>

        {{-- Checkbox Mentor Needed --}}
        <label class="flex items-start gap-3 rounded-xl border border-border-soft bg-warm-bg p-4 cursor-pointer">
            <input type="checkbox" name="mentor_needed" value="1" @checked(old('mentor_needed', $youth->mentor_needed ?? false)) class="mt-1 rounded border-border-soft text-teal-forest focus:ring-teal-forest">
            <span>
                <span class="text-sm font-medium text-stone-ink">Butuh mentor</span>
                <p class="mt-1 text-xs text-stone-gray">Centang jika remaja ini ingin didampingi relawan untuk belajar keterampilan (menggambar, bahasa Inggris, komputer, dll). Relawan hanya melihat inisial dan minat.</p>
            </span>
        </label>

        <div>
            <label for="note" class="block text-sm font-medium text-stone-gray">Catatan</label>
            <textarea id="note" name="note" rows="3" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">{{ old('note', $youth->note ?? '') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('panti.youth.index') }}" class="rounded-lg border border-border-soft px-4 py-2 text-sm font-medium text-stone-gray hover:border-teal-forest hover:text-teal-forest">Batal</a>
            <x-primary-button>Simpan Profil</x-primary-button>
        </div>
    </form>
</x-card>
