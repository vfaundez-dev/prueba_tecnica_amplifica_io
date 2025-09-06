@extends('layouts.app')

@section('header')
    <strong>{{ Auth::user()->name ?? 'Usuario' }}!</strong> Bienvenido al sistema.
@endsection
@section('title', 'Dashboard')
@section('content_header_title', 'Dashboard')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-end align-items-center">
            
        </div>
        <div class="card-body">
            @if(session('success'))
                <x-adminlte-alert theme="success" title="{{ session('success') }}" />
            @endif

            @if(session('error'))
                <x-adminlte-alert theme="danger" title="{{ session('error') }}" />
            @endif

            @if(session('info'))
                <x-adminlte-alert theme="info" title="{{ session('info') }}" />
            @endif
        </div>
    </div>
@endsection