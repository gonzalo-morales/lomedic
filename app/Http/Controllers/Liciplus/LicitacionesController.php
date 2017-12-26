<?php

namespace App\Http\Controllers\Liciplus;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Liciplus\Licitaciones;

class LicitacionesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Licitaciones $entity)
	{
		$this->entity = $entity;
	}
}