<!DOCTYPE html>
<html>
<head>
    <title>Registrar Cliente</title>
</head>
<body>
    <h2>Formulario de Registro</h2>
        <form method="POST" action="{{ url('/clientes') }}">
        @csrf

        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label>Cédula:</label>
        <input type="text" name="cedula" required><br>

        <label>Género:</label>
        <select name="genero" required>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
            <option value="Otro">Otro</option>
        </select><br>

        <label>Email:</label>
        <input type="email" name="email"><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono"><br>

        <label>Dirección:</label>
        <textarea name="direccion"></textarea><br>

        <button type="submit">Guardar</button>
    </form>

</body>
</html>
