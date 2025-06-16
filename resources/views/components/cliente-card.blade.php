<div class="bg-white shadow-md rounded-lg p-4">
    <h3 class="text-lg font-semibold mb-2">{{ $cliente->nombre }}</h3>
    <ul class="text-sm space-y-1 mb-4">
        <li><strong>Total gastado:</strong> ${{ number_format($totalGastado, 2) }}</li>
        <li><strong>Puntos:</strong> {{ $cliente->puntos }}</li>
        <li><strong>Nivel:</strong> {{ $nivel }}</li>
        <li><strong>Última compra:</strong> {{ $ultimaCompra ? \Carbon\Carbon::parse($ultimaCompra)->format('d/m/Y') : 'N/A' }}</li>
    </ul>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <canvas id="compras-chart-{{ $cliente->id }}" height="150"></canvas>
        <canvas id="interacciones-chart-{{ $cliente->id }}" height="150"></canvas>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx{{ $cliente->id }} = document.getElementById('compras-chart-{{ $cliente->id }}');
            new Chart(ctx{{ $cliente->id }}, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Compras',
                        data: @json($data),
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: { scales: { y: { beginAtZero: true } } }
            });

            const ictx{{ $cliente->id }} = document.getElementById('interacciones-chart-{{ $cliente->id }}');
            new Chart(ictx{{ $cliente->id }}, {
                type: 'pie',
                data: {
                    labels: @json($interaccionesLabels),
                    datasets: [{
                        data: @json($interaccionesData),
                        backgroundColor: ['#f87171','#60a5fa','#34d399','#fbbf24','#a78bfa']
                    }]
                }
            });
        });
    </script>
</div>
