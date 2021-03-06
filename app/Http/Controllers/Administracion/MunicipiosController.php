<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Municipios;

class MunicipiosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new Municipios;
	}

	public function getDataView($entity = null)
	{
		return [
			'estados' => Estados::select(['estado','id_estado'])->pluck('estado','id_estado'),
		];
	}
}