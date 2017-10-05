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

    Route::group(['prefix' => 'compras', 'as' => 'compras.', 'middleware' => ['auth','share']], function(){
        Route::get('solicitudes/{id}/impress', 'Compras\SolicitudesController@impress')->name('solicitudes');
        Route::resource('solicitudes', 'Compras\SolicitudesController');
        Route::resource('solicitudes_detalles', 'Compras\DetalleSolicitudesController');
        Route::resource('ordenes','Compras\OrdenesController');
    });
});
