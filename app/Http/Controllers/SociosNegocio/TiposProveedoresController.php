<?php

namespace App\Http\Controllers\SociosNegocio;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\SociosNegocio\TiposProveedores;

class TiposProveedoresController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(TiposProveedores $entity)
	{
		$this->entity = $entity;
	}
}