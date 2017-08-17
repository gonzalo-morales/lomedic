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

    Route::group(['prefix' => 'administracion', 'as' => 'administracion.', 'middleware' => ['auth','share'] ], function() {
        Route::resource('aplicacionesmedicamentos', 'Administracion\AplicacionesMedicamentosController');
		Route::resource('areas', 'Administracion\AreasController');
		Route::resource('bancos', 'Administracion\BancosController');
		Route::resource('correos', 'Administracion\CorreosController');
		Route::resource('devolucionesmotivos', 'Administracion\DevolucionesMotivosController');
		Route::resource('diagnosticos', 'Administracion\DiagnosticosController');
		Route::resource('estados', 'Administracion\EstadosController');
        Route::resource('familiasproductos', 'Administracion\FamiliasproductosController');
        Route::resource('grupoproductos', 'Administracion\GrupoProductosController');
        Route::resource('jurisdicciones', 'Administracion\JurisdiccionesController');
        Route::resource('laboratorios', 'Administracion\LaboratoriosController');
		Route::resource('metodospago', 'Administracion\MetodosPagoController');
		Route::resource('modulos', 'Administracion\ModulosController');
        Route::resource('motivosajustes', 'Administracion\MotivosAjustesController');
        Route::resource('motivosdesviaciones', 'Administracion\MotivosDesviacionesController');
        Route::resource('motivosnotas', 'Administracion\MotivosNotasController');
		Route::resource('municipios', 'Administracion\MunicipiosController');
		Route::resource('numeroscuenta', 'Administracion\NumerosCuentaController');
		Route::resource('paises', 'Administracion\PaisesController');
		Route::resource('parentescos', 'Administracion\ParentescosController');
		Route::resource('perfiles', 'Administracion\PerfilesController');
		Route::resource('sucursales', 'Administracion\SucursalesController');
        Route::get('sucursalesautocomplete','Administracion\SucursalesController@obtenerSucursales');
        Route::get('sucursalesempleado/{id}','Administracion\SucursalesController@sucursalesEmpleado');
        Route::resource('sustanciasactivas', 'Administracion\SustanciasActivasController');
		Route::resource('tipocombustible', 'Administracion\TipocombustibleController');
        Route::resource('unidadesmedicas', 'Administracion\UnidadesMedicasController');
        Route::resource('unidadesmedidas', 'Administracion\UnidadesMedidasController');
		Route::resource('usuarios', 'Administracion\UsuariosController');
        Route::resource('vehiculosmarcas', 'Administracion\VehiculosMarcasController');
        Route::resource('vehiculosmodelos', 'Administracion\VehiculosModelosController');
	});
});
