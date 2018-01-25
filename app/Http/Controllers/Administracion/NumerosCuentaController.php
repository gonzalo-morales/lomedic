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
		    'companies' => Empresas::where('activo',1)->select(['razon_social','id_empresa'])->orderBy('razon_social')->pluck('razon_social','id_empresa'),
		    'bancos' => Bancos::select(['razon_social','id_banco'])->orderBy('razon_social')->pluck('razon_social','id_banco'),
		    'monedas' => Monedas::select(['descripcion','id_moneda'])->orderBy('descripcion')->pluck('descripcion','id_moneda'),
		];
	}
}