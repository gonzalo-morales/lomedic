<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\UnidadesMedidas;

class UnidadesMedidasController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct(UnidadesMedidas $entity)
	{
		$this->entity = $entity;
	}
}