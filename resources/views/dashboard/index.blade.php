@extends('layouts.app')

@section('header')
    <strong>{{ Auth::user()->name ?? 'Usuario' }}!</strong> Bienvenido al sistema.
@endsection
@section('title', 'Dashboard')
@section('content_header_title', 'Dashboard')

@section('content')

@endsection