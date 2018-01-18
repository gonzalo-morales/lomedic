<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\MetodosValoracion;

class MetodosValoracionController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(MetodosValoracion $entity)
	{
		$this->entity = $entity;
	}
}