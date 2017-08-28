<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\MotivosNotas;

class MotivosNotasController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(MotivosNotas $entity)
	{
		$this->entity = $entity;
	}
}
