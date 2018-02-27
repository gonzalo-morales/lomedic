<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\SeriesSkus;

class SeriesSkusController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct()
	{
	    $this->entity = new SeriesSkus;
	}
	
	public function getSerie($company, $id)
	{
	    return $this->entity->select('prefijo','numero_siguiente','sufijo')->whereRaw('ultimo_numero < numero_siguiente OR ultimo_numero is null')->findOrFail($id)->toJson();
	}
}