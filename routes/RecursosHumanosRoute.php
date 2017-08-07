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
    Route::group(['prefix' => 'recursos_humanos', 'as' => 'recursos_humanos.', 'middleware' => ['auth','share']], function(){
        Route::resource('empleados', 'RecursosHumanos\EmpleadosController');
        Route::resource('puestos', 'RecursosHumanos\PuestosController');
        Route::resource('departamentos', 'RecursosHumanos\DepartamentosController');
        Route::resource('causasbajas', 'RecursosHumanos\CausasBajasController');
    });
});
