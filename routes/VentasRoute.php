<?php
/*
|--------------------------------------------------------------------------
| Ventas Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'ventas', 'as' => 'ventas.', 'middleware' => ['auth','share','csrf','password_expired']], function() {
        Route::view("/","ventas.index");
        
        Route::get('getProductosRelacionadosCredito','Ventas\NotasCreditoClientesController@getProductosRelacionados');
        Route::get('getProductosRelacionadosCargo','Ventas\NotasCargoClientesController@getProductosRelacionados');
        Route::get('pedidos/{id}/descargaranexo', 'Ventas\PedidosController@descargaranexo');
        Route::get('LayoutProductosPedidos','Ventas\PedidosController@layoutProductos');
        Route::post('ImportarProductosPedido','Ventas\PedidosController@importarProductos');
        
        collect(\File::glob(app_path().'/Http/Controllers/Ventas/*Controller.php'))->map(function($file) {
            $name = strtolower(substr(basename($file),0,-14));
            $controller = basename(dirname($file)).'\\'.substr(basename($file),0,-4);
            Route::resource($name,$controller);
        });
    });
});