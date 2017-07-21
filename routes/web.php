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
	
    Route::resource('bancos', 'BancosController');
	Route::resource('diagnosticos', 'DiagnosticosController');
	Route::resource('areas', 'AreasController');
	Route::resource('tipocombustible', 'TipocombustibleController');
	Route::resource('familiasproductos', 'FamiliasproductosController');

	Route::resource('modulos', 'ModulosController');
    Route::resource('perfiles','PerfilesController');
    Route::resource('usuarios','UsuariosController');
    Route::resource('sucursales','SucursalesController');
    Route::resource('correos','CorreosController');
});
