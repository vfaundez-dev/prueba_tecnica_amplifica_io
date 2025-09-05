@extends('layouts.app')

@section('header','Dashboard')
@section('title', 'Dashboard')
@section('content_header_title', 'Dashboard')

@section('content')

{{ Auth::user()->name }} Bienvenido al sistema.

@endsection