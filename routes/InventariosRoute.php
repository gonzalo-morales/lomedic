<?php
/*
|--------------------------------------------------------------------------
| Inventarios Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'inventarios', 'as' => 'inventarios.', 'middleware' => ['auth','csrf','password_expired'] ], function() {
        Route::view("/","inventarios.index");
        
        Route::get('getSkus','Inventarios\ProductosController@obtenerSkus');
        // Route::get('getUpcs/{id}','Inventarios\ProductosController@obtenerUpcs');
        Route::get('getUpcs','Inventarios\ProductosController@getUpcs')->name('productos.getUpcs');
        
        collect(\File::glob(app_path().'/Http/Controllers/Inventarios/*Controller.php'))->map(function($file) {
            $name = strtolower(substr(basename($file),0,-14));
            $controller = basename(dirname($file)).'\\'.substr(basename($file),0,-4);
            Route::resource($name,$controller);
        });
        
        Route::post('getDetalleEntrada','Inventarios\EntradasController@getDetalleEntrada')->name('entradas.getDetalleEntrada');
        Route::post('guardarEntrada','Inventarios\EntradasController@guardarEntrada')->name('entradas.guardarEntrada');
        Route::post('getProveedores','Inventarios\EntradasController@getProveedores')->name('entradas.getPorveedores');
        Route::post('getOrdenes','Inventarios\EntradasController@getOrdenes')->name('entradas.getOrdenes');
        Route::post('getDetalleOrden','Inventarios\EntradasController@getDetalleOrden')->name('entradas.getDetalleOrden');
        Route::get('salidas/{salida}/pendientes','Inventarios\SalidasController@pendings')->name('salidas.pendings');
        Route::post('surtidoreceta/getReceta','Inventarios\SurtidoRecetaController@getReceta')->name('surtidoreceta.getReceta');
        Route::post('surtidoreceta/getRecetaDetalle','Inventarios\SurtidoRecetaController@getRecetaDetalle')->name('surtidoreceta.getRecetaDetalle');
        Route::get('consultaFolio','Inventarios\SurtidoRecetaController@consultaFolio')->name('surtidoreceta.consultaFolio');
        Route::post('surtidoClave','Inventarios\SurtidoRecetaController@consultaFolio')->name('surtidoreceta.surtidoClave');
        Route::post('surtidorequisicionhospitalaria/getRequisiciones','Inventarios\SurtidoRequisicionHospitalariaController@getRequisiciones')->name('surtidorequisicionhospitalaria.getRequisiciones');
        Route::post('surtidorequisicionhospitalaria/getRequisicionDetalle','Inventarios\SurtidoRequisicionHospitalariaController@getRequisicionDetalle')->name('surtidorequisicionhospitalaria.getRequisicionDetalle');
    });
});