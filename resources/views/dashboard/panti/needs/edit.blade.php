@extends('layouts.dashboard')

@section('title', 'Edit Kebutuhan')
@section('page_title', 'Edit Kebutuhan')
@section('page_description', 'Perbarui data kebutuhan panti.')

@section('content')

    @include('dashboard.panti.needs.partials.form', ['need' => $need])

@endsection
