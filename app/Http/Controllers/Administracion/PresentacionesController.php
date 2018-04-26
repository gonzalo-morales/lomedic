<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Presentaciones;
use App\Http\Controllers\Administracion\UnidadesMedidas;

class PresentacionesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new Presentaciones;
	}

	public function getDataView($entity = null)
	{
		return [
		    'unidadesmedidas' => UnidadesMedidas::where('activo',1)->select('nombre','clave','id_unidad_medida')->pluck('nombre','id_unidad_medida'),
		];
	}
}
