@extends('layouts.dashboard')

@section('title', 'Edit Kategori')
@section('page_title', 'Edit Kategori')

@section('content')
    @include('dashboard.admin.categories.partials.form', ['category' => $category])
@endsection
