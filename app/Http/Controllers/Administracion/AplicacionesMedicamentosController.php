<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\AplicacionesMedicamentos;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;

class AplicacionesMedicamentosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(AplicacionesMedicamentos $entity)
	{
		$this->entity = $entity;

	}

	public function update(Request $request, $company, $id)
    {
        $return = parent::update($request, $company, $id);
        return $return['redirect'];
    }


}
