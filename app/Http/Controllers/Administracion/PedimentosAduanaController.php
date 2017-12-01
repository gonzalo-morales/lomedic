<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\PedimentosAduana;
use Illuminate\Http\Request;

class PedimentosAduanaController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(PedimentosAduana $entity)
	{
		$this->entity = $entity;
	}
}
