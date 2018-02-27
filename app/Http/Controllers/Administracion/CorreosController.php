<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Correos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Usuarios;

class CorreosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new Correos;
	}

	public function getDataView($entity = null)
	{
		return [
		    'companies' => Empresas::where('activo',1)->select(['nombre_comercial','id_empresa'])->pluck('nombre_comercial','id_empresa'),
		    'users' => Usuarios::where('activo',1)->select(['nombre_corto','id_usuario'])->pluck('nombre_corto','id_usuario')
		];
	}
}