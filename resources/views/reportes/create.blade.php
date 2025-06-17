@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">Crear Reporte</h1>
        <form method="POST" action="{{ route('reportes.generate') }}" class="bg-white p-4 rounded shadow">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Desde</label>
                    <input type="date" name="desde" class="mt-1 w-full border-gray-300 rounded" value="{{ request('desde') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium">Hasta</label>
                    <input type="date" name="hasta" class="mt-1 w-full border-gray-300 rounded" value="{{ request('hasta') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium">Cliente</label>
                    <select name="cliente_id" class="mt-1 w-full border-gray-300 rounded">
                        <option value="">Todos</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Sucursal</label>
                    <select name="sucursal" class="mt-1 w-full border-gray-300 rounded">
                        <option value="">Todas</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal }}" {{ request('sucursal') == $sucursal ? 'selected' : '' }}>{{ $sucursal }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Tipo de interacción</label>
                    <select name="tipo_interaccion" class="mt-1 w-full border-gray-300 rounded">
                        <option value="">Todas</option>
                        @foreach($tiposInteraccion as $tipo)
                            <option value="{{ $tipo }}" {{ request('tipo_interaccion') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-sm font-medium mb-2">Métricas</h3>
                <label class="mr-4"><input type="checkbox" name="metricas[]" value="total_compras" checked> Total de compras</label>
                <label class="mr-4"><input type="checkbox" name="metricas[]" value="monto_total" checked> Monto total</label>
                <label class="mr-4"><input type="checkbox" name="metricas[]" value="puntos" checked> Puntos</label>
            </div>
            <div class="mt-4 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Generar</button>
                <button type="submit" name="exportar_excel" value="1" class="px-4 py-2 bg-green-600 text-white rounded">Exportar a Excel</button>
            </div>
        </form>

        @isset($resultado)
            <div class="mt-8 bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Vista previa</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div v-if="in_array('total_compras', request('metricas', []))" class="text-center">
                        <p class="text-gray-600">Total de Compras</p>
                        <p class="text-2xl font-bold">{{ $resultado['total_compras'] }}</p>
                    </div>
                    <div v-if="in_array('monto_total', request('metricas', []))" class="text-center">
                        <p class="text-gray-600">Monto Total</p>
                        <p class="text-2xl font-bold">{{ number_format($resultado['monto_total'],2,',','.') }}</p>
                    </div>
                    <div v-if="in_array('puntos', request('metricas', []))" class="text-center">
                        <p class="text-gray-600">Puntos</p>
                        <p class="text-2xl font-bold">{{ $resultado['puntos'] }}</p>
                    </div>
                </div>
                <div>
                    <canvas id="grafico" style="height:300px;"></canvas>
                </div>
            </div>
        @endisset
    </div>
</div>
@endsection

@push('scripts')
@if(isset($labels))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('grafico').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Monto por mes',
                data: @json($monto),
                backgroundColor: 'rgba(54,162,235,0.7)',
                borderColor: 'rgba(54,162,235,1)',
                borderWidth: 1
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
});
</script>
@endif
@endpush
