@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <h1 class="text-2xl font-semibold mb-4">Resumen de Clientes</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($clientesData as $data)
                <x-cliente-card :cliente="$data['cliente']"
                                :total-gastado="$data['totalGastado']"
                                :nivel="$data['nivel']"
                                :ultima-compra="$data['ultimaCompra']"
                                :labels="$data['labels']"
                                :data="$data['data']"
                                :interacciones-labels="$data['interaccionesLabels']"
                                :interacciones-data="$data['interaccionesData']" />
            @endforeach
        </div>
    </div>
</div>
@endsection
