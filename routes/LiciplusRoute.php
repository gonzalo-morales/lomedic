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
    Route::group(['prefix' => 'liciplus', 'as' => 'liciplus.', 'middleware' => ['auth','share','csrf','password_expired']], function(){
        Route::resource('licitaciones', 'Liciplus\LicitacionesController');
        Route::resource('contratos','Liciplus\ContratosController');
        Route::resource('partidas','Liciplus\PartidasController');
    });
});