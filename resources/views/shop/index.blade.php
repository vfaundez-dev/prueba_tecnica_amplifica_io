@extends('layouts.app')

@section('header','Tiendas')
@section('title', 'Tiendas')
@section('content_header_title', 'Tiendas')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-end align-items-center">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addShopModal">
            Agregar tienda
        </button>
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

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>URL</th>
                    <th>Origen</th>
                    <th>Creada</th>
                </tr>
            </thead>
            <tbody>
                @isset($shops)
                @forelse($shops as $shop)
                <tr>
                    <td>{{ $shop->id }}</td>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->url }}</td>
                    <td>{{ ucfirst($shop->platform) }}</td>
                    <td>{{ $shop->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">
                        No hay tiendas
                    </td>
                </tr>
                @endforelse
                @endisset
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addShopModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('shop.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Agregar tienda</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre</label>
                    <input name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Dominio / URL</label>
                    <input name="url" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Servicio E-Commerce</label>
                    <select name="platform" class="form-control" required>
                        <option value="shopify">Shopify</option>
                        <option value="woocommerce">WooCommerce</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>API Key (si aplica)</label>
                    <input name="api_key" class="form-control">
                </div>
                <div class="form-group">
                    <label>API Secret (si aplica)</label>
                    <input name="api_secret" class="form-control">
                </div>
                <div class="form-group">
                    <label>Token (si aplica)</label>
                    <input name="access_token" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </form>
    </div>
</div>
@endsection