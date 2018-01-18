<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\TipoSalida;
use App\Http\Models\Administracion\TiposEntrega;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Salidas;
use App\Http\Models\Inventarios\StockDetalle;
use App\Http\Models\Inventarios\Ubicaciones;
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
	public function __construct(Salidas $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
	{
		$proyectos = [];
		$sucursales_entrega = [];
		$upcs = [];
		$skus_data = [];
		$skus = Productos::has('upcs')->get()->tap(function($collection) use (&$skus_data) {
			$skus_data = $collection->mapWithKeys(function($item) {
				return [$item['id_sku'] => [
					'data-sku' => $item['sku'],
					'data-descripcion_corta' => $item['descripcion_corta']
				]];
			})->toArray();
		})->pluck('sku_descripcion','id_sku');

		#
		if ($entity) {
			$proyectos = Proyectos::where('fk_id_cliente', $entity->fk_id_socio_negocio)->pluck('proyecto','id_proyecto');
			$sucursales_entrega = DireccionesSociosNegocio::where('fk_id_socio_negocio', $entity->fk_id_socio_negocio)->where('fk_id_tipo_direccion', 2)->get()->pluck('direccion_concat','id_direccion');
			#
			if (isCurrentRouteName(currentRouteName('pendings'))) {
				$entity_upcs = $entity->detalle()->where('cantidad_pendiente', '!=',0);
			} else {
				$entity_upcs = $entity->detalle();
			}
			$entity_upcs->with(['sku', 'upc', 'almacen'])->get()->each(function($item, $index) use (&$upcs) {
				// dump($item->toArray());
				$upcs[$index] = $item->toArray();
				$upcs[$index]['sku'] = $item->sku->sku;
				$upcs[$index]['upc'] = $item->upc->upc;
				$upcs[$index]['marca'] = $item->upc->marca;
				$upcs[$index]['descripcion'] = $item->upc->descripcion;
				$upcs[$index]['almacen'] = $item->almacen->almacen;
				$upcs[$index]['ubicacion'] = $item->ubicacion->nomenclatura;
				$upcs[$index]['sucursal'] = $item->almacen->sucursal->sucursal;
				if (isCurrentRouteName(currentRouteName('pendings'))) {
					$upcs[$index]['id_detalle'] = null;
					$upcs[$index]['cantidad_solicitada'] = $upcs[$index]['cantidad_pendiente'];
					$upcs[$index]['cantidad_surtida'] = 0;
					$upcs[$index]['cantidad_pendiente'] = 0;
				}
			});
		}

		return [
			'tipos_salida' => TipoSalida::all()->pluck('salida', 'id_tipo'),
			'clientes' => SociosNegocio::on(request()->company)->has('proyectos')->where('activo', 1)->whereNotNull('fk_id_tipo_socio_venta')->with('proyectos')->pluck('nombre_comercial','id_socio_negocio'),
			'proyectos' => $proyectos,
			'sucursales_entrega' => $sucursales_entrega,
			'tipos_entrega' => TiposEntrega::all()->pluck('tipo_entrega', 'id_tipo_entrega'),
			'skus' => $skus,
			'skus_data' => $skus_data,
			'upcs' => $upcs,
			'api_proyectos' => Crypt::encryptString('"select": ["id_proyecto", "proyecto"], "conditions": [{"where":["fk_id_cliente", "$fk_id_cliente"]}]'),
			'api_direcciones' => Crypt::encryptString('"select": ["calle", "num_exterior", "num_interior", "codigo_postal", "id_direccion", "fk_id_estado","fk_id_municipio"], "conditions": [{"where":["fk_id_socio_negocio", "$fk_id_socio_negocio"]},{"where":["fk_id_tipo_direccion", "2"]}], "append": ["direccion_concat"]'),
			'api_sku' => Crypt::encryptString('"select": ["id_sku","sku","descripcion"], "conditions": [{"where": ["id_sku", "$fk_id_sku"]}], "with": ["upcs:id_upc,descripcion,marca,upc"]'),
			'api_verify_stock' => Crypt::encryptString('"conditions": [{"where": ["fk_id_almacen", "$fk_id_almacen"]},{"where": ["fk_id_ubicacion", "$fk_id_ubicacion"]},{"where": ["fk_id_sku", "$fk_id_sku"]}, {"where": ["fk_id_upc", "$fk_id_upc"]}]'),
			'api_ubicaciones' => Crypt::encryptString('"select":["lote","stock","fk_id_ubicacion"],"with": ["ubicacion:id_ubicacion,nivel,posicion,rack,ubicacion,fk_id_almacen", "ubicacion.almacen:id_almacen,almacen,fk_id_sucursal", "ubicacion.almacen.sucursal:id_sucursal,sucursal"], "conditions": [{"where": ["fk_id_sku", "$fk_id_sku"]}, {"where": ["fk_id_upc", "$fk_id_upc"]}]'),
		];
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $company, $compact = false)
	{
		# PENDIENTE --->
		# ¿Salida completa?
		// $notPendings = collect($request->relations['has']['detalle'])->every(function ($item) {
		// 	return $item['cantidad_pendiente'] == 0;
		// });
		// $request->request->add([
		// 	# 0: Abierto, 1: Cerrado, 2: Cancelado, 3: Surtido Parcial, 4: Surtido
		// 	'estatus' => $notPendings ? 4 : 3,
		// ]);
		# PENDIENTE <---
		$request->request->add([
			'fecha_salida' => Carbon::now()
		]);
		return parent::store($request, $company, $compact);
	}

	/**
	 * Formulario para crear registro usando informacion pre-cargada
	 *
	 * @param  integer  $salida
	 * @return \Illuminate\Http\Response
	 */
	public function pendings(Request $request, $company, $salida) {
		return parent::edit($company, $salida);
	}

	/**
	 * Cancelamos regristro
	 *
	 * @param  integer  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $company, $idOrIds, $attributes = [])
	{
		return parent::destroy($request, $company, $idOrIds, [
			'motivo_cancelacion' => $request->motivo_cancelacion ?? '',
			'estatus' => Salidas::CANCELADO
		]);
	}
}