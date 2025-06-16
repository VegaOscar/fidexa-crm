@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Creador de Reportes</h1>

        <!-- Formulario del creador de reportes -->
        <form method="GET" action="{{ url('/reportes') }}" class="bg-white p-6 rounded-lg shadow mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="entidad" class="block text-sm font-medium text-gray-700">Entidad</label>
                    <select id="entidad" name="entidad" class="mt-1 block w-full border-gray-300 rounded-md">
                        <option value="clientes">Clientes</option>
                        <option value="compras">Compras</option>
                        <option value="interacciones">Interacciones</option>
                    </select>
                </div>
                <div>
                    <label for="desde" class="block text-sm font-medium text-gray-700">Desde</label>
                    <input type="date" id="desde" name="desde" class="mt-1 block w-full border-gray-300 rounded-md" />
                </div>
                <div>
                    <label for="hasta" class="block text-sm font-medium text-gray-700">Hasta</label>
                    <input type="date" id="hasta" name="hasta" class="mt-1 block w-full border-gray-300 rounded-md" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Campos</label>
                    <div class="mt-1 space-y-1">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="campos[]" value="nombre" class="mr-2">
                            Nombre
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="campos[]" value="email" class="mr-2">
                            Email
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="campos[]" value="monto" class="mr-2">
                            Monto
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="campos[]" value="puntos" class="mr-2">
                            Puntos
                        </label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Generar Reporte</button>
            </div>
        </form>

        @isset($resultados)
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Resultados</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach(array_keys($resultados[0] ?? []) as $col)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($resultados as $row)
                            <tr>
                                @foreach($row as $valor)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $valor }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endisset
    </div>
</div>
@endsection
