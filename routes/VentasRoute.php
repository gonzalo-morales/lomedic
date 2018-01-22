<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'ventas', 'as' => 'ventas.', 'middleware' => ['auth','share','csrf','password_expired']], function() {
        Route::view("/","ventas.index");
        
        Route::resource('facturasclientes','Ventas\FacturasClientesController');
        Route::resource('notascreditoclientes','Ventas\NotasCreditoClientesController');
        Route::resource('notascargoclientes','Ventas\NotasCargoClientesController');
        Route::resource('pedidos','Ventas\PedidosController');
        Route::get('pedidos/{id}/descargaranexo', 'Ventas\PedidosController@descargaranexo');
        Route::get('LayoutProductosPedidos','Ventas\PedidosController@layoutProductos');
        Route::post('ImportarProductosPedido','Ventas\PedidosController@importarProductos');
    });
});