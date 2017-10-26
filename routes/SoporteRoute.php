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

    Route::group(['prefix' => 'soporte', 'as' => 'soporte.', 'middleware' => ['auth','share','csrf']], function(){
        Route::resource('acciones', 'Soporte\AccionesController');
        Route::resource('categorias', 'Soporte\CategoriasController');
        Route::resource('estatustickets', 'Soporte\EstatusTicketsController');
        Route::resource('impactos', 'Soporte\ImpactosController');
        Route::resource('modoscontacto', 'Soporte\ModosContactoController');
        Route::resource('prioridades', 'Soporte\PrioridadesController');
        Route::resource('seguimiento', 'Soporte\SeguimientoSolicitudesController');
        Route::get('solicitudes/asignadas', 'Soporte\SolicitudesController@index_tecnico')->name('solicitudes.index_tecnico');
        Route::get('solicitudes/disponibles', 'Soporte\SolicitudesController@index_tecnicos')->name('solicitudes.index_tecnicos');
        Route::get('solicitudes/{id}/acciones', 'Soporte\SolicitudesController@obtenerAcciones');
        Route::get('solicitudes/{id}/descargar', 'Soporte\SolicitudesController@descargarArchivosAdjuntos');
        Route::get('solicitudes/{id}/subcategorias', 'Soporte\SolicitudesController@obtenerSubcategorias');
        Route::resource('solicitudes', 'Soporte\SolicitudesController');
        Route::resource('subcategorias', 'Soporte\SubcategoriasController');
        Route::resource('urgencias', 'Soporte\UrgenciasController');
    });
});
