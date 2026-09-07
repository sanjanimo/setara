@extends('layouts.dashboard')
@section('title', 'Ajukan Kunjungan')
@section('page_title', 'Ajukan Kegiatan ke ' . $panti->name)

@section('content')
    @if(!$canApply)
        <x-empty-state icon="shield" title="Syarat Modul Belum Terpenuhi" description="Kamu wajib lulus modul pembekalan untuk tipe panti ini ({{ implode(', ', $requiredTargets) }}) sebelum dapat mengajukan kunjungan.">
            <a href="{{ route('relawan.modules.index') }}" class="inline-flex items-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">
                Buka Modul Pembekalan
            </a>
        </x-empty-state>
    @else
        <x-card>
            <form method="POST" action="{{ route('relawan.applications.store', $panti->slug) }}" class="space-y-6" x-data="{ type: 'kunjungan' }">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-stone-gray">Jenis Kegiatan</label>
                    <select name="activity_type" x-model="type" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                        <option value="kunjungan">Kunjungan Sosial / Mengajar</option>
                        <option value="mentor">Menjadi Mentor (Youth)</option>
                        <option value="bantuan_logistik">Bantuan Distribusi Logistik</option>
                    </select>
                </div>

                <div x-show="type === 'mentor'" x-cloak>
                    <label class="block text-sm font-medium text-stone-gray">Pilih Youth yang akan didampingi (Opsional)</label>
                    <select name="youth_profile_id" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                        <option value="">-- Pilih Youth --</option>
                        @foreach($youths as $youth)
                            <option value="{{ $youth->id }}">{{ $youth->initials }} ({{ $youth->age }} thn) - Minat: {{ $youth->interests }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-gray">Motivasi</label>
                    <textarea name="motivation" rows="3" required class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest"></textarea>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-stone-gray">Keahlian / Keterampilan</label>
                        <input type="text" name="skills" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-gray">Ketersediaan Waktu</label>
                        <input type="text" name="availability" placeholder="Contoh: Sabtu Pagi" class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('relawan.applications.search') }}" class="rounded-lg border border-border-soft px-4 py-2 text-sm font-medium text-stone-gray hover:border-teal-forest hover:text-teal-forest">Batal</a>
                    <x-primary-button>Kirim Pengajuan</x-primary-button>
                </div>
            </form>
        </x-card>
    @endif
@endsection
