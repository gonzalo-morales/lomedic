<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\RegimenesFiscales;
use Illuminate\Http\Request;

class RegimenesFiscalesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(RegimenesFiscales $entity)
	{
		$this->entity = $entity;
	}
}
