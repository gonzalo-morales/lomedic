<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\TipoSalida;
use App\Http\Models\Administracion\TiposEntrega;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\SolicitudesSalida;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\DireccionesSociosNegocio;
use App\Http\Models\SociosNegocio\SociosNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class SalidasController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(SolicitudesSalida $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
	{
		// dump( [] );
		return [
			'tipos_salida' => TipoSalida::all()->pluck('salida', 'id_tipo'),
			'clientes' => SociosNegocio::where('activo', 1)->whereNotNull('fk_id_tipo_socio_venta')->pluck('nombre_comercial','id_socio_negocio'),
			'api_proyectos' => Crypt::encryptString('"conditions": [{"where":["fk_id_cliente", "$fk_id_cliente"]}], "only": ["id_proyecto", "proyecto"]'),
			'api_direcciones' => Crypt::encryptString('"conditions": [{"where":["fk_id_socio_negocio", "$fk_id_socio_negocio"]},{"where":["fk_id_tipo_direccion", "2"]}], "only": ["id_direccion", "direccion_concat"]'),
			'tipos_entrega' => TiposEntrega::all()->pluck('tipo_entrega', 'id_tipo_entrega'),
			'skus' => Productos::all()->pluck('sku_descripcion','id_sku'),
			'almacenes' => Almacenes::with('sucursal')->get()->pluck('almacen_sucursal_concat','id_almacen'),
			'api_sku' => Crypt::encryptString('"select": ["id_sku","sku","descripcion"], "conditions": [{"where": ["id_sku", "$id_sku"]}], "with": ["upcs:id_upc,descripcion,marca,upc"]'),

		];
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $company)
	{
		// $request->request->add([
		//     'fecha_solicitud' => Carbon::now()
		// ]);
		return parent::store($request, $company);
	}

}