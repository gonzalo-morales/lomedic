<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\SustanciasActivas;

class SustanciasActivasController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct(SustanciasActivas $entity)
	{
		$this->entity = $entity;
	}
}