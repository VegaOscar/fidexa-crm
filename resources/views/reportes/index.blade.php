@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard de Reportes</h1>

        <!-- Métricas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h3 class="text-gray-600 text-sm uppercase">Total de Clientes</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalClientes }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h3 class="text-gray-600 text-sm uppercase">Total de Interacciones</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalInteracciones }}</p>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-700 text-lg font-semibold mb-4">Distribución de Interacciones por Tipo</h3>
            <canvas id="graficoInteracciones" class="w-full" style="height: 300px;"></canvas>
        </div>

        <!-- KPIs por Cliente -->
        <h2 class="text-xl font-semibold mt-8 mb-4">Clientes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($clientesData as $data)
                <x-cliente-card :cliente="$data['cliente']"
                                :total-gastado="$data['totalGastado']"
                                :nivel="$data['nivel']"
                                :ultima-compra="$data['ultimaCompra']"
                                :labels="$data['labels']"
                                :data="$data['data']"
                                :interacciones-labels="$data['interaccionesLabels']"
                                :interacciones-data="$data['interaccionesData']" />
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('graficoInteracciones').getContext('2d');

    const dataLabels = {!! json_encode($interaccionesPorTipo->keys()) !!};
    const dataValues = {!! json_encode($interaccionesPorTipo->values()) !!};

    if (dataLabels.length > 0 && dataValues.length > 0) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataLabels,
                datasets: [{
                    label: 'Cantidad',
                    data: dataValues,
                    backgroundColor: 'rgba(34,197,94,0.7)',
                    borderColor: 'rgba(34,197,94,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
});
</script>
@endpush
