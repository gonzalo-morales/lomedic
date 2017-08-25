<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Correos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Usuarios;

class CorreosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Correos $entity)
	{
		$this->entity = $entity;
		$this->companies = Empresas::active()->pluck('nombre_comercial','id_empresa');
		$this->users = Usuarios::active()->pluck('nombre_corto','id_usuario');
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
				'companies' => $this->companies,
				'users' => $this->users
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
				'companies' => $this->companies,
				'users' => $this->users
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
				'companies' => $this->companies,
				'users' => $this->users
			]
		]);
	}
}
