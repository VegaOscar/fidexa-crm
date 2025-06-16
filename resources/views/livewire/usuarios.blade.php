<div class="p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Gestión de Usuarios</h1>

    <!-- Botón para abrir modal -->
    <button type="button" wire:click="openModal" class="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        Nuevo Usuario
    </button>

    <!-- Mensaje -->
    @if (session()->has('message'))
        <div class="mb-4 text-green-600 font-semibold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabla -->
    <table class="min-w-full table-auto">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Fecha de Registro</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $usuario->name }}</td>
                    <td class="px-4 py-2">{{ $usuario->email }}</td>
                    <td class="px-4 py-2">{{ $usuario->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <button type="button" wire:click="edit({{ $usuario->id }})" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Editar
                        </button>
                        <button wire:click="confirmDelete({{ $usuario->id }})"
                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                            Eliminar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $isEditMode ? 'Editar Usuario' : 'Nuevo Usuario' }}
                </h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model.defer="name" class="w-full border rounded p-2">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model.defer="email" class="w-full border rounded p-2">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Contraseña {{ $isEditMode ? '(opcional)' : '' }}</label>
                    <input type="password" wire:model.defer="password" class="w-full border rounded p-2">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                        Cancelar
                    </button>
                    <button type="button" wire:click="store" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        {{ $isEditMode ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
