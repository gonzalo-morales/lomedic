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
    Route::resource('/', 'HomeController');

    Route::group(['prefix' => 'administracion', 'as' => 'administracion.', 'middleware' => 'auth' ], function() {
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
	});
});
