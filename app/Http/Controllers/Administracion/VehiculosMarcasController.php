<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\VehiculosMarcas;

class VehiculosMarcasController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct(VehiculosMarcas $entity)
	{
		$this->entity = $entity;
	}
}