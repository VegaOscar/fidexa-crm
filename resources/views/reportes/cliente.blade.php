@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Reporte de Cliente: {{ $cliente->nombre }}</h2>

    {{-- ✅ Formulario de filtros --}}
    <form method="GET" action="{{ route('reportes.cliente', $cliente->id) }}" class="mb-4">
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <div>
                <label>Desde:</label>
                <input type="date" name="desde" value="{{ request('desde') }}">
            </div>
            <div>
                <label>Hasta:</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}">
            </div>
            <div>
                <label>Sucursal:</label>
                <select name="sucursal">
                    <option value="">Todas</option>
                    @foreach ($comprasOriginales->pluck('sucursal')->unique() as $sucursal)
                        <option value="{{ $sucursal }}" {{ request('sucursal') == $sucursal ? 'selected' : '' }}>
                            {{ $sucursal }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit">Filtrar</button>
            </div>
        </div>
    </form>

    {{-- 📋 Información del cliente --}}
    <div class="mb-4">
        <p><strong>Total de Compras:</strong> {{ $totalCompras }}</p>
        <p><strong>Monto Total Gastado:</strong> Bs. {{ number_format($montoTotal, 2, ',', '.') }}</p>
        <p><strong>Puntos Acumulados:</strong> <span style="color: green; font-weight: bold;">{{ $totalPuntos }}</span></p>
            @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h5>Canjear Puntos</h5>
    <form method="POST" action="{{ route('canjes.store') }}" class="mb-4">
        @csrf
        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
        <div class="mb-3">
            <label>Puntos a canjear (máx. {{ $cliente->puntos }}):</label>
            <input type="number" name="puntos_canjeados" class="form-control" max="{{ $cliente->puntos }}" min="1" required>
        </div>
        <div class="mb-3">
            <label>Detalle:</label>
            <input type="text" name="detalle" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Canjear</button>
    </form>

        <p><strong>Última Compra:</strong> {{ $ultimaCompra ?? 'No disponible' }}</p>
        <p><strong>Frecuencia Promedio (días):</strong> {{ $frecuenciaPromedio ?? 'N/A' }}</p>
        <p><strong>Sucursal Más Frecuente:</strong> {{ $sucursalMasFrecuente ?? 'No definida' }}</p>
    </div>

    {{-- 📊 Gráfico de compras mensuales --}}
    <div class="mt-4">
        <h5>Gráfico de Compras por Mes</h5>
        <canvas id="comprasChart" width="600" height="300"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById('comprasChart');

    if (!canvas) {
        console.error('No se encontró el canvas');
        return;
    }

    const labels = {!! json_encode(array_values($labels->toArray())) !!};
    const data = {!! json_encode(array_values($data->toArray())) !!};

    if (!labels.length || !data.length) {
        console.warn('Sin datos para mostrar el gráfico');
        return;
    }

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Monto de compras por mes',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<h5>Historial de Canjes</h5>
@if($cliente->canjes->isEmpty())
    <p>No hay canjes registrados.</p>
@else
    <ul class="list-group mb-4">
        @foreach($cliente->canjes as $canje)
            <li class="list-group-item">
                {{ $canje->fecha }} – {{ $canje->detalle }} – <strong>{{ $canje->puntos_canjeados }} pts</strong>
            </li>
        @endforeach
    </ul>
@endif

@endsection
