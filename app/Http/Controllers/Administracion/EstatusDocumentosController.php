<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\EstatusDocumentos;

class EstatusDocumentosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(EstatusDocumentos $entity)
	{
		$this->entity = $entity;
	}
}