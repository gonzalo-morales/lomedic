<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\ConceptosViaje;

class ConceptosViajeController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ConceptosViaje $entity)
	{
		$this->entity = $entity;
	}
}
