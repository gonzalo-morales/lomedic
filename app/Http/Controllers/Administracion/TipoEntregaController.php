<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\TiposEntrega;
use Illuminate\Http\Request;

class TipoEntregaController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(TiposEntrega $entity)
	{
		$this->entity = $entity;
	}
}
