<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Reportes - Fidexa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4 text-center">📊 Reportes y Métricas - Fidexa CRM</h2>

    <!-- Módulo de métricas numéricas -->
    <div class="row text-center mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h5>Total de Clientes</h5>
                <h2 class="text-primary">{{ $totalClientes }}</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h5>Total de Interacciones</h5>
                <h2 class="text-success">{{ $totalInteracciones }}</h2>
            </div>
        </div>
    </div>

    <!-- Gráfico de Interacciones por Tipo -->
    <div class="card p-4">
        <h5 class="mb-3">Distribución de Interacciones por Tipo</h5>
        <canvas id="graficoInteracciones"></canvas>
    </div>
</div>

<!-- Script del gráfico -->
<script>
    const ctx = document.getElementById('graficoInteracciones').getContext('2d');
    const grafico = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($interaccionesPorTipo->keys()) !!},
            datasets: [{
                label: 'Cantidad',
                data: {!! json_encode($interaccionesPorTipo->values()) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
</body>
</html>
