<?php

namespace App\Http\Controllers\SociosNegocio;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\SociosNegocio\RamosSocioNegocio;

class RamosSocioNegocioController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct()
	{
	    $this->entity = new RamosSocioNegocio;
	}
}