<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\AplicacionesMedicamentos;
use Illuminate\Http\Request;

class AplicacionesMedicamentosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(AplicacionesMedicamentos $entity)
	{
		$this->entity = $entity;
	}

}
