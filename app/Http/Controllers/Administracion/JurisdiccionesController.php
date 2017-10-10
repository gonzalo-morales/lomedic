<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Jurisdicciones;

class JurisdiccionesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Jurisdicciones $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
	{
		return [
			'states' => Estados::all(),
		];
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($company, $attributes = [])
	{
	    $attributes = $attributes + ['dataview'=>[
	        'states'=>Estados::where('activo',1)->pluck('estado','id_estado')
            ]];

		return parent::create($company, $attributes);
	}

	/**
	 * Display the specified resource
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($company, $id, $attributes = [])
	{
        $attributes = $attributes + ['dataview'=>[
                'states'=>Estados::where('activo',1)->pluck('estado','id_estado')
            ]];
		return parent::show($company, $id, $attributes);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($company, $id, $attributes = [])
	{
        $attributes = $attributes + ['dataview'=>[
                'states'=>Estados::where('activo',1)->pluck('estado','id_estado')
            ]];
		return parent::edit($company, $id, $attributes);
	}
}
