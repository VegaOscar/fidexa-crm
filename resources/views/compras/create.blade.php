@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Compra para {{ $cliente->nombre }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/clientes/' . $cliente->id . '/compras') }}" method="POST">
        @csrf

        <!-- Fecha -->
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha de Compra</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>

        <!-- Monto -->
        <div class="mb-3">
            <label for="monto" class="form-label">Monto</label>
            <input type="number" name="monto" id="monto" class="form-control" step="0.01" required>
        </div>

        <!-- Sucursal -->
        <div class="mb-3">
            <label for="sucursal" class="form-label">Sucursal</label>
            <input type="text" name="sucursal" id="sucursal" class="form-control">
        </div>

        <!-- Tipo de Movimiento -->
        <div class="mb-3">
            <label for="tipo_movimiento" class="form-label">Tipo de Movimiento</label>
            <select name="tipo_movimiento" id="tipo_movimiento" class="form-control">
                <option value="compra">Compra</option>
                <option value="recarga">Recarga</option>
                <option value="abono">Abono</option>
            </select>
        </div>

        <!-- Documento de Referencia -->
        <div class="mb-3">
            <label for="documento_referencia" class="form-label">Documento de Referencia</label>
            <input type="text" name="documento_referencia" id="documento_referencia" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Compra</button>
        <a href="{{ url('/clientes/' . $cliente->id . '/compras') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
