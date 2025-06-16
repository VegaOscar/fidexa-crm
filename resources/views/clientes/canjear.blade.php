@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4 text-center text-green-700">🎁 Canje de Puntos</h2>

    <div class="mb-6 text-center">
        <p class="text-gray-600">Cliente:</p>
        <h3 class="text-xl font-bold text-gray-800">{{ $cliente->nombre }}</h3>
        <p class="mt-2 text-sm text-gray-500">Puntos disponibles:</p>
        <span class="inline-block mt-1 px-3 py-1 text-green-700 bg-green-100 rounded-full font-semibold text-lg">
            {{ $cliente->puntos }} pts
        </span>

    </div>

    @if(session('error'))
        <div class="mb-4 text-red-600 bg-red-100 p-3 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('puntos.canjear', $cliente->id) }}" method="POST" class="space-y-4">
        @csrf
        <label for="puntos_canjeados" class="block text-gray-700 font-medium">Cantidad a canjear</label>
        <input type="number" name="puntos_canjeados" id="puntos_canjeados"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400"
            min="1" max="{{ $cliente->puntos }}" required>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded transition duration-200">
            Canjear Puntos
        </button>
    </form>
</div>
@endsection
