@extends('layouts.dashboard')

@section('title', 'Tambah Kebutuhan')
@section('page_title', 'Tambah Kebutuhan')
@section('page_description', 'Tambah kebutuhan baru untuk panti kamu.')

@section('content')

    @include('dashboard.panti.needs.partials.form', ['need' => null])

@endsection
