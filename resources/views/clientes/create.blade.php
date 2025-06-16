{{-- resources/views/clientes/crear.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-4">Registrar Cliente</h1>

            <form action="{{ url('/clientes') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" id="nombre" required
                            class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label for="cedula" class="block text-sm font-medium text-gray-700">Cédula</label>
                        <input type="text" name="cedula" id="cedula" required
                            class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                        <select name="genero" id="genero" required
                            class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Seleccione</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" id="telefono"
                            class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" name="direccion" id="direccion"
                            class="mt-1 block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <button type="button"
                        onclick="document.getElementById('modal-importar').classList.remove('hidden')"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Importar desde Excel
                </button>

                <div class="flex justify-end space-x-2">
                    <a href="{{ url('/clientes') }}"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</a>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Guardar</button>
                </div>


            </form>
        </div>
    </div>
</div>

{{-- Modal para importar clientes --}}
<div id="modal-importar" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">Importar Clientes desde Excel</h2>
        <form action="{{ route('clientes.importar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="archivo_excel" accept=".xlsx, .xls, .csv"
                class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:border file:border-gray-300 file:rounded file:bg-gray-100">
            <div class="mt-4 flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-importar').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Subir
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
