<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\CadenasPagos;
use Illuminate\Http\Request;

class CadenasPagosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(CadenasPagos $entity)
	{
		$this->entity = $entity;
	}
}
