@extends('layouts.dashboard')

@section('title', 'Tambah Youth')
@section('page_title', 'Tambah Youth')
@section('page_description', 'Tambah profil remaja panti secara anonim.')

@section('content')
    @include('dashboard.panti.youth.partials.form', ['youth' => null])
@endsection
