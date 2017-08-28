<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Municipios;
use Illuminate\Support\Facades\Schema;

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
		$this->estados = Estados::active()->pluck('estado','id_estado');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($company, $attributes = [])
	{
		return parent::create($company, [
			'dataview' => [
				'estados' => $this->estados,
			]
		]);
	}

	/**
	 * Display the specified resource
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($company, $id, $attributes = [])
	{
		return parent::show($company, $id, [
			'dataview' => [
				'estados' => $this->estados,
			]
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($company, $id, $attributes = [])
	{
		return parent::edit($company, $id, [
			'dataview' => [
				'estados' => $this->estados,
			]
		]);
	}
}
