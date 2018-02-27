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
use App\Http\Models\Administracion\Jurisdicciones;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Cache;

class SucursalesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new Sucursales;
	}

	public function getDataView($entity = null)
	{
		if ($entity) {
			// dd($entity);
			return [
				'empresas' => Empresas::select('id_empresa','nombre_comercial')->where('activo',1)->where('empresa',1)->get()->sortBy('nombre_comercial'),
			    'localidades' => Localidades::select(['localidad','id_localidad'])->where('activo',1)->pluck('localidad','id_localidad'),
			    'zonas' => Zonas::select(['zona','id_zona'])->where('activo',1)->pluck('zona','id_zona'),
			    'paises' => Paises::select(['pais','id_pais'])->where('activo',1)->pluck('pais','id_pais'),
			    'estados' => Estados::select(['estado','id_estado'])->where('activo',1)->where('fk_id_pais', $entity->fk_id_pais)->pluck('estado','id_estado'),
			    'municipios' => Municipios::select(['municipio','id_municipio'])->where('activo',1)
			        ->whereHas('estado', function($q) use ($entity) {
    					$q->where('id_estado', $entity->fk_id_estado);
    					$q->whereHas('pais', function($q) use ($entity) {
    						$q->where('id_pais', $entity->fk_id_pais);
    					});
    				})->pluck('municipio','id_municipio'),
				'sucursales' => $this->entity->select(['sucursal','id_sucursal'])->where('activo',1)->pluck('sucursal','id_sucursal'),
				'tipos' => TipoSucursal::select(['tipo','id_tipo'])->where('activo',1)->pluck('tipo','id_tipo'),
				'jurisdicciones' => Jurisdicciones::select('id_jurisdiccion','jurisdiccion')->where('activo',1)->pluck('jurisdiccion','id_jurisdiccion'),
				'clientes' => SociosNegocio::select('nombre_comercial','id_socio_negocio')->where('activo',1)->whereNotNull('fk_id_tipo_socio_venta')->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
			];
		}
		return [
			'empresas' => Empresas::select('id_empresa','nombre_comercial')->where('activo',1)->where('empresa',1)->get()->sortBy('nombre_comercial'),
		    'localidades' => Localidades::select(['localidad','id_localidad'])->where('activo',1)->pluck('localidad','id_localidad'),
		    'zonas' => Zonas::select(['zona','id_zona'])->where('activo',1)->pluck('zona','id_zona'),
			'paises' => Paises::select(['pais','id_pais'])->where('activo',1)->pluck('pais','id_pais'),
			'jurisdicciones' => Jurisdicciones::select('id_jurisdiccion','jurisdiccion')->where('activo',1)->pluck('jurisdiccion','id_jurisdiccion'),
		    'sucursales' => $this->entity->select(['sucursal','id_sucursal'])->where('activo',1)->pluck('sucursal','id_sucursal'),
		    'tipos' => TipoSucursal::select(['tipo','id_tipo'])->where('activo',1)->pluck('tipo','id_tipo'),
		    'clientes' => SociosNegocio::select('nombre_comercial','id_socio_negocio')->where('activo',1)->whereNotNull('fk_id_tipo_socio_venta')->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
		];
	}

	public function obtenerSucursales($company)
	{
	    $sucursales = Sucursales::where('activo',1)->get();

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

	public function store(Request $request, $company, $compact = false)
	{
		$this->validate($request, $this->entity->rules);

		DB::beginTransaction();
		$entity = $this->entity->create($request->all());
 
		if ($entity)
		{
			$id = $entity->id_sucursal;
 
			# Guardamos el detalle de empresas en la que estara disponible
			if(isset($request->empresas)) {
				$sync = [];
				foreach ($request->empresas as $id_empresa=>$active) {
					if($active) {
						$sync[] = $id_empresa;
					}
				}
					$entity->empresas()->sync($sync);
			}
			DB::commit();
 
			# Eliminamos cache
			Cache::tags(getCacheTag('index'))->flush();
			#$this->log('store', $id);
			return $this->redirect('store');
		} else {
			DB::rollBack();
			#$this->log('error_store');
			return $this->redirect('error_store');
		}

	}
	public function update(Request $request, $company, $id, $compact = false)
	{
	    # ¿Usuario tiene permiso para actualizar?
	    #$this->authorize('update', $this->entity);

	    # Validamos request, si falla regresamos atras
	    $this->validate($request, $this->entity->rules);

	    #DB::beginTransaction();
	    $entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
		if ($entity->save())
		{
			$id = $entity->id_sucursal;
 
			# Guardamos el detalle de empresas en la que estara disponible
			if(isset($request->empresas)) {
				$sync = [];
				foreach ($request->empresas as $id_empresa=>$active) {
					if($active) {
						$sync[] = $id_empresa;
					}
				}
					$entity->empresas()->sync($sync);
			}
			DB::commit();
 
			# Eliminamos cache
			Cache::tags(getCacheTag('index'))->flush();
			#$this->log('store', $id);
			return $this->redirect('store');
		} else {
			DB::rollBack();
			#$this->log('error_store');
			return $this->redirect('error_store');
		}
	}
}