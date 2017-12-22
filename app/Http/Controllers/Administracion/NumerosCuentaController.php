<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\NumerosCuenta;

class NumerosCuentaController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(NumerosCuenta $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
	{
		return [
			'companies' => Empresas::active()->select(['razon_social','id_empresa'])->orderBy('razon_social')->pluck('razon_social','id_empresa'),
		    'bancos' => Bancos::select(['razon_social','id_banco'])->orderBy('razon_social')->pluck('banco','id_banco'),
		    'monedas' => Monedas::select(['descripcion','id_moneda'])->orderBy('descripcion')->pluck('descripcion','id_moneda')
		];
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($company, $attributes = [])
	{
		return parent::create($company, [
			'dataview' => []
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
			'dataview' => []
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
			'dataview' => []
		]);
	}
}