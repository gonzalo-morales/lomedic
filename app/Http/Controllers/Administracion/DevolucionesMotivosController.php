<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\DevolucionesMotivos;
use Illuminate\Http\Request;

class DevolucionesMotivosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(DevolucionesMotivos $entity)
	{
		$this->entity = $entity;
	}
}
