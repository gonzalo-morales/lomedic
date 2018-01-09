<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Localidades;
use App\Http\Models\Administracion\Municipios;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TipoSucursal;
use App\Http\Models\Administracion\Zonas;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\Empresas;

class SucursalesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Sucursales $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
	{
		if ($entity) {
			return [
				'localidades' => Localidades::select(['localidad','id_localidad'])->where('activo', 1)->pluck('localidad','id_localidad'),
				'zonas' => Zonas::select(['zona','id_zona'])->where('activo', 1)->pluck('zona','id_zona'),
				'paises' => Paises::select(['pais','id_pais'])->where('activo', 1)->pluck('pais','id_pais'),
				'estados' => Estados::select(['estado','id_estado'])->where('activo', 1)->where('fk_id_pais', $entity->fk_id_pais)->pluck('estado','id_estado'),
				'municipios' => Municipios::select(['municipio','id_municipio'])->where('activo', 1)->whereHas('estado', function($q) use ($entity) {
									$q->where('id_estado', $entity->fk_id_estado);
									$q->whereHas('pais', function($q) use ($entity) {
										$q->where('id_pais', $entity->fk_id_pais);
									});
								})->pluck('municipio','id_municipio'),
				'sucursales' => $this->entity->select(['sucursal','id_sucursal'])->where('activo', 1)->pluck('sucursal','id_sucursal'),
				'tipos' => TipoSucursal::select(['tipo','id_tipo'])->where('activo', 1)->pluck('tipo','id_tipo'),
				'clientes' => SociosNegocio::select('nombre_comercial','id_socio_negocio')->where('eliminar',0)->where('activo',1)->whereNotNull('fk_id_tipo_socio_venta')->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
			];
		}
		return [
			'localidades' => Localidades::select(['localidad','id_localidad'])->where('activo', 1)->pluck('localidad','id_localidad'),
			'zonas' => Zonas::select(['zona','id_zona'])->where('activo', 1)->pluck('zona','id_zona'),
			'paises' => Paises::select(['pais','id_pais'])->where('activo', 1)->pluck('pais','id_pais'),
			'sucursales' => $this->entity->select(['sucursal','id_sucursal'])->where('activo', 1)->pluck('sucursal','id_sucursal'),
			'tipos' => TipoSucursal::select(['tipo','id_tipo'])->where('activo', 1)->pluck('tipo','id_tipo'),
		    'clientes' => SociosNegocio::select('nombre_comercial','id_socio_negocio')->where('eliminar',0)->where('activo',1)->whereNotNull('fk_id_tipo_socio_venta')->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
		];
	}

	public function obtenerSucursales($company)
	{
		$sucursales = Sucursales::all()->where('activo','1');

		foreach($sucursales as $sucursal){
			$sucursal_data['id'] = (int)$sucursal->id_sucursal;
			$sucursal_data['text'] = $sucursal->nombre_sucursal;
			$sucursal_set[] = $sucursal_data;
		}
		return Response::json($sucursal_set);
	}

	public function sucursalesEmpleado($company,$id)
	{
		return Empleados::where('id_empleado',$id)->first()->sucursales()->select('id_sucursal as id','sucursal as text')->get()->toJson();
	}
}