<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\PresentacionVenta;

class PresentacionVentaController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct(PresentacionVenta $entity)
	{
		$this->entity = $entity;
	}
}
