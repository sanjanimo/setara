@extends('layouts.dashboard')

@section('title', 'Tambah Kategori')
@section('page_title', 'Tambah Kategori')

@section('content')
    @include('dashboard.admin.categories.partials.form', ['category' => null])
@endsection
