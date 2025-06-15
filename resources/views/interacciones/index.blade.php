<!-- resources/views/interacciones/index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Interacciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Interacciones con Clientes</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ url('/interacciones/crear') }}" class="btn btn-primary mb-3">Registrar Nueva Interacción</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($interacciones as $interaccion)
                <tr>
                    <td>{{ $interaccion->id }}</td>
                    <td>{{ $interaccion->cliente->nombre }}</td>
                    <td>{{ ucfirst($interaccion->tipo) }}</td>
                    <td>{{ $interaccion->descripcion }}</td>
                    <td>{{ \Carbon\Carbon::parse($interaccion->fecha)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $interacciones->links() }}
</div>

</body>
</html>
