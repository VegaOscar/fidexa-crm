@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Canje de Puntos - {{ $cliente->nombre }}</h3>
    <p>Puntos disponibles: <strong>{{ $cliente->puntos }}</strong></p>

    @if (session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('canje.canjear', $cliente->id) }}">
        @csrf
        <label>Seleccione recompensa:</label>
        <select name="recompensa_id" required>
            @foreach($recompensas as $recompensa)
                <option value="{{ $recompensa->id }}">
                    {{ $recompensa->nombre }} ({{ $recompensa->puntos_requeridos }} puntos)
                </option>
            @endforeach
        </select>
        <button type="submit">Canjear</button>
    </form>
</div>
@endsection
