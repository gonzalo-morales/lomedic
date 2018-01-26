<?php

namespace App\Http\Controllers\SociosNegocio;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\SociosNegocio\TiposSocioNegocio;

class TiposSocioNegocioController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(TiposSocioNegocio $entity)
	{
		$this->entity = $entity;
	}
}