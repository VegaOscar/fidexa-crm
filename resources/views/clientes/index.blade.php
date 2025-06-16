{{-- resources/views/clientes/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Listado de Clientes</h1>
            <a href="{{ url('/clientes/crear') }}"
               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Registrar Cliente
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Género</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($clientes as $cliente)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->cedula }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->genero }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->telefono }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->direccion }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ url('/clientes/'.$cliente->id.'/compras') }}"
                                   class="text-blue-600 hover:text-blue-900">Compras</a>

                                <a href="{{ url('/clientes/'.$cliente->id.'/canjear') }}"
                                   class="text-indigo-600 hover:text-indigo-900">Canjear</a>

                                <button onclick="abrirModalEditar({{ $cliente->id }})"
                                        class="text-yellow-600 hover:text-yellow-900">
                                    Editar
                                </button>

                                <button onclick="confirmarEliminacion({{ $cliente->id }})"
                                        class="text-red-600 hover:text-red-900">
                                    Eliminar
                                </button>

                                {{-- formulario oculto para eliminar --}}
                                <form id="form-eliminar-{{ $cliente->id }}"
                                      action="{{ url('/clientes/'.$cliente->id) }}"
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal de edición --}}
<div id="modal-editar" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden">
    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Editar Cliente</h2>
        <form id="form-editar" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="cliente_id" id="cliente_id">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="mt-1 block w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label for="cedula" class="block text-sm font-medium text-gray-700">Cédula</label>
                    <input type="text" id="cedula" name="cedula" class="mt-1 block w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                    <select id="genero" name="genero" class="mt-1 block w-full border rounded px-3 py-2">
                        <option value="M">M</option>
                        <option value="F">F</option>
                    </select>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" class="mt-1 block w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" id="direccion" name="direccion" class="mt-1 block w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button type="button" onclick="cerrarModalEditar()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- Scripts necesarios --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert2 confirm delete
    function confirmarEliminacion(clienteId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción eliminará al cliente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-eliminar-' + clienteId).submit();
            }
        });
    }

    // Abrir modal y cargar datos
    function abrirModalEditar(id) {
        fetch(`/api/clientes/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('cliente_id').value = id;
                document.getElementById('nombre').value    = data.nombre;
                document.getElementById('cedula').value    = data.cedula;
                document.getElementById('genero').value    = data.genero;
                document.getElementById('email').value     = data.email;
                document.getElementById('telefono').value  = data.telefono;
                document.getElementById('direccion').value = data.direccion;

                document.getElementById('form-editar').action = `/clientes/${id}`;
                document.getElementById('modal-editar').classList.remove('hidden');
            });
    }
    function cerrarModalEditar() {
        document.getElementById('modal-editar').classList.add('hidden');
    }
</script>
@endpush
