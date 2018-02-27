<?php
namespace App\Http\Controllers\Finanzas;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Finanzas\CondicionesPago;

class CondicionesPagoController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new CondicionesPago;
	}
}