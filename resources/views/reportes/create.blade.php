@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Generar Reporte Personalizado</h1>

        <form method="POST" action="{{ route('reportes.generar') }}" class="space-y-6 bg-white p-6 rounded-lg shadow">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="desde" class="block text-sm font-medium text-gray-700">Desde</label>
                    <input type="date" id="desde" name="desde" value="{{ old('desde', $filtros['desde'] ?? '') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>
                <div>
                    <label for="hasta" class="block text-sm font-medium text-gray-700">Hasta</label>
                    <input type="date" id="hasta" name="hasta" value="{{ old('hasta', $filtros['hasta'] ?? '') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Métricas a incluir</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="metricas[]" value="total_compras" class="text-green-600"
                            {{ in_array('total_compras', $metricasSeleccionadas ?? []) ? 'checked' : '' }}>
                        <span class="ml-2">Total de Compras</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="metricas[]" value="monto_total" class="text-green-600"
                            {{ in_array('monto_total', $metricasSeleccionadas ?? []) ? 'checked' : '' }}>
                        <span class="ml-2">Monto Total Gastado</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="metricas[]" value="total_puntos" class="text-green-600"
                            {{ in_array('total_puntos', $metricasSeleccionadas ?? []) ? 'checked' : '' }}>
                        <span class="ml-2">Total de Puntos</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Generar Reporte</button>
                <a href="{{ route('reportes.crear') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">Limpiar</a>
            </div>
        </form>

        @isset($resultados)
            <div class="mt-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Resultados</h2>
                    <ul class="space-y-2">
                        @if(in_array('total_compras', $metricasSeleccionadas))
                            <li class="flex justify-between">
                                <span>Total de Compras:</span>
                                <span class="font-semibold">{{ $resultados['total_compras'] }}</span>
                            </li>
                        @endif
                        @if(in_array('monto_total', $metricasSeleccionadas))
                            <li class="flex justify-between">
                                <span>Monto Total Gastado:</span>
                                <span class="font-semibold">Bs. {{ number_format($resultados['monto_total'], 2, ',', '.') }}</span>
                            </li>
                        @endif
                        @if(in_array('total_puntos', $metricasSeleccionadas))
                            <li class="flex justify-between">
                                <span>Total de Puntos:</span>
                                <span class="font-semibold text-green-600">{{ $resultados['total_puntos'] }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endisset
    </div>
</div>
@endsection