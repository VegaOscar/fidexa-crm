@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Historial de Interacciones</h1>
            <a href="{{ url('/interacciones/crear') }}"
               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Registrar Interacción
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($interacciones as $interaccion)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $interaccion->cliente->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $interaccion->tipo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $interaccion->descripcion }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $interaccion->fecha }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ url('/interacciones/'.$interaccion->id.'/editar') }}"
                                   class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                <form action="{{ url('/interacciones/'.$interaccion->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('¿Seguro que quieres eliminar esta interacción?')"
                                            class="text-red-600 hover:text-red-900">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
