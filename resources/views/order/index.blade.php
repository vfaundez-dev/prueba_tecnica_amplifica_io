@extends('layouts.app')

@section('header','Pedidos')
@section('title', 'Pedidos')
@section('content_header_title', 'Pedidos')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-end align-items-center">
            <form action="{{ route('order.getAll') }}" method="POST" class="w-50">
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
                        <button class="btn btn-primary">
                            Consultar Pedidos
                        </button>
                    </div>
                    @if(isset($orders))
                        <a href="{{ route('export.orders') }}" class="btn btn-success ml-2">
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

            @if(isset($orders) && count($orders) > 0)
                @foreach ($orders as $order)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>Orden #{{ $order['order_number'] }}</strong><br>
                                    <small>ID: {{ $order['id'] }}</small>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-success">{{ ucfirst($order['payment_status']) }}</span><br>
                                    <small>{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Cliente --}}
                            <h6 class="font-weight-bold">Cliente</h6>
                            <p>
                                @if(isset($order['customer']))
                                    {{ $order['customer']['first_name'] ?? '' }} {{ $order['customer']['last_name'] ?? '' }} <br>
                                    <small>Email: {{ $order['customer']['email'] ?? 'Sin email' }}</small>
                                @else
                                    <em>Cliente no disponible</em>
                                @endif
                            </p>

                            {{-- Totales --}}
                            <p>
                                <strong>Total: </strong> ${{ number_format($order['total_price'], 0, ',', '.') }}
                                <span class="text-muted">({{ $order['currency'] }})</span>
                            </p>

                            {{-- Items --}}
                            <h6 class="font-weight-bold">Productos</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>SKU</th>
                                            <th>Producto</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-right">Precio</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order['items'] as $item)
                                            <tr>
                                                <td>{{ $item['sku'] }}</td>
                                                <td>{{ $item['title'] }}</td>
                                                <td class="text-center">{{ $item['quantity'] }}</td>
                                                <td class="text-right">${{ number_format($item['price'], 0, ',', '.') }}</td>
                                                <td class="text-right">
                                                    ${{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <h5 class="text-center">Selecciona una tienda para ver sus pedidos</h5>
            @endif
        </div>
    </div>
@endsection
