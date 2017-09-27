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

    Route::post('sociosnegocio/{id}/getEstados', 'SociosNegocio\SociosNegocioController@getEstados');
    Route::post('sociosnegocio/{id}/getMunicipios', 'SociosNegocio\SociosNegocioController@getMunicipios');
    // Route::post('sociosnegocio/uploadLicencias', 'SociosNegocio\SociosNegocioController@uploadLicencias');
    Route::post('sociosnegocio/store', 'SociosNegocio\SociosNegocioController@store');
    // Route::get('index', 'Socios\ProveedoresController@index');
    // Route::get('socios/{id}/eliminarContacto', 'Socios\ProveedoresController@eliminarContacto');
    // Route::post('socios/crearContacto', 'Socios\ProveedoresController@crearContacto');
    Route::group(['prefix' => 'sociosnegocio', 'as' => 'sociosnegocio.', 'middleware' => ['auth','share'] ], function() {
        // Route::get('proveedores/crear', 'Socios\ProveedoresController@create')->name('proveedores.crear');
        // Route::resource('proveedores', 'Socios\ProveedoresController');
        Route::resource('sociosnegocio', 'SociosNegocio\SociosNegocioController');
        Route::post('sociosnegocio/{id}/getEstados', 'SociosNegocio\SociosNegocioController@getEstados');
        Route::post('sociosnegocio/{id}/getMunicipios', 'SociosNegocio\SociosNegocioController@getMunicipios');
        // Route::post('sociosnegocio/uploadLicencias', 'SociosNegocio\SociosNegocioController@uploadLicencias');
        // Route::get('proveedores', 'Socios\ProveedoresController@create');
	});
});
