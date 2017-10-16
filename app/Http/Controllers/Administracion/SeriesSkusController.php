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
    public function __construct(SeriesSkus $entity)
	{
		$this->entity = $entity;
	}
	
	public function index($company, $attributes = [])
	{
	    return parent::index($company,$attributes);
	}
	
	public function getSerie($company, $id)
	{
	    return $this->entity->select('prefijo','numero_siguiente','sufijo')->findOrFail($id)->toJson();
	}
}