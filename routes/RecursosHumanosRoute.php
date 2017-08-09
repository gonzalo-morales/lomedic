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

    Route::group(['prefix' => 'soporte', 'as' => 'soporte.', 'middleware' => ['auth','share']], function(){
        Route::resource('estatustickets', 'Soporte\EstatusTicketsController');
        Route::resource('categorias', 'Soporte\CategoriasController');
        Route::resource('subcategorias', 'Soporte\SubcategoriasController');
        Route::resource('acciones', 'Soporte\AccionesController');
        Route::resource('impactos', 'Soporte\ImpactosController');
        Route::resource('urgencias', 'Soporte\UrgenciasController');
        Route::resource('prioridades', 'Soporte\PrioridadesController');
        Route::resource('modoscontacto', 'Soporte\ModosContactoController');
    });
});
