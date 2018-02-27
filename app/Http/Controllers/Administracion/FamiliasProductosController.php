<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\FamiliasProductos;
use App\Http\Models\Administracion\TiposProductos;

class FamiliasProductosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new FamiliasProductos;
	}

	public function getDataView($entity = null)
	{
		return [
		    'companies' => Empresas::where('activo',1)->select(['nombre_comercial','id_empresa'])->pluck('nombre_comercial','id_empresa'),
		    'product_types'=>TiposProductos::where('activo',1)->pluck('tipo_producto','id_tipo'),
		];
	}
}