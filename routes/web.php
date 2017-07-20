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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::pattern('company', '(pgsql|one|two)');
Route::prefix('{company}')->group(function () {
    Route::resource('bancos', 'BancosController');
    Route::resource('perfiles','PerfilesController');
    Route::resource('usuarios','UsuariosController');
    Route::resource('sucursales','SucursalesController');
    Route::resource('correos','CorreosController');
});
