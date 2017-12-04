<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\ClavesProductosServicios;
use Illuminate\Http\Request;

class ClavesProductosServiciosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ClavesProductosServicios $entity)
	{
		$this->entity = $entity;
	}
}
