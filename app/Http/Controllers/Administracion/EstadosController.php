<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Paises;

class EstadosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Estados $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView()
	{
		return [
			'paises' => Paises::select(['id_pais', 'pais'])->orderBy('pais')->pluck('pais','id_pais'),
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
		return parent::create($company, [
			'dataview' => $this->getDataView()
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
//		$this->loadResources();
		return parent::show($company, $id, [
			'dataview' => $this->getDataView()
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
//		$this->loadResources();
		return parent::edit($company, $id, [
			'dataview' => $this->getDataView()
		]);
	}
}
