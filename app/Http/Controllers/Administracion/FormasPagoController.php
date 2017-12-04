<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\FormasPago;
use Illuminate\Http\Request;

class FormasPagoController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(FormasPago $entity)
	{
		$this->entity = $entity;
	}
}
