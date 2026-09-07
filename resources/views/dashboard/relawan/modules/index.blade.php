@extends('layouts.dashboard')

@section('title', 'Modul Pembekalan')
@section('page_title', 'Sekolah Relawan SETARA')
@section('page_description', 'Jalur dasar wajib untuk mengajukan kunjungan. Jalur lanjutan adalah sertifikasi kehormatanmu.')

@section('content')

@php
    $basics = $modules->where('level', 'basic');
    $advanced = $modules->where('level', 'advanced');
@endphp

<h2 class="text-lg font-semibold text-stone-ink mb-4"> Jalur Dasar <span class="text-xs font-semibold text-warm-amber uppercase">(Wajib untuk mengajukan kunjungan)</span></h2>
<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    @foreach ($basics as $module)
        @include('dashboard.relawan.modules.partials.card', ['module' => $module])
    @endforeach
</div>

<h2 class="text-lg font-semibold text-stone-ink mt-12 mb-4">🏅 Jalur Lanjutan <span class="text-xs font-semibold text-teal-forest uppercase">(Sertifikasi sukarela)</span></h2>
<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    @foreach ($advanced as $module)
        @include('dashboard.relawan.modules.partials.card', ['module' => $module])
    @endforeach
</div>

@endsection
