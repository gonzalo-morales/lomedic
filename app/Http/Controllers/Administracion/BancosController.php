<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Bancos;
use App\Http\Controllers\ControllerBase;

class BancosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Bancos $entity)
	{
		$this->entity = $entity;
	}
	
	/*
	public function index($company, array $attributes = [])
	{
	    $attributes = ['where'=>['eliminar = 0','company = 1']];
	    return parent::index($company, $attributes);
	}
	*/
}