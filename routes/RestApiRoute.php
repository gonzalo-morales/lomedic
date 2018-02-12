<?php

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'restapi', 'as' => 'restapi.', 'middleware' => 'auth.api'], function() {
        Route::resource('pagos', 'RestApi\PagosController');
        Route::resource('facturasclientes', 'RestApi\FacturasClientesController');
        Route::resource('facturasproveedores', 'RestApi\FacturasProveedoresController');
        Route::resource('notascreditoclientes', 'RestApi\NotasCreditoClientesController');
        Route::resource('notascargoclientes', 'RestApi\NotasCargoClientesController');
        Route::resource('notascreditoproveedores', 'RestApi\NotasCreditoClientesController');
        Route::resource('entradas', 'RestApi\MovimientosEntradaController');
        Route::resource('salidas', 'RestApi\MovimientosSalidaController');
        Route::resource('proveedores', 'RestApi\SociosNegocioController');
    });
});