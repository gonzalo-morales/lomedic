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
    Route::group(['prefix' => 'administracion', 'as' => 'administracion.', 'middleware' => ['auth','share','csrf','password_expired'] ], function() {
        Route::view("/","administracion.index");
        
		Route::resource('aplicacionesmedicamentos', 'Administracion\AplicacionesMedicamentosController');
		Route::resource('areas', 'Administracion\AreasController');
		Route::resource('bancos', 'Administracion\BancosController');
		Route::resource('cadenaspagos', 'Administracion\CadenasPagosController');
        Route::resource('caractereventos','Administracion\CaracterEventosController');
		Route::resource('clavesproductosservicios', 'Administracion\ClavesProductosServiciosController');
		Route::resource('clavesunidades', 'Administracion\ClavesUnidadesController');
		Route::resource('conceptosviaje', 'Administracion\ConceptosViajeController');
		Route::resource('correos', 'Administracion\CorreosController');
		Route::resource('dependencias','Administracion\DependenciasController');
		Route::resource('devolucionesmotivos', 'Administracion\DevolucionesMotivosController');
		Route::resource('diagnosticos', 'Administracion\DiagnosticosController');
		Route::resource('empresas', 'Administracion\EmpresasController');
		Route::get('empresas/{id}/descargar/{archivo}', 'Administracion\EmpresasController@descargar');
		Route::post('getDatoscer', 'Administracion\EmpresasController@getDatoscer');
		Route::resource('estados', 'Administracion\EstadosController');
        Route::resource('estatusdocumentos', 'Administracion\EstatusDocumentosController');
		Route::resource('familiasproductos', 'Administracion\FamiliasProductosController');
		Route::resource('formafarmaceutica', 'Administracion\FormaFarmaceuticaController');
        Route::resource('formasadjudicacion','Administracion\FormasAdjudicacionController');
		Route::resource('formaspago', 'Administracion\FormasPagoController');
		Route::resource('grupoproductos', 'Administracion\GrupoProductosController');
		Route::resource('impuestos', 'Administracion\ImpuestosController');
		Route::get('getPorcentaje/{id}','Administracion\ImpuestosController@obtenerPorcentaje');
		Route::get('getImpuestos','Administracion\ImpuestosController@obtenerImpuestos');
		Route::resource('indicacionterapeutica', 'Administracion\IndicacionTerapeuticaController');
		Route::resource('jurisdicciones', 'Administracion\JurisdiccionesController');
		Route::resource('laboratorios', 'Administracion\LaboratoriosController');
		Route::resource('localidades', 'Administracion\LocalidadesController');
		Route::resource('metodospago', 'Administracion\MetodosPagoController');
		Route::resource('metodosvaloracion', 'Administracion\MetodosValoracionController');
        Route::resource('modalidadesentrega','Administracion\ModalidadesentregaController');
		Route::resource('modulos', 'Administracion\ModulosController');
		Route::resource('monedas', 'Administracion\MonedasController');
		Route::resource('motivosajustes', 'Administracion\MotivosAjustesController');
		Route::resource('motivosdesviaciones', 'Administracion\MotivosDesviacionesController');
		Route::resource('motivosnotas', 'Administracion\MotivosNotasController');
		Route::resource('municipios', 'Administracion\MunicipiosController');
		Route::resource('numeroscuenta', 'Administracion\NumerosCuentaController');
		Route::resource('paises', 'Administracion\PaisesController');
		Route::resource('parentescos', 'Administracion\ParentescosController');
		Route::resource('patrones', 'Administracion\PatronesController');
		Route::resource('pedimentosaduana', 'Administracion\PedimentosAduanaController');
		Route::resource('perfiles', 'Administracion\PerfilesController');
		Route::resource('periodos', 'Administracion\PeriodosController');
		Route::resource('presentacionventa', 'Administracion\PresentacionVentaController');
		Route::resource('regimenesfiscales', 'Administracion\RegimenesFiscalesController');
		Route::resource('satmunicipios', 'Administracion\SatMunicipiosController');
		Route::resource('seriesdocumentos', 'Administracion\SeriesDocumentosController');
		Route::resource('seriesskus', 'Administracion\SeriesSkusController');
		Route::get('getSerie/{id}','Administracion\SeriesSkusController@getSerie');
        Route::resource('subdependencias','Administracion\SubdependenciasController');
		Route::resource('subgrupoproductos', 'Administracion\SubgrupoProductosController');
		Route::resource('sucursales', 'Administracion\SucursalesController');
		Route::get('sucursalesautocomplete','Administracion\SucursalesController@obtenerSucursales');
		Route::get('sucursalesempleado/{id}','Administracion\SucursalesController@sucursalesEmpleado');
		Route::resource('sustanciasactivas', 'Administracion\SustanciasActivasController');
		Route::resource('tipoalmacen', 'Administracion\TipoAlmacenController');
		Route::resource('tipocombustible', 'Administracion\TipoCombustibleController');
		Route::resource('tipodocumento', 'Administracion\TipoDocumentosController');
		Route::resource('tipoentrega', 'Administracion\TipoEntregaController');
        Route::resource('tiposeventos','Administracion\TiposEventosController');
        Route::resource('tipoinventario', 'Administracion\TipoInventarioController');
		Route::resource('tipoproductos', 'Administracion\TipoProductosController');
		Route::resource('tiposucursal', 'Administracion\TipoSucursalController');
		Route::resource('tiposcomprobantes', 'Administracion\TiposComprobantesController');
		Route::resource('unidadesmedicas', 'Administracion\UnidadesMedicasController');
		Route::resource('unidadesmedidas', 'Administracion\UnidadesMedidasController');
		Route::resource('usoscfdis', 'Administracion\UsosCfdisController');
		Route::resource('usuarios', 'Administracion\UsuariosController');
		Route::resource('vehiculosmarcas', 'Administracion\VehiculosMarcasController');
		Route::resource('vehiculosmodelos', 'Administracion\VehiculosModelosController');
		Route::resource('viaadministracion', 'Administracion\ViaAdministracionController');
		Route::resource('zonas', 'Administracion\ZonasController');
        Route::resource('pacientes', 'Administracion\AfiliacionesController');
        Route::post('pacientes/getDependientes','Administracion\AfiliacionesController@getDependientes')->name('pacientes.getDependientes');
	});
});