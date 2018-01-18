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
    Route::group(['prefix' => 'compras', 'as' => 'compras.', 'middleware' => ['auth','share','csrf','password_expired']], function(){
        Route::get("/", function(){ return View::make("compras.index"); });
        
        Route::resource('autorizaciones','Compras\AutorizacionesController');
        Route::resource('facturasproveedores','Compras\FacturasProveedoresController');
        Route::post('getFacturaData','Compras\FacturasProveedoresController@parseXML');
        Route::resource('notascreditoproveedores','Compras\NotasCreditoProveedorController');
        Route::post('getFacturaData','Compras\NotasCreditoProveedorController@parseXML');
        Route::resource('ofertas','Compras\OfertasController');
        Route::get('ofertas/{id}/impress', 'Compras\OfertasController@impress')->name('ofertas');
        Route::get('ordenes/getProveedores', 'Compras\OrdenesController@getProveedores')->name('ordenes');
        Route::post('ordenes/destroyDetail', 'Compras\OrdenesController@destroyDetail')->name('ordenes');
        Route::get('ordenes/{id}/impress', 'Compras\OrdenesController@impress')->name('ordenes');
        Route::get('ordenes/{id}/solicitudOrden','Compras\OrdenesController@createSolicitudOrden')->name('ordenes');
        Route::resource('ordenes','Compras\OrdenesController');
        Route::resource('pagos','Compras\PagosController');
        Route::get('solicitudes/{id}/impress', 'Compras\SolicitudesController@impress')->name('solicitudes');
        Route::resource('solicitudes', 'Compras\SolicitudesController');
        Route::resource('solicitudes_detalles', 'Compras\DetalleSolicitudesController');
        Route::resource('solicitudespagos','Compras\SolicitudesPagosController');
        
        Route::group(['prefix' => 'solicitudes/{id_solicitud}'], function(){
            Route::resource('ofertas','Compras\OfertasController');
        });
        Route::group(['prefix'=>'{id}/{tipo_documento}'], function (){
            Route::resource('ordenes','Compras\OrdenesController',['only'=>['create']]);
        });
    });
});