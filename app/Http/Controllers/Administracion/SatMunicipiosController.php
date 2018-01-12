<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\SatMunicipios;

class SatMunicipiosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(SatMunicipios $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
	{
		return [
			'estados' => Estados::select(['estado','id_estado'])->pluck('estado','id_estado'),
		];
	}
}