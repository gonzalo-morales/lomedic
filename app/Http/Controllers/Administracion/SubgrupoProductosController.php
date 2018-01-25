<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\SubgrupoProductos;
use App\Http\Models\Administracion\GrupoProductos;

class SubgrupoProductosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(SubgrupoProductos $entity)
	{
		$this->entity = $entity;
	}
	
	public function getDataView($entity = null)
	{
	    return [
	        'groups' => GrupoProductos::where('activo',1)->pluck('grupo','id_grupo')
	    ];
	}
}