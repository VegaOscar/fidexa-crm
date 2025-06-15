@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Clientes Bonificados</h2>

    @if (count($bonificados) > 0)
        <ul>
            @foreach ($bonificados as $cliente)
                <li>{{ $cliente }} ha recibido 10 puntos por su frecuencia de compra.</li>
            @endforeach
        </ul>
    @else
        <p>No se otorgaron bonificaciones. Ningún cliente cumple con la frecuencia requerida.</p>
    @endif
</div>
@endsection
