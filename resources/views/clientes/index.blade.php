<!DOCTYPE html>
<html>
<head>
    <title>Clientes Registrados</title>
</head>
<body>
    <h1>Listado de Clientes</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Género</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->cedula }}</td>
                    <td>{{ $cliente->genero }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>{{ $cliente->direccion }}</td>
                </tr>
            @endforeach
        </tbody>
        <a href="{{ route('canjes.create', $cliente->id) }}" class="btn btn-warning">
    Canjear Puntos
</a>

    </table>

    <br>
    <a href="/fidexa/public/clientes/crear">Registrar otro cliente</a>
</body>
</html>
