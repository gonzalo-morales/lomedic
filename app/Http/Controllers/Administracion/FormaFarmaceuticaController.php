<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\FormaFarmaceutica;

class FormaFarmaceuticaController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct(FormaFarmaceutica $entity)
	{
		$this->entity = $entity;
	}
}
