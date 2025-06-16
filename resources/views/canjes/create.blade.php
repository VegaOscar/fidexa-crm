@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-semibold text-green-700 mb-4">🎁 Canje de Puntos</h2>

    <div class="bg-gray-100 p-4 rounded mb-6">
        <p class="text-gray-700 mb-1"><strong>Cliente:</strong> {{ $cliente->nombre }}</p>
        <p class="text-gray-700"><strong>Puntos Disponibles:</strong> {{ $cliente->puntos }}</p>
    </div>

    <form action="{{ route('canjes.store', $cliente->id) }}" method="POST">

        @csrf

        <div>
            <label for="puntos_canjeados" class="block text-sm font-medium text-gray-700">Puntos a Canjear:</label>
            <input type="number" name="puntos_canjeados" id="puntos_canjeados" min="1" max="{{ $cliente->puntos }}"
                class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                required>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">
                Canjear
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#16a34a'
            });
        @elseif(session('error'))
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#dc2626'
            });
        @endif
    });
</script>
@endpush

@endpush
