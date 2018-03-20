<?php
/*
|--------------------------------------------------------------------------
| Proyectos Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'proyectos', 'as' => 'proyectos.', 'middleware' => ['auth','share','csrf','password_expired'] ], function() {
        Route::view("/","proyectos.index");
        
        collect(\File::glob(app_path().'/Http/Controllers/Proyectos/*Controller.php'))->map(function($file) {
            $name = strtolower(substr(basename($file),0,-14));
            $controller = basename(dirname($file)).'\\'.substr(basename($file),0,-4);
            Route::resource($name,$controller);
        });
        
        Route::get('getClavesClientes/{id}','Proyectos\ClaveClienteProductosController@obtenerClavesCliente');
        Route::get('proyectos/{id}/descargaranexo', 'Proyectos\ProyectosController@descargaranexo');
        Route::get('proyectos/{id}/descargarcontrato', 'Proyectos\ProyectosController@descargarcontrato');
        Route::get('getProyectos','Proyectos\ProyectosController@obtenerProyectos');
        Route::post('getProductosProyectos','Proyectos\ProyectosController@loadLayoutProductosProyectos');
        Route::get('getProyectosCliente/{id}','Proyectos\ProyectosController@obtenerProyectosCliente');
        Route::get('getLayoutProductosProyecto','Proyectos\ProyectosController@layoutProductosProyecto');
        Route::post('getClavesClientesProductos','Proyectos\ProyectosController@getClavesClientesProductos');
    });
});