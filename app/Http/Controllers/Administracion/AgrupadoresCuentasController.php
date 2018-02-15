<?php
namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\AgrupadoresCuentas;

class AgrupadoresCuentasController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct(AgrupadoresCuentas $entity)
	{
		$this->entity = $entity;
	}
}