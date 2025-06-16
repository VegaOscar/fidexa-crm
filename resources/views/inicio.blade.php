@extends('layouts.app')

@section('content')
<div class="dashboard-container">

    {{-- Logo y bienvenida --}}
    <div class="dashboard-header">
        <h1>Bienvenido a Fidexa CRM</h1>
        <p class="subtitulo">Visualiza métricas claves para gestionar la fidelización de tus clientes.</p>
    </div>

    {{-- Métricas principales --}}
    <div class="metricas-grid">
        <div class="metric-card"><i class="fas fa-users metric-icon"></i><p>Total de Clientes</p><h3>{{ $totalClientes }}</h3></div>
        <div class="metric-card"><i class="fas fa-shopping-cart metric-icon"></i><p>Total de Compras</p><h3>{{ $totalCompras }}</h3></div>
        <div class="metric-card"><i class="fas fa-dollar-sign metric-icon"></i><p>Monto Total Gastado</p><h3>Bs. {{ number_format($montoTotal, 2, ',', '.') }}</h3></div>
        <div class="metric-card"><i class="fas fa-coins metric-icon"></i><p>Promedio por Compra</p><h3>Bs. {{ number_format($promedioGasto, 2, ',', '.') }}</h3></div>
    </div>

    {{-- KPIs Visuales y Gráficos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-10">
        {{-- Gráfico de evolución mensual --}}
        <div class="bg-white rounded-lg p-4 shadow">
            <h3 class="text-lg font-semibold mb-2">📈 Evolución Mensual de Compras</h3>
            <canvas id="graficoCompras" class="w-full" style="max-height: 200px;"></canvas>
        </div>

        {{-- Distribución de Interacciones --}}
        <div class="bg-white rounded-lg p-4 shadow">
            <h3 class="text-lg font-semibold mb-2">💬 Interacciones por Tipo</h3>
            <canvas id="graficoInteracciones" class="w-full" style="max-height: 200px;"></canvas>
        </div>

        {{-- Clientes más rentables --}}
        <div class="bg-white rounded-lg p-4 shadow w-full">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                🏆 <span class="ml-2">Clientes Más Rentables</span>
            </h3>
            <div class="space-y-3">
                @php $max = $clientesRentables->max('monto') ?? 1; @endphp
                @foreach($clientesRentables as $cliente)
                    @php
                        $porcentaje = ($max > 0) ? round(($cliente->monto / $max) * 100, 2) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                            <span>{{ $cliente->nombre }}</span>
                            <span>Bs. {{ number_format($cliente->monto, 2, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-200 h-3 rounded">
                            <div class="bg-green-500 h-3 rounded" style="width: {{ $porcentaje }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>





        {{-- Historial de comportamiento --}}
        <div class="bg-white mt-8 p-4 rounded-lg shadow w-full">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="far fa-clock mr-2 text-purple-600"></i> Historial de Comportamiento
            </h3>
            <div class="flex items-center justify-between overflow-x-auto px-2">
                @foreach($historial as $evento)
                    @php
                        $icon = match(strtolower($evento->tipo)) {
                            'compra' => 'fa-trophy text-orange-500',
                            'reclamo' => 'fa-exclamation-circle text-red-500',
                            'sugerencia' => 'fa-comment-dots text-teal-500',
                            'consulta' => 'fa-question-circle text-yellow-500',
                            default => 'fa-dot-circle text-gray-400',
                        };
                    @endphp
                    <div class="flex flex-col items-center text-xs text-center mx-2">
                        <span class="text-gray-500">{{ \Carbon\Carbon::createFromFormat('d/m/Y', $evento->fecha)->format('M.') }}</span>
                        <i class="fas {{ $icon }} text-xl my-1"></i>
                        <span class="font-medium text-gray-700">{{ ucfirst($evento->tipo) }}</span>
                    </div>
                @endforeach
            </div>
        </div>




</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const comprasData = {
            labels: {!! json_encode($labelsMeses) !!},
            datasets: [{
                label: 'Monto mensual',
                data: {!! json_encode($valoresMeses->map(fn($v) => (float) $v)) !!},
                backgroundColor: 'rgba(34,197,94,0.2)',
                borderColor: 'rgba(34,197,94,1)',
                borderWidth: 2,
                tension: 0.3
            }]
        };

        const interaccionesData = {
            labels: {!! json_encode(array_keys($interaccionesPorTipo->toArray())) !!},
            datasets: [{
                data: {!! json_encode(array_values($interaccionesPorTipo->toArray())) !!},
                backgroundColor: ['#4ade80', '#60a5fa', '#facc15', '#f87171']
            }]
        };

        new Chart(document.getElementById('graficoCompras'), {
            type: 'line',
            data: comprasData,
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        new Chart(document.getElementById('graficoInteracciones'), {
            type: 'doughnut',
            data: interaccionesData,
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    });

    </script>


@endpush


