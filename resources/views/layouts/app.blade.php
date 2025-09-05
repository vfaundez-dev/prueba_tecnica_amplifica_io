
@extends('adminlte::page')

@section('title', config('app.name', 'Tiendas'))

@section('content_header')
    <h1>@yield('header', 'Panel')</h1>
@stop

@section('content')
    @yield('content')
@stop

@section('css')
    {{-- CSS adicional --}}
@stop

@section('js')
    {{-- JS adicional --}}
    @stack('scripts')
@stop
