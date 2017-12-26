<?php

namespace App\Http\Controllers\Liciplus;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Liciplus\Contratos;

class ContratosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Contratos $entity)
	{
		$this->entity = $entity;
	}
}