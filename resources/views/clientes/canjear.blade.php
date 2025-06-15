@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Canjear Puntos de {{ $cliente->nombre }}</h2>

    <p>Puntos disponibles: <strong>{{ $cliente->puntos }}</strong></p>

    <form method="POST" action="{{ route('puntos.canjear', $cliente->id) }}">
        @csrf
        <label>Cantidad a canjear:</label>
        <input type="number" name="puntos_a_canjear" min="1" max="{{ $cliente->puntos }}" required>
        <button type="submit">Canjear</button>
    </form>
</div>
@endsection
