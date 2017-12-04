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
    Route::group(['prefix' => 'sociosnegocio', 'as' => 'sociosnegocio.', 'middleware' => ['auth','share','csrf'] ], function() {
        Route::view("/","sociosnegocio.index");
        Route::resource('sociosnegocio', 'SociosNegocio\SociosNegocioController');
        Route::get('sociosnegocio/{id}/descargar', 'SociosNegocio\SociosNegocioController@descargar');
        Route::post('sociosnegocio/{id}/getEstados', 'SociosNegocio\SociosNegocioController@getEstados');
        Route::post('sociosnegocio/{id}/getMunicipios', 'SociosNegocio\SociosNegocioController@getMunicipios');
        Route::get('sociosnegocio/getData', 'SociosNegocio\SociosNegocioController@getData');
	});
});
