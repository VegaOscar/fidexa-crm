@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Clientes Inactivos</h2>
    <p>Clientes que no han realizado compras en los últimos 30 días.</p>

    @if($clientes->isEmpty())
        <div class="alert alert-info">No hay clientes inactivos.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Última Compra</th>
                    <th>Días de Inactividad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->email }}</td>
                        <td>{{ $cliente->ultima_compra }}</td>
                        <td>{{ $cliente->dias_inactivo }} días</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
