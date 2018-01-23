<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Dependencias;
use App\Http\Models\Administracion\Subdependencias;

class SubdependenciasController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Subdependencias $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {
        return ['dependencias'=>Dependencias::where('activo',1)->where('eliminar',0)->pluck('dependencia','id_dependencia')];
    }
}