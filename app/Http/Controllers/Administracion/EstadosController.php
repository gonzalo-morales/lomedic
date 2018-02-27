<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Paises;

class EstadosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new Estados;
	}

	public function getDataView($entity = null)
	{
		return [
			'paises' => Paises::select(['id_pais', 'pais'])->orderBy('pais')->pluck('pais','id_pais'),
		];
	}
}
