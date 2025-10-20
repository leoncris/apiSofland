<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ExistenciaBodegaContoller;
use App\Http\Controllers\ExistenciaSerieContoller;
use App\Http\Controllers\MastersContoller;
use App\Models\ExistenciaSerieModel;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//Route::get('/clientes', function () { return 'Clientes List';});



 


Route::post('login', [AuthController::class, 'login']);

//Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
///consulta de la lista de productos

Route::group(['middleware' => 'auth:api'], function () {
    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    
    //existencias de articulo con precios y cantidades
    Route::get('/existencia/{vend}/{art}',[ExistenciaBodegaContoller::class, 'getexistencias'] );
    // Existencia Serie 
    Route::get('/existenciaserie/{vend}/{art}/{serie}',[ExistenciaSerieContoller::class, 'getexistenciaserie'] );
    Route::get('/existenciaserie/{vend}/{art}',[ExistenciaSerieContoller::class, 'getexistenciaserietotal'] );

    /////////Rutas para tabla Master
    Route::get('/empresas',[MastersContoller::class, 'getempresas'] );
    Route::get('/vendedores',[MastersContoller::class, 'getvendedores'] );
    Route::get('/bodegas',[MastersContoller::class, 'getbodegas'] );
    Route::get('/usuarios',[MastersContoller::class, 'getUsuarios'] );
    Route::get('/nivelprecio',[MastersContoller::class, 'getNivelprecios'] );
    
    /////////Rutas clientes
    Route::get('/clientes/{vend}',[ClienteController::class, 'getClientes'] );
    Route::get('/cliente/{vend}/{cliente}',[ClienteController::class, 'getCliente'] );
    Route::post('/scliente',[ClienteController::class, 'psStore'] );

});