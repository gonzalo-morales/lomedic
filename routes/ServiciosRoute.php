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

    Route::get('servicios/{id}/vehiculos', 'Servicios\VehiculosController@getModelos');
    Route::group(['prefix' => 'servicios', 'as' => 'servicios.', 'middleware' => ['auth','share'] ], function() {
        Route::resource('vehiculos', 'Servicios\VehiculosController');
    });
});
