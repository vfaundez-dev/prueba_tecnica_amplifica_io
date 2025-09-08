@extends('layouts.app')

@section('header')
    <strong>{{ Auth::user()->name ?? 'Usuario' }}!</strong> Bienvenido al sistema.
@endsection
@section('title', 'Dashboard')
@section('content_header_title', 'Dashboard')

@section('plugins.Chartjs', true)

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-end align-items-center">
            <form action="{{ route('dashboard.metrics') }}" method="POST" class="w-50">
                @csrf
                <div class="input-group mb-3">
                    <select name="shop" class="custom-select form-control-border">
                        <option value="">-- Selecciona una tienda --</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ session('shop_id') == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary">
                            Ver Métricas
                        </button>
                    </div>
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

            {{-- Widgets --}}
            <div class="row">

                <!-- Total Ventas -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>
                                {{
                                    isset($metrics['salesByCurrentMonth'])
                                        ? "$" . number_format($metrics['salesByCurrentMonth'], 0, ',', '.')
                                        : "-"
                                }}
                            </h3>
                            <p>Total Ventas ({{ date('Y-m') }})</p>
                        </div>
                        <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>

                <!-- Pedidos Pendientes -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ isset($metrics['pendingOrders']) ? $metrics['pendingOrders'] : "-" }}</h3>
                            <p>Pedidos Pendientes</p>
                        </div>
                        <div class="icon"><i class="fas fa-clock"></i></div>
                    </div>
                </div>

                <!-- Producto más vendido -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            @if( isset($metrics['topProduct']) && !empty($metrics['topProduct']) )
                                <div class="d-flex flex-column align-items-start mt-1">
                                    <span class="w-100 pb-0 mb-0 font-weight-bold">
                                        {{ $metrics['topProduct']['title'] }}
                                    </span>
                                    <span class="pt-0 mt-0 font-weight-bold">
                                        {{ $metrics['topProduct']['quantity'] }} unidades
                                    </span>
                                </div>
                            @else
                                <h3>-</h3>
                            @endif
                            <p>Producto más Vendido</p>
                        </div>
                        <div class="icon"><i class="fas fa-box"></i></div>
                    </div>
                </div>

            </div>
            {{-- Widgets --}}

            <div class="row">
                {{-- Tabla Productos mas Vendidos --}}
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Top 5 Productos Más Vendidos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: block;">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if( isset($metrics['topProducts']) && !empty($metrics['topProducts']) )
                                        @foreach($metrics['topProducts'] as $index => $product)
                                            <tr>
                                                <td>{{ $product['title'] }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary text-md">{{ $product['quantity'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center">No hay datos disponibles</td>
                                        </tr>
                                    @endif
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Tabla Productos mas Vendidos --}}

                {{-- Grafica Ventas Ultimos 6 Meses --}}
                <div class="col-md-6">
                    <div class="card card-purple">
                        <div class="card-header">
                            <h3 class="card-title">Ventas por Mes</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: block; min-height: 110px;">
                            @if ( isset($metrics) && isset($metrics['salesByMonth']) )
                                <canvas id="salesChart" style="min-height: 100px;"></canvas>
                            @else
                                <p class="text-center">No hay datos disponibles</p>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Grafica Ventas Ultimos 6 Meses --}}
            </div>

        </div>
        
    </div>
@endsection


@section('js')
    @if( isset($metrics) && isset($metrics['salesByMonth']) )
        <script>
            const ctx = document.getElementById('salesChart').getContext('2d');

            // Obtener los datos de ventas
            const salesData = {!! json_encode(array_values($metrics['salesByMonth'])) !!};
            const months = {!! json_encode(array_keys($metrics['salesByMonth'])) !!};

            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Ventas ($)',
                        data: salesData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    }
                },
            });
        </script>
    @endif
@endsection