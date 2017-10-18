<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TipoAlmacen;
use App\Http\Models\Inventarios\Inventarios;

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
		return [
			'ubicaciones' => $entity ? $entity->ubicaciones()->where('eliminar', 0)->orderby('id_ubicacion', 'DESC')->get() : [],
			'sucursales' => Sucursales::select(['sucursal','id_sucursal'])->where('activo', 1)->pluck('sucursal','id_sucursal'),
			'tipos' => TipoAlmacen::select(['tipo','id_tipo'])->where('activo', 1)->pluck('tipo','id_tipo'),
		];
	}
}