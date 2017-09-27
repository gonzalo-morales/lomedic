<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\GrupoProductos;

class GrupoProductosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(GrupoProductos $entity)
	{
		$this->entity = $entity;
	}
}
