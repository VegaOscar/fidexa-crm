@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Historial de Compras de {{ $cliente->nombre }}</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ url('/clientes/' . $cliente->id . '/compras/crear') }}" class="btn btn-success mb-3">Registrar Nueva Compra</a>

    @if ($compras->isEmpty())
        <p>No hay compras registradas.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Sucursal</th>
                    <th>Tipo Movimiento</th>
                    <th>Referencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($compras as $compra)
                    <tr>
                        <td>{{ $compra->fecha }}</td>
                        <td>{{ $compra->monto }}</td>
                        <td>{{ $compra->sucursal }}</td>
                        <td>{{ $compra->tipo_movimiento }}</td>
                        <td>{{ $compra->documento_referencia }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
