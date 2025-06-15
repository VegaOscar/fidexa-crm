<!-- resources/views/canjes/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Canjear Puntos de {{ $cliente->nombre }}</h2>

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ url('canjes/' . $cliente->id) }}">
        @csrf
        <p>Puntos disponibles: <strong>{{ $cliente->puntos }}</strong></p>
        <label for="puntos_canjeados">Puntos a canjear:</label>
        <input type="number" name="puntos_canjeados" min="1" max="{{ $cliente->puntos }}" required>
        <button type="submit">Canjear</button>
    </form>
</div>
@endsection
