<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InteraccionController;
use App\Http\Controllers\ReporteController;

Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/clientes/crear', [ClienteController::class, 'create']);
Route::post('/clientes', [ClienteController::class, 'store']);

Route::get('/interacciones', [InteraccionController::class, 'index']);
Route::get('/interacciones/crear', [InteraccionController::class, 'create']);
Route::post('/interacciones', [InteraccionController::class, 'store']);

Route::get('/reportes', [ReporteController::class, 'index']);

// ✅ Reemplazado correctamente
Route::get('/', [ReporteController::class, 'index']);

use App\Http\Controllers\CompraController;

Route::get('/clientes/{id}/compras', [CompraController::class, 'index']);
Route::get('/clientes/{id}/compras/crear', [CompraController::class, 'create']);
Route::post('/clientes/{id}/compras', [CompraController::class, 'store']);

Route::get('/reportes/cliente/{id}', [ReporteController::class, 'cliente'])->name('reportes.cliente');



use App\Http\Controllers\PuntoController;

Route::get('/clientes/{id}/canjear', [PuntoController::class, 'mostrarFormulario'])->name('puntos.formulario');
Route::post('/clientes/{id}/canjear', [PuntoController::class, 'canjear'])->name('puntos.canjear');

use App\Http\Controllers\CanjePuntosController;

Route::get('/clientes/{cliente}/canje', [CanjePuntosController::class, 'show'])->name('canje.index');
Route::post('/clientes/{cliente}/canje', [CanjePuntosController::class, 'canjear'])->name('canje.canjear');

// routes/web.php
use App\Http\Controllers\CanjeController;

// Ruta para mostrar el formulario de canje
Route::get('/canjes/{cliente}/crear', [CanjeController::class, 'create'])->name('canjes.create');

// Ruta para procesar el formulario
Route::post('/canjes/{cliente}', [CanjeController::class, 'store'])->name('canjes.store');


use App\Http\Controllers\AlertaController;

Route::get('/alertas/inactivos', [AlertaController::class, 'clientesInactivos'])->name('alertas.inactivos');


use App\Http\Controllers\BonificacionController;

Route::get('/bonificaciones/otorgar', [BonificacionController::class, 'otorgarBonificaciones'])->name('bonificaciones.otorgar');


Route::get('/', function () {
    return view('inicio');
});


use App\Http\Controllers\InicioController;
Route::get('/', [InicioController::class, 'index']);
