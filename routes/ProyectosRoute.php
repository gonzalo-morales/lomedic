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

    Route::group(['prefix' => 'proyectos', 'as' => 'proyectos.', 'middleware' => ['auth','share'] ], function() {
        Route::resource('proyectos', 'Proyectos\ProyectosController');
        Route::get('getProyectos','Proyectos\ProyectosController@obtenerProyectos');
    });
});
