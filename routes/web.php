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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {

	Route::group(['middleware' => 'share'], function() {
	    Route::resource('/', 'HomeController');
	});

    Route::group(['prefix' => 'administracion', 'as' => 'administracion.', 'middleware' => ['auth','share'] ], function() {
		Route::resource('bancos', 'Administracion\BancosController');
		Route::resource('areas', 'Administracion\AreasController');
		Route::resource('diagnosticos', 'Administracion\DiagnosticosController');
		Route::resource('tipocombustible', 'Administracion\TipocombustibleController');
		Route::resource('familiasproductos', 'Administracion\FamiliasproductosController');
		Route::resource('modulos', 'Administracion\ModulosController');
		Route::resource('perfiles', 'Administracion\PerfilesController');
		Route::resource('usuarios', 'Administracion\UsuariosController');
		Route::resource('sucursales', 'Administracion\SucursalesController');
		Route::resource('correos', 'Administracion\CorreosController');
		Route::resource('municipios', 'Administracion\MunicipiosController');
		Route::resource('estados', 'Administracion\EstadosController');
		Route::resource('paises', 'Administracion\PaisesController');
		Route::resource('metodospago', 'Administracion\MetodosPagoController');
		Route::resource('parentescos', 'Administracion\ParentescosController');
	});

    Route::group(['prefix' => 'recursos_humanos', 'as' => 'recursos_humanos.', 'middleware' => ['auth','share']], function(){
       Route::resource('empleados', 'RecursosHumanos\EmpleadosController');
    });
});
