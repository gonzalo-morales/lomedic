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
	public function __construct()
	{
	    $this->entity = new Subdependencias;
	}

	public function getDataView($entity = null)
    {
        return ['dependencias'=>Dependencias::where('activo',1)->pluck('dependencia','id_dependencia')];
    }
}