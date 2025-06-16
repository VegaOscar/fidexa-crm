<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InteraccionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\PuntoController;
use App\Http\Controllers\CanjePuntosController;
use App\Http\Controllers\CanjeController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\BonificacionController;
use App\Http\Controllers\InicioController;
use App\Http\Livewire\Usuarios;
use App\Http\Controllers\ClienteImportController;

/*
|--------------------------------------------------------------------------
| Rutas públicas o de prueba
|--------------------------------------------------------------------------
*/

Route::get('/', [InicioController::class, 'index']);


/*
|--------------------------------------------------------------------------
| Rutas protegidas por autenticación
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    // Módulo de Clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/crear', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}/editar', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::get('/api/clientes/{id}', function($id) 
    {
    return App\Models\Cliente::findOrFail($id);
    });
    Route::post('/clientes/importar', [ClienteController::class, 'importar'])->name('clientes.importar');




    // Compras por cliente
    Route::get('/clientes/{id}/compras', [CompraController::class, 'index']);
    Route::get('/clientes/{id}/compras/crear', [CompraController::class, 'create']);
    Route::post('/clientes/{id}/compras', [CompraController::class, 'store']);

    // Interacciones
    Route::get('/interacciones', [InteraccionController::class, 'index']);
    Route::get('/interacciones/crear', [InteraccionController::class, 'create']);
    Route::post('/interacciones', [InteraccionController::class, 'store']);

    // Canjes de puntos
    Route::get('/clientes/{id}/canjear', [CanjeController::class, 'create'])->name('canjes.create');
    Route::post('/clientes/{id}/canjear', [CanjeController::class, 'store'])->name('canjes.store');


    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index']);
    Route::get('/reportes/cliente/{id}', [ReporteController::class, 'cliente'])->name('reportes.cliente');

    // Alertas de inactividad
    Route::get('/alertas/inactivos', [AlertaController::class, 'clientesInactivos'])->name('alertas.inactivos');

    // Bonificaciones
    Route::get('/bonificaciones/otorgar', [BonificacionController::class, 'otorgarBonificaciones'])->name('bonificaciones.otorgar');

    // Livewire: Gestión de Usuarios
    Route::get('/usuarios', Usuarios::class)->name('usuarios');


});
