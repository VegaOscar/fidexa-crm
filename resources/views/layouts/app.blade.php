<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset('img/hexagon-nodes-solid.svg') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <!-- Styles -->
        @livewireStyles
        @stack('scripts')
    </head>
        <body x-data="{ sidebarOpen: true }" class="font-sans antialiased">


        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

        <div class="relative min-h-screen flex">

<!-- SIDEBAR -->
    <div :class="sidebarOpen ? 'w-64' : 'w-16'" 
        class="bg-white border-r shadow-md fixed top-16 left-0 h-full overflow-hidden transition-all duration-300 z-30">


        <div class="flex flex-col  mt-8 space-y-2">
            <a href="{{ url('/') }}"
            class="flex items-center px-4 py-2 hover:bg-gray-200 transition"
            :class="sidebarOpen ? 'justify-start space-x-2' : 'justify-center'">
                <i class="fas fa-user text-gray-700"></i>
                <span x-show="sidebarOpen" class="text-sm">Inicio</span>
            </a>
            <a href="{{ url('/clientes') }}"
            class="flex items-center px-4 py-2 hover:bg-gray-200 transition"
            :class="sidebarOpen ? 'justify-start space-x-2' : 'justify-center'">
                <i class="fas fa-user text-gray-700"></i>
                <span x-show="sidebarOpen" class="text-sm">Clientes</span>
            </a>
            <a href="{{ url('/interacciones') }}"
            class="flex items-center px-4 py-2 hover:bg-gray-200 transition"
            :class="sidebarOpen ? 'justify-start space-x-2' : 'justify-center'">
                <i class="fas fa-comments text-gray-700"></i>
                <span x-show="sidebarOpen" class="text-sm">interacciones</span>
            </a>
            <a href="{{ url('/reportes') }}"
            class="flex items-center px-4 py-2 hover:bg-gray-200 transition"
            :class="sidebarOpen ? 'justify-start space-x-2' : 'justify-center'">
                <i class="fas fa-chart-line text-gray-700"></i>
                <span x-show="sidebarOpen" class="text-sm">reportes</span>
            </a>            
            <a href="{{ url('/alertas/inactivos') }}"
            class="flex items-center px-4 py-2 hover:bg-gray-200 transition"
            :class="sidebarOpen ? 'justify-start space-x-2' : 'justify-center'">
                <i class="fas fa-user-slash text-gray-700"></i>
                <span x-show="sidebarOpen" class="text-sm">Clientes Inactivos</span>
            </a>
            <a href="{{ url('/bonificaciones/otorgar') }}"
            class="flex items-center px-4 py-2 hover:bg-gray-200 transition"
            :class="sidebarOpen ? 'justify-start space-x-2' : 'justify-center'">
                <i class="fas fa-gift text-gray-700"></i>
                <span x-show="sidebarOpen" class="text-sm">Bonificaciones</span>
            </a>                         
                
            
        </div>
    </div>



        <!-- Content -->
    <div class="flex-1 transition-all duration-300" :class="{ 'ml-64': sidebarOpen, 'ml-16': !sidebarOpen }">
</aside>
            <!-- Aquí empieza el contenido de tu app -->

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
    </div> <!-- Fin del contenedor de contenido -->
</div> <!-- Fin de x-data -->

        @stack('modals')

<script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Confirmación antes de eliminar
    Livewire.on('confirmDelete', (id) => {
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡El usuario será eliminado permanentemente!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteConfirmed', id)
            }
        });
    });

    // Alerta después de eliminar exitosamente
    Livewire.on('usuarioEliminado', () => {
        Swal.fire({
            title: 'Eliminado',
            text: 'El usuario fue eliminado correctamente.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    });
</script>

<script>
    Livewire.on('toast', message => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2500
        });
    });
</script>


    </body>
</html>
