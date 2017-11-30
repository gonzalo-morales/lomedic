<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Monedas;
use Illuminate\Http\Request;

class MonedasController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Monedas $entity)
	{
		$this->entity = $entity;
	}
}
