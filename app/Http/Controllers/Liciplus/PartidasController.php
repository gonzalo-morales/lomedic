<?php

namespace App\Http\Controllers\Liciplus;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Liciplus\Partidas;

class PartidasController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new Partidas;
	}
}