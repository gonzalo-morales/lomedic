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
    Route::group(['prefix' => 'handheld', 'as' => 'handheld.', 'middleware' => ['auth','share','csrf','password_expired'] ], function() {
		Route::post('inventarios/{inventario}/detalle', 'HandheldController@inventario_detalle')->name('inventarios-inventario-detalle');
		Route::get('inventarios/{inventario}', 'HandheldController@inventario')->name('inventarios-inventario');
		Route::get('inventarios', 'HandheldController@inventarios')->name('inventarios');
		Route::post('inventarios', 'HandheldController@inventario_detalle_store')->name('inventarios-inventario-detalle-store');
	});
});