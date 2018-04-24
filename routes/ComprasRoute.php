<?php
/*
|--------------------------------------------------------------------------
| Compras Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'compras', 'as' => 'compras.', 'middleware' => ['auth','csrf','password_expired']], function(){
        Route::group(['prefix'=>'{id}/{tipo_documento}'], function (){
            Route::resource('solicitudes','Compras\SolicitudesController',['only'=>['create']]);
        });
        Route::group(['prefix' => 'solicitudes/{id_solicitud}'], function(){
            Route::resource('ofertas','Compras\OfertasController');
        });
        Route::group(['prefix'=>'{id}/{tipo_documento}'], function (){
            Route::resource('ordenes','Compras\OrdenesController',['only'=>['create']]);
        });

        Route::get("/", function(){ return View::make("compras.index"); });

        Route::group(['prefix' => 'solicitudes/{id_solicitud}'], function(){
            Route::resource('ofertas','Compras\OfertasController');
        });
        Route::group(['prefix'=>'{id}/{tipo_documento}'], function (){
            Route::resource('ordenes','Compras\OrdenesController',['only'=>['create']]);
        });
        
        Route::get('ordenes/getProveedores', 'Compras\OrdenesController@getProveedores')->name('ordenes');
        Route::post('ordenes/destroyDetail', 'Compras\OrdenesController@destroyDetail')->name('ordenes');
        Route::get('ordenes/{id}/solicitudOrden','Compras\OrdenesController@createSolicitudOrden')->name('ordenes');

        collect(\File::glob(app_path().'/Http/Controllers/Compras/*Controller.php'))->map(function($file) {
            $name = strtolower(substr(basename($file),0,-14));
            $controller = basename(dirname($file)).'\\'.substr(basename($file),0,-4);
            Route::resource($name,$controller);
        });
        Route::post('getFacturaData','Compras\NotasCreditoProveedorController@parseXML');
        Route::get('ofertas/{id}/impress', 'Compras\OfertasController@impress')->name('ofertas');
        Route::post('getDetallesOrden','Compras\OrdenesController@getDetallesOrden');
        // Route::post('getFacturaData','Compras\FacturasProveedoresController@parseXML');
        Route::post('getFacturaData2','Compras\FacturasProveedoresController@parseXML'); // Por conflicto con los nombres similares
        Route::post('getDocumentos','Compras\SeguimientoDesviacionesController@getDocumentos');
        Route::post('getDesviaciones','Compras\SeguimientoDesviacionesController@getDesviaciones');
        Route::post('getDetalleDesviacion','Compras\SeguimientoDesviacionesController@getDetalleDesviacion');
        Route::post('actualizarEstatus','Compras\DetalleSeguimientoDesviacionController@actualizarEstatus');
        Route::get('pagos/{id}/download','Compras\PagosController@descargaComprobante');
        Route::get('solicitudes/{id}/impress', 'Compras\SolicitudesController@impress')->name('solicitudes');
        Route::get('ordenes/{id}/impress', 'Compras\OrdenesController@impress')->name('ordenes');
    });
});
