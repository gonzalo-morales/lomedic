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
    Route::group(['prefix' => 'proyectos', 'as' => 'proyectos.', 'middleware' => ['auth','share','csrf','password_expired'] ], function() {
        Route::view("/","proyectos.index");
        
        Route::resource('clasificaciones','Proyectos\ClasificacionesProyectosController');
        Route::resource('clave_cliente_productos','Proyectos\ClaveClienteProductosController');
        Route::get('getClavesClientes/{id}','Proyectos\ClaveClienteProductosController@obtenerClavesCliente');
        Route::resource('proyectos', 'Proyectos\ProyectosController');
        Route::get('proyectos/{id}/descargaranexo', 'Proyectos\ProyectosController@descargaranexo');
        Route::get('proyectos/{id}/descargarcontrato', 'Proyectos\ProyectosController@descargarcontrato');
        Route::get('getProyectos','Proyectos\ProyectosController@obtenerProyectos');
        Route::post('getProductosProyectos','Proyectos\ProyectosController@loadLayoutProductosProyectos');
        Route::get('getProyectosCliente/{id}','Proyectos\ProyectosController@obtenerProyectosCliente');
        Route::get('getLayoutProductosProyecto','Proyectos\ProyectosController@layoutProductosProyecto');
        Route::post('getClavesClientesProductos','Proyectos\ProyectosController@getClavesClientesProductos');
        #Route::resource('tipos_productos','Proyectos\TiposProductosProyectosController');
        Route::resource('tiposproyectos','Proyectos\TiposProyectosController');
    });
});