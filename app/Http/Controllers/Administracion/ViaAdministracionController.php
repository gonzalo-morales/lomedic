<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\ViaAdministracion;

class ViaAdministracionController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct(ViaAdministracion $entity)
	{
		$this->entity = $entity;
	}
}
