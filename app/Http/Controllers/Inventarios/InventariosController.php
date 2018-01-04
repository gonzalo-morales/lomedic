<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TipoInventario;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\Inventarios;
use App\Http\Models\Inventarios\Ubicaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class InventariosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Inventarios $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
	{
		$almacenes = [];
		$vue_almacenes = ['0' => ['value' => 0, 'text' => '...', 'selected' => true, 'disabled' => true]];
		$vue_ubicaciones = ['0' => ['0' => ['value' => 0, 'text' => '...', 'selected' => true, 'disabled' => true]]];

		#
		if ($entity) {
			#
			Almacenes::where('fk_id_sucursal', $entity->fk_id_sucursal)->get()->pluck('almacen', 'id_almacen')->tap(function($collection) use (&$almacenes){
				$almacenes += $collection->toArray();
			})->map(function($value, $index){
				return ['value' => $index, 'text' => $value];
			})->tap(function($collection) use (&$vue_almacenes) {
				$vue_almacenes += $collection->toArray();
			});

			#
			$vue_ubicaciones += Ubicaciones::whereIn('fk_id_almacen', array_keys($almacenes))->get()->reduce(function($acc, $item) {
				$acc[$item->fk_id_almacen][0] = ['value' => 0, 'text' => 'Selecciona ...'];
				$acc[$item->fk_id_almacen][$item->id_ubicacion] = ['value' => $item->id_ubicacion, 'text' => $item->ubicacion];
				return $acc;
			}, []);
		}

		return [
			'tipos' => TipoInventario::select(['tipo','id_tipo'])->where('activo', 1)->pluck('tipo','id_tipo'),
			'sucursales' => Sucursales::select(['sucursal','id_sucursal'])->where('activo', 1)->pluck('sucursal','id_sucursal'),
			'almacenes' => $almacenes,
			'upcs' => $entity ? $entity->detalle()->with('upc:nombre_comercial,descripcion,upc')->orderby('id_detalle', 'DESC')->get() : [],
			'vue_almacenes' => $vue_almacenes,
			'vue_ubicaciones' => $vue_ubicaciones,
			'api_codebar' => Crypt::encryptString('"select": ["nombre_comercial", "descripcion"], "conditions": [{"where": ["upc", "$codigo_barras"]}]'),
			'api_almacen' => Crypt::encryptString('"conditions": [{"where": ["fk_id_almacen", "$fk_id_almacen"]}]'),
			'api_almacenes_ubicaciones' => Crypt::encryptString('"conditions": [{"where":["fk_id_sucursal", "$fk_id_sucursal"]}], "with": ["ubicaciones:id_ubicacion,fk_id_almacen,ubicacion"]')
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
		$request->request->add([
			'fecha_creacion' => Carbon::now()
		]);
		$return = parent::store($request, $company);
		return $return['redirect'];
	}
}