<?php
/*
|--------------------------------------------------------------------------
| Servicios Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'servicios', 'as' => 'servicios.', 'middleware' => ['auth','share','csrf','password_expired'] ], function() {
        Route::view("/","servicios.index");
        
	    Route::post('getAfiliados','Servicios\RecetasController@getAfiliados')->name('recetas.getAfiliados');
        Route::post('getProyectos','Servicios\RecetasController@getProyectos')->name('recetas.getProyectos');
	    Route::post('getDiagnosticos','Servicios\RecetasController@getDiagnosticos')->name('recetas.getDiagnosticos');
        Route::post('getMedicamentos','Servicios\RecetasController@getMedicamentos')->name('recetas.getMedicamentos');
	    Route::post('verifyStock','Servicios\RecetasController@verifyStock')->name('recetas.verifyStock');
	    Route::post('verifyStockSurtir','Servicios\RecetasController@verifyStockSurtir')->name('recetas.verifyStockSurtir');
	    Route::get('recetas/{id}/surtirReceta','Servicios\RecetasController@surtirReceta')->name('recetas.surtirReceta');
	    Route::post('recetas/{id}/surtir','Servicios\RecetasController@surtir')->name('recetas.surtir');
	    Route::get('recetas/{id}/imprimirReceta','Servicios\RecetasController@imprimirReceta')->name('recetas.imprimirReceta');
        
	    collect(\File::glob(app_path().'/Http/Controllers/Servicios/*Controller.php'))->map(function($file) {
	        $name = strtolower(substr(basename($file),0,-14));
	        $controller = basename(dirname($file)).'\\'.substr(basename($file),0,-4);
	        Route::resource($name,$controller);
	    });
        
        Route::post('verifyStock','Servicios\RecetasController@verifyStock')->name('recetas.verifyStock');
        Route::post('vales/getReceta','Servicios\ValesController@getReceta')->name('vales.getReceta');
        Route::post('vales/getRecetaDetalle','Servicios\ValesController@getRecetaDetalle')->name('vales.getRecetaDetalle');
        Route::post('requisicioneshospitalarias/getDiagnosticos','Servicios\RecetasController@getDiagnosticos')->name('requisicioneshospitalarias.getDiagnosticos');
        Route::post('requisicioneshospitalarias/getMedicamentos','Servicios\RecetasController@getMedicamentos')->name('requisicioneshospitalarias.getMedicamentos');
//        Route::post('vales/impress','Servicios\ValesController@impress')->name('vales.impress');
        Route::get('vales/{id}/impress', 'Servicios\ValesController@impress')->name('vales');
    });
});