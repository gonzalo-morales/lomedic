<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\SolicitudesSalida;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\DireccionesSociosNegocio;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Ventas\Pedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class SolicitudesSalidaController extends ControllerBase
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
		// dd(Pedidos::whereHas('detalle',function($q){
		// 	$q->where('cantidad', '!=' , 0)->orWhereNull('cantidad');
		// })->get());
		
		$proyectos = [];
		$sucursales_entrega = [];
		$upcs = [];

		#
		if ($entity) {
			$proyectos = Proyectos::where('fk_id_cliente', $entity->fk_id_socio_negocio)->pluck('proyecto','id_proyecto');
			$sucursales_entrega = DireccionesSociosNegocio::where('fk_id_socio_negocio', $entity->fk_id_socio_negocio)->where('fk_id_tipo_direccion', 2)->get()->pluck('direccion_concat','id_direccion');
			$entity->detalle()->with(['sku', 'upc', 'almacen'])->get()->each(function($item, $index) use (&$upcs) {
				$upcs[$index] = $item->toArray();
				$upcs[$index]['sku'] = $item->sku->sku;
				$upcs[$index]['upc'] = $item->upc->upc;
				$upcs[$index]['marca'] = $item->upc->marca;
				$upcs[$index]['descripcion'] = $item->upc->descripcion;
				$upcs[$index]['almacen'] = $item->almacen->almacen;
				$upcs[$index]['sucursal'] = $item->almacen->sucursal->sucursal;
			});
		}
		return [
		    'clientes' => SociosNegocio::where('activo',1)->whereNotNull('fk_id_tipo_socio_venta')->pluck('nombre_comercial','id_socio_negocio'),
			'proyectos' => $proyectos,
			'sucursales_entrega' => $sucursales_entrega,
			'skus' => Productos::all()->pluck('sku_descripcion','id_sku'),
			'almacenes' => Almacenes::with('sucursal')->get()->pluck('almacen_sucursal_concat','id_almacen'),
		    'empleados' => Empleados::where('activo',1)->whereIn('fk_id_puesto',[9,15])->selectRaw("Concat(nombre,' ',apellido_paterno,' ',apellido_materno) as empleado, id_empleado")->pluck('empleado','id_empleado'),
			'pedidos' => Pedidos::whereHas('detalle',function($q){
				$q->where('cantidad', '!=' , 0)->orWhereNull('cantidad');
			})->selectRaw("Concat(no_pedido,' - ',observaciones,' - ',fecha_limite) no_pedido, id_documento")->pluck('no_pedido','id_documento')->prepend('Selecciona un pedido...',''),
			'upcs' => $upcs,
			'api_sku' => Crypt::encryptString('"select": ["id_sku","sku","descripcion"], "conditions": [{"where": ["id_sku", "$id_sku"]}], "with": ["upcs:id_upc,descripcion,marca,upc"]'),
			'api_proyectos' => Crypt::encryptString('"conditions": [{"where":["fk_id_cliente", "$fk_id_cliente"]}], "only": ["id_proyecto", "proyecto"]'),
			'api_direcciones' => Crypt::encryptString('"conditions": [{"where":["fk_id_socio_negocio", "$fk_id_socio_negocio"]},{"where":["fk_id_tipo_direccion", "2"]}], "only": ["id_direccion", "direccion_concat"]'),
			'api_pedidos_detalle' => Crypt::encryptString('"conditions": [{"where":["id_documento", "$id_documento"]}], "with": ["detalle"], "select":["id_documento"]'),
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
		$request->request->add([
			'fecha_solicitud' => Carbon::now()
		]);
		return parent::store($request, $company, $compact);
	}

}