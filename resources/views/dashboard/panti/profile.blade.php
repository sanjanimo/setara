@extends('layouts.dashboard')

@section('title', 'Profil Panti')
@section('page_title', 'Profil Panti')
@section('page_description', 'Kelola informasi dasar panti kamu.')

@section('content')

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-urgency-red/30 bg-urgency-red/10 px-4 py-3 text-sm text-urgency-red">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($panti)
        <div class="mb-6 rounded-xl border border-border-soft bg-warm-surface px-4 py-3 text-sm text-stone-gray">
            Status verifikasi:
            <span class="font-semibold text-stone-ink">{{ ucfirst($panti->verification_status) }}</span>

            @if ($panti->verification_status === 'pending')
                <span class="ml-2">Profil panti belum ditampilkan ke publik sampai diverifikasi admin.</span>
            @endif
        </div>
    @endif

    <x-card>
        <form method="POST" action="{{ route('panti.profile.update') }}" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Informasi Dasar --}}
            <div>
                <h2 class="text-base font-semibold text-stone-ink">Informasi Dasar</h2>

                <div class="mt-4 grid gap-4 md:grid-cols-2">

                    <div>
                        <label for="name" class="block text-sm font-medium text-stone-gray">Nama Panti</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $panti->name ?? '') }}"
                            placeholder="contoh: Panti Asuhan Tunas Harapan"
                            required
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-stone-gray">Tipe Panti</label>
                        <select id="type" name="type" required
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                            <option value="anak" @selected(old('type', $panti->type ?? '') === 'anak')>Panti Anak</option>
                            <option value="jompo" @selected(old('type', $panti->type ?? '') === 'jompo')>Panti Jompo</option>
                            <option value="campuran" @selected(old('type', $panti->type ?? '') === 'campuran')>Panti Campuran</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-stone-gray">Deskripsi</label>
                        <textarea id="description" name="description" rows="4"
                            placeholder="Ceritakan singkat sejarah, visi, dan kegiatan harian panti..."
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">{{ old('description', $panti->description ?? '') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Lokasi --}}
            <div>
                <h2 class="text-base font-semibold text-stone-ink">Lokasi</h2>

                <div class="mt-4 grid gap-4 md:grid-cols-3" x-data="locationPicker({
                    provinceName: @js(old('province', $panti->province ?? '')),
                    cityName: @js(old('city', $panti->city ?? '')),
                    districtName: @js(old('district', $panti->district ?? '')),
                    latitude: @js(old('latitude', $panti->latitude ?? '')),
                    longitude: @js(old('longitude', $panti->longitude ?? '')),
                })">
                    <div>
                        <label for="province_id" class="block text-sm font-medium text-stone-gray">Provinsi</label>
                        <select id="province_id" x-model="province_id" @change="onProvinceChange()"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                            <option value="">Pilih provinsi</option>
                            <template x-for="p in provinces" :key="p.id">
                                <option :value="p.id" x-text="p.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label for="city_id" class="block text-sm font-medium text-stone-gray">Kota / Kabupaten</label>
                        <select id="city_id" x-model="city_id" @change="onCityChange()" :disabled="!province_id"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm disabled:bg-warm-bg">
                            <option value="">Pilih kota</option>
                            <template x-for="c in cities" :key="c.id">
                                <option :value="c.id" x-text="c.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label for="district_id" class="block text-sm font-medium text-stone-gray">Kecamatan</label>
                        <select id="district_id" x-model="district_id" :disabled="!city_id"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm disabled:bg-warm-bg">
                            <option value="">Pilih kecamatan</option>
                            <template x-for="d in districts" :key="d.id">
                                <option :value="d.id" x-text="d.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Hidden fields agar server tetap menerima string --}}
                    <input type="hidden" name="province" :value="provinceName">
                    <input type="hidden" name="city" :value="cityName">
                    <input type="hidden" name="district" :value="districtName">
                    <input type="hidden" name="latitude" :value="latitude">
                    <input type="hidden" name="longitude" :value="longitude">

                    <div class="md:col-span-3">
                        <label for="address" class="block text-sm font-medium text-stone-gray">Alamat Lengkap</label>
                        <input id="address" name="address" type="text"
                            value="{{ old('address', $panti->address ?? '') }}"
                            placeholder="contoh: Jl. Merdeka No. 12"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-stone-gray">
                            Tentukan Lokasi di Peta
                            <span class="text-xs text-stone-gray">(geser pin atau cari alamat)</span>
                        </label>
                        <div class="mt-2 flex gap-2">
                            <input type="text" x-model="searchQuery" @keydown.enter.prevent="searchAddress()"
                                placeholder="Ketik alamat lalu tekan Enter..."
                                class="flex-1 rounded-lg border-border-soft bg-warm-surface text-sm">
                            <button type="button" @click="searchAddress()"
                                class="rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white hover:bg-teal-hover">
                                Cari
                            </button>
                        </div>
                        <div id="panti-picker-map"
                            class="mt-3 h-72 w-full overflow-hidden rounded-xl border border-border-soft"></div>
                        <p class="mt-2 text-xs text-stone-gray">
                            Koordinat tersimpan otomatis:
                            <span class="font-mono text-stone-ink" x-text="latitude + ', ' + longitude">—</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Presisi Lokasi Publik --}}
            <div>
                <h2 class="text-base font-semibold text-stone-ink">Tampilan Alamat di Halaman Publik</h2>
                <p class="mt-1 text-sm text-stone-gray">Pilih bagaimana alamat panti ditampilkan kepada pengunjung website.
                </p>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <label
                        class="relative cursor-pointer rounded-xl border border-border-soft bg-warm-bg p-4 transition has-[:checked]:border-teal-forest has-[:checked]:bg-teal-forest/5">
                        <input type="radio" name="location_precision" value="approximate" @checked(old('location_precision', $panti->location_precision ?? 'approximate') === 'approximate')
                            class="sr-only">
                        <p class="text-sm font-semibold text-stone-ink">📍 Hanya Area Umum</p>
                        <p class="mt-1 text-xs text-stone-gray">
                            Hanya nama kecamatan & kota yang tampil. Alamat detail disembunyikan.
                            <strong>(Direkomendasikan)</strong>
                        </p>
                    </label>

                    <label
                        class="relative cursor-pointer rounded-xl border border-border-soft bg-warm-bg p-4 transition has-[:checked]:border-teal-forest has-[:checked]:bg-teal-forest/5">
                        <input type="radio" name="location_precision" value="exact" @checked(old('location_precision', $panti->location_precision ?? '') === 'exact')
                            class="sr-only">
                        <p class="text-sm font-semibold text-stone-ink">🗺️ Alamat Detail</p>
                        <p class="mt-1 text-xs text-stone-gray">
                            Alamat lengkap akan terlihat di halaman publik panti.
                        </p>
                    </label>
                </div>
            </div>

            {{-- Pengelola --}}
            <div>
                <h2 class="text-base font-semibold text-stone-ink">Pengelola</h2>

                <div class="mt-4 grid gap-4 md:grid-cols-2">

                    <div>
                        <label for="manager_name" class="block text-sm font-medium text-stone-gray">Nama Pengelola</label>
                        <input id="manager_name" name="manager_name" type="text"
                            value="{{ old('manager_name', $panti->manager_name ?? '') }}"
                            placeholder="contoh: Budi Santoso"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>

                    <div>
                        <label for="manager_phone" class="block text-sm font-medium text-stone-gray">Telepon
                            Pengelola</label>
                        <input id="manager_phone" name="manager_phone" type="text"
                            value="{{ old('manager_phone', $panti->manager_phone ?? '') }}"
                            placeholder="contoh: 08123456789"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>

                </div>
            </div>

            {{-- Kapasitas --}}
            <div>
                <h2 class="text-base font-semibold text-stone-ink">Kapasitas dan Penghuni</h2>

                <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-5">

                    <div>
                        <label for="capacity" class="block text-sm font-medium text-stone-gray">Kapasitas</label>
                        <input id="capacity" name="capacity" type="number" min="0"
                            value="{{ old('capacity', $panti->capacity ?? 0) }}" required
                            placeholder="0"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>

                    <div>
                        <label for="total_residents" class="block text-sm font-medium text-stone-gray">Total
                            Penghuni</label>
                        <input id="total_residents" name="total_residents" type="number" min="0"
                            value="{{ old('total_residents', $panti->total_residents ?? 0) }}" required
                            placeholder="0"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>

                    <div>
                        <label for="children_count" class="block text-sm font-medium text-stone-gray">Jumlah Anak</label>
                        <input id="children_count" name="children_count" type="number" min="0"
                            value="{{ old('children_count', $panti->children_count ?? 0) }}" required
                            placeholder="0"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>

                    <div>
                        <label for="elderly_count" class="block text-sm font-medium text-stone-gray">Jumlah Lansia</label>
                        <input id="elderly_count" name="elderly_count" type="number" min="0"
                            value="{{ old('elderly_count', $panti->elderly_count ?? 0) }}" required
                            placeholder="0"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>

                    <div>
                        <label for="staff_count" class="block text-sm font-medium text-stone-gray">Jumlah Staff</label>
                        <input id="staff_count" name="staff_count" type="number" min="0"
                            value="{{ old('staff_count', $panti->staff_count ?? 0) }}" required
                            placeholder="0"
                            class="mt-1 w-full rounded-lg border-border-soft bg-warm-surface text-sm focus:border-teal-forest focus:ring-teal-forest">
                    </div>

                </div>
            </div>

            {{-- Consent --}}
            <div>
                <label class="flex items-start gap-3 rounded-xl border border-border-soft bg-warm-bg p-4">
                    <input type="checkbox" name="consent_agreement" value="1" @checked(old('consent_agreement', $panti->consent_agreement ?? false))
                        class="mt-1 rounded border-border-soft text-teal-forest focus:ring-teal-forest">
                    <span class="text-sm leading-relaxed text-stone-gray">
                        Saya menyatakan bahwa data yang diisi adalah benar dan setuju bahwa informasi agregat panti dapat
                        ditampilkan untuk keperluan penyaluran bantuan secara etis dan bertanggung jawab.
                    </span>
                </label>
            </div>

            <div class="flex justify-end">
                <x-primary-button>
                    Simpan Profil
                </x-primary-button>
            </div>

        </form>
    </x-card>

@endsection
