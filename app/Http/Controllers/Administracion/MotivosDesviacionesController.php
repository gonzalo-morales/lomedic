<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\MotivosDesviaciones;

class MotivosDesviacionesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(MotivosDesviaciones $entity)
	{
		$this->entity = $entity;
	}
}
