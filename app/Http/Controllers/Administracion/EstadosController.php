<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
		$this->paises = Paises::orderBy('pais')->get()->pluck('pais','id_pais');
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
				'paises' => $this->paises,
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
				'paises' => $this->paises,
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
				'paises' => $this->paises,
			]
		]);
	}
}
