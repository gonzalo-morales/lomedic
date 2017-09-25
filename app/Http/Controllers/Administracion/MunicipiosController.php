<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Municipios;

class MunicipiosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Municipios $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView()
	{
		return [
//			'estados' => Estados::active()->select(['estado','id_estado'])->pluck('estado','id_estado'),
		];
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($company, $attributes = [])
	{
//		$this->loadResources();
        $attributes=$attributes+['dataview'=>[
            'estados' => Estados::select(['estado','id_estado'])->pluck('estado','id_estado'),
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
//		$this->loadResources();
        $attributes=$attributes+['dataview'=>[
                'estados' => Estados::select(['estado','id_estado'])->pluck('estado','id_estado'),
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
//		$this->loadResources();
        $attributes=$attributes+['dataview'=>[
                'estados' => Estados::select(['estado','id_estado'])->pluck('estado','id_estado'),
            ]];
		return parent::edit($company, $id, $attributes);
	}
}
