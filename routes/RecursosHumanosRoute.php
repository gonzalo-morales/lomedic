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
    Route::group(['prefix' => 'recursoshumanos', 'as' => 'recursoshumanos.', 'middleware' => ['auth','share','csrf','password_expired']], function(){
        Route::view("/","recursoshumanos.index");
        Route::resource('causasbajas', 'RecursosHumanos\CausasBajasController');
        Route::resource('departamentos', 'RecursosHumanos\DepartamentosController');
        Route::resource('empleados', 'RecursosHumanos\EmpleadosController');
        Route::get('empleadosautocomplete','RecursosHumanos\EmpleadosController@obtenerEmpleados');
        Route::get('empleado','RecursosHumanos\EmpleadosController@obtenerEmpleado');
        Route::resource('puestos', 'RecursosHumanos\PuestosController');
    });
});