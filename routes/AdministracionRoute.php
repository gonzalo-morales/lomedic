<?php
/*
|--------------------------------------------------------------------------
| Administracion Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'administracion', 'as' => 'administracion.', 'middleware' => ['auth','share','csrf','password_expired'] ], function() {
        Route::view("/","administracion.index");
        
        
        collect(\File::glob(app_path().'/Http/Controllers/Administracion/*Controller.php'))->map(function($file) {
            $name = strtolower(substr(basename($file),0,-14));
            $controller = basename(dirname($file)).'\\'.substr(basename($file),0,-4);
            Route::resource($name,$controller);
        });
        
		Route::get('empresas/{id}/descargar/{archivo}', 'Administracion\EmpresasController@descargar');
		Route::post('getDatoscer', 'Administracion\EmpresasController@getDatoscer');
		Route::get('getPorcentaje/{id}','Administracion\ImpuestosController@obtenerPorcentaje');
		Route::get('getImpuestos','Administracion\ImpuestosController@obtenerImpuestos');
		Route::get('getSerie/{id}','Administracion\SeriesSkusController@getSerie');
		Route::get('sucursalesautocomplete','Administracion\SucursalesController@obtenerSucursales');
		Route::get('sucursalesempleado/{id}','Administracion\SucursalesController@sucursalesEmpleado');
        Route::post('afiliaciones/getDependientes','Administracion\AfiliacionesController@getDependientes')->name('afiliaciones.getDependientes');
	});
});