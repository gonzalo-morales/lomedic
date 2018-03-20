<?php
/*
|--------------------------------------------------------------------------
| Soporte Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'soporte', 'as' => 'soporte.', 'middleware' => ['auth','share','csrf','password_expired']], function(){
        Route::get('solicitudes/asignadas', 'Soporte\SolicitudesController@index_tecnico')->name('solicitudes.index_tecnico');
        Route::get('solicitudes/disponibles', 'Soporte\SolicitudesController@index_tecnicos')->name('solicitudes.index_tecnicos');
        Route::get('solicitudes/{id}/acciones', 'Soporte\SolicitudesController@obtenerAcciones');
        Route::get('solicitudes/{id}/descargar', 'Soporte\SolicitudesController@descargarArchivosAdjuntos');
        Route::get('solicitudes/{id}/subcategorias', 'Soporte\SolicitudesController@obtenerSubcategorias');
        Route::get('solicitudes/getcategorias', 'Soporte\SolicitudesController@getCategorias');
        Route::get('solicitudes/getprioridades', 'Soporte\SolicitudesController@getPrioridades');
        
        collect(\File::glob(app_path().'/Http/Controllers/Soporte/*Controller.php'))->map(function($file) {
            $name = strtolower(substr(basename($file),0,-14));
            $controller = basename(dirname($file)).'\\'.substr(basename($file),0,-4);
            Route::resource($name,$controller);
        });
    });
});