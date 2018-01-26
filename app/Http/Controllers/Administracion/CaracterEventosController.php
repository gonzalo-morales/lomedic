<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\CaracterEventos;

class CaracterEventosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(CaracterEventos $entity)
	{
		$this->entity = $entity;
	}
}