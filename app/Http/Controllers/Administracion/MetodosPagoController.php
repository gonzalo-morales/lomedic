<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\MetodosPago;

class MetodosPagoController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(MetodosPago $entity)
	{
		$this->entity = $entity;
	}
}
