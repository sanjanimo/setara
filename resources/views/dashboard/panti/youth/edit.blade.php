@extends('layouts.dashboard')

@section('title', 'Edit Youth')
@section('page_title', 'Edit Youth')
@section('page_description', 'Perbarui profil remaja panti.')

@section('content')
    @include('dashboard.panti.youth.partials.form', ['youth' => $youth])
@endsection
