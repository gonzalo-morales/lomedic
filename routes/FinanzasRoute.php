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

    Route::group(['prefix' => 'finanzas', 'as' => 'finanzas.', 'middleware' => ['auth','share','csrf'] ], function() {
        Route::view("/","finanzas.index");
        Route::resource('gastosviajes', 'Finanzas\GastosViajeController');
    });
});
