<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Jurisdicciones;

class JurisdiccionesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Jurisdicciones $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
	{
		return [
		    'states' => Estados::activos()->pluck('estado','id_estado')
		];
	}
}