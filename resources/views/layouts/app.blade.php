<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fidexa CRM</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('styles')
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">

        {{-- 🟢 Sidebar izquierdo --}}
        <div class="sidebar p-3" style="width: 250px; background-color: #f4fdf9; box-shadow: 2px 0 5px rgba(0,0,0,0.1);">
            <div class="mb-4 text-center">
                <img src="{{ asset('images/fidexa-logo.png') }}" alt="Logo Fidexa" class="logo-fidexa">
            </div>

            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link active" href="{{ url('/') }}">🏠 Inicio</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link" href="{{ url('/clientes') }}">👥 Clientes</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link" href="{{ url('/interacciones') }}">💬 Interacciones</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link" href="{{ url('/compras') }}">🛒 Compras</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link" href="{{ url('/reportes') }}">📊 Reportes</a>
                </li>
            </ul>
        </div>

        {{-- 🔷 Contenido Principal --}}
        <div class="flex-grow-1 p-4 bg-light">
            @yield('content')
        </div>
    </div>

    {{-- 📌 Footer --}}
    <footer class="text-center p-3" style="background-color: #2ECC71; color: white;">
        &copy; 2025 Fidexa CRM - Todos los derechos reservados.
    </footer>

    @yield('scripts')
</body>
</html>
