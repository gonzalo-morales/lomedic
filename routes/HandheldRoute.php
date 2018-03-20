<?php
/*
|--------------------------------------------------------------------------
| Handheld Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'handheld', 'as' => 'handheld.', 'middleware' => ['auth','share','csrf','password_expired'] ], function() {
        Route::view("/","handheld.home");
        
		Route::post('inventarios/{inventario}/detalle', 'HandheldController@inventario_detalle')->name('inventarios-inventario-detalle');
		Route::get('inventarios/{inventario}', 'HandheldController@inventario')->name('inventarios-inventario');
		Route::get('inventarios', 'HandheldController@inventarios')->name('inventarios');
		Route::post('inventarios', 'HandheldController@inventario_detalle_store')->name('inventarios-inventario-detalle-store');
		/*Nando's solicitudes*/
		Route::get('solicitudes', 'HandheldController@solicitudes')->name('solicitudes');
		Route::get('solicitudes/{solicitud}', 'HandheldController@solicitud')->name('solicitudes-solicitud');
		Route::post('solicitudes', 'HandheldController@solicitud_detalle_store')->name('solicitudes-solicitud-detalle-store');
		/*Nando's sucursales*/
		Route::get('sucursales', 'HandheldController@sucursales')->name('sucursales');
		Route::get('almacenes', 'HandheldController@almacenes')->name('almacenes');
		Route::get('movimientos', 'HandheldController@movimientos')->name('movimientos');
		Route::get('movimientos/{movimiento}', 'HandheldController@movimiento')->name('movimiento');
		Route::post('stock-movimiento-detalle', 'HandheldController@stock_movimiento_detalle_store')->name('stock-movimiento-detalle-store');
		/*Nando's ordenes compra*/
		Route::get('ordenes', 'HandheldController@ordenes')->name('ordenes');
		Route::get('ordenes/{orden}', 'HandheldController@orden')->name('orden-compra');
		Route::post('entrada-detalle-store', 'HandheldController@entrada_detalle_store')->name('entrada-detalle-store');
	});
});