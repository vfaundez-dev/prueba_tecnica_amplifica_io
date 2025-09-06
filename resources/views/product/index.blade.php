@extends('layouts.app')

@section('header','Productos')
@section('title', 'Productos')
@section('content_header_title', 'Productos')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-end align-items-center">
            <form action="{{ route('product.getAll') }}" method="POST" class="w-50">
                @csrf
                <div class="input-group mb-3">
                    <select name="shop" class="custom-select form-control-border">
                        <option value="">-- Selecciona una tienda --</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ isset($shop_id['shop']) && ($shop_id['shop'] == $shop->id) ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addShopModal">
                            Consultar Productos
                        </button>
                    </div>
                    @if(isset($products))
                        <a href="{{ route('export.products') }}" class="btn btn-success ml-2">
                            Exportar
                        </a>
                    @endif
                </div>
            </form>
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

            @if( isset($products) )
                @forelse ($products as $product)
                    <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src={{ $product['image'] }} class="img-fluid rounded-start" alt={{ $product['title'] }}>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold">{{ $product['title'] }}</h5>
                                <p class="card-text font-weight-bold">
                                    SKU: <span class="font-weight-normal">{{ $product['sku'] }}</span>
                                </p>
                                {!! $product['description'] !!}
                                <p class="card-text"><strong>Price: </strong> ${{ number_format($product['price'], 2) }}</p>
                                <p class="card-text">
                                    <small class="text-muted">ID: {{ $product['id'] }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    </div>
                @empty
                    <h5 class="text-center">
                        No hay productos disponibles
                    </h5>
                @endforelse
            @else
                <h5 class="text-center">
                    Selecciona una tienda para ver sus productos
                </h5>
            @endif

        </div>
    </div>
@endsection