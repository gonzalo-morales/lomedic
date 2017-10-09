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

    Route::group(['prefix' => 'inventarios', 'as' => 'inventarios.', 'middleware' => ['auth','share'] ], function() {
        Route::resource('skus', 'Inventarios\SkusController');
        Route::get('getSkus','Inventarios\SkusController@obtenerSkus');
        Route::get('getUpcs/{id}','Inventarios\SkusController@obtenerUpcs');
        Route::resource('upcs','Inventarios\UpcsController');
        Route::resource('almacenes','Inventarios\AlmacenesController');
    });
});
