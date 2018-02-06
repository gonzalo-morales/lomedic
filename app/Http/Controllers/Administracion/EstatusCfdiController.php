<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\EstatusCfdi;

class EstatusCfdiController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(EstatusCfdi $entity)
	{
		$this->entity = $entity;
	}
}