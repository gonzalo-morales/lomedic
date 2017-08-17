<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Tipocombustible;

class TipocombustibleController extends ControllerBase
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct(Tipocombustible $entity)
	{
		$this->entity = $entity;
	}
}
