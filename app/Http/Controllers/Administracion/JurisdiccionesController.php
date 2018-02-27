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
	public function __construct()
	{
	    $this->entity = new Jurisdicciones;
	}

	public function getDataView($entity = null)
	{
		return [
		    'states' => Estados::where('activo',1)->pluck('estado','id_estado')
		];
	}
}