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

    Route::group(['prefix' => 'inventarios', 'as' => 'inventarios.', 'middleware' => ['auth','share'] ], function() {
        Route::resource('cbn','Inventarios\CbnController');
        Route::resource('productos', 'Inventarios\ProductosController');
        Route::get('getSkus','Inventarios\ProductosController@obtenerSkus');
        Route::get('getUpcs/{id}','Inventarios\ProductosController@obtenerUpcs');
        Route::resource('upcs','Inventarios\UpcsController');
        Route::resource('almacenes','Inventarios\AlmacenesController');
        Route::resource('entradas','Inventarios\EntradasController');
        Route::post('getProveedores','Inventarios\EntradasController@getProveedores')->name('entradas.getPorveedores');
        Route::post('getOrdenes','Inventarios\EntradasController@getOrdenes')->name('entradas.getOrdenes');
        Route::post('getDetalleEntrada','Inventarios\EntradasController@getDetalleEntrada')->name('entradas.getDetalleEntrada');
        Route::post('guardarEntrada','Inventarios\EntradasController@guardarEntrada')->name('entradas.guardarEntrada');
    });
});
