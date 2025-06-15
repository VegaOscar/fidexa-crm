@extends('layouts.app')

@section('content')
<div class="dashboard container-fluid">
    {{-- 🔵 Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">¡Hola, Bienvenido a Fidexa CRM!</h2>
        <span class="text-muted">Lunes, {{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
    </div>

    {{-- 🔹 Tarjetas de KPIs --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card kpi-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <p class="fs-3 fw-bold">{{ $totalClientes ?? '0' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Interacciones</h5>
                    <p class="fs-3 fw-bold">{{ $totalInteracciones ?? '0' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Compras</h5>
                    <p class="fs-3 fw-bold">{{ $totalCompras ?? '0' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Canjes</h5>
                    <p class="fs-3 fw-bold">{{ $totalCanjes ?? '0' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 📊 Gráfico --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Actividad de Compras</h5>
            <canvas id="comprasChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('comprasChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels ?? []),
            datasets: [{
                label: 'Monto de Compras',
                data: @json($data ?? []),
                borderColor: 'rgba(46, 204, 113, 1)',
                backgroundColor: 'rgba(46, 204, 113, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
