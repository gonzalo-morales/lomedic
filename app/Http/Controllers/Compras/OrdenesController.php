<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Unidadesmedidas;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\SociosNegocio\TiposEntrega;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class OrdenesController extends ControllerBase
{
	public function __construct(Ordenes $entity)
	{
		$this->entity = $entity;
	}

	public function index($company, $attributes = [])
	{
		$attributes = ['where'=>[]];
		return parent::index($company, $attributes);
	}

	public function create($company, $attributes =[])
	{
	    $clientes = SociosNegocio::where('activo', 1)->whereHas('tipoSocio', function($q) {
	        $q->where('fk_id_tipo_socio', 1);
        })->get()->pluck('nombre_corto','id_socio_negocio');

        $attributes = $attributes+['dataview'=>[
                'companies' => Empresas::where('activo',1)->where('conexion','<>',$company)->where('conexion','<>','corporativo')->get()->pluck('nombre_comercial','id_empresa'),
                'sucursales' => Sucursales::where('activo',1)->get()->pluck('sucursal','id_sucursal'),
                'clientes' => $clientes,
                'proyectos' => Proyectos::where('activo',1)->get()->pluck('proyecto','id_proyecto'),
                'tiposEntrega' => TiposEntrega::where('activo',1)->get()->pluck('tipo_entrega','id_tipo_entrega'),
                'condicionesPago' => CondicionesPago::where('activo',1)->get()->pluck('condicion_pago','id_condicion_pago'),
            ]];
		 return parent::create($company,$attributes);
	}

	public function store(Request $request, $company)
	{
        # ¿Usuario tiene permiso para crear?
		$this->authorize('create', $this->entity);


		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules);

		$request->request->set('fecha_creacion',DB::raw('now()'));

		$request->request->set('fk_id_estatus_orden',1);
		if(empty($request->fk_id_empresa)){
		    $request->request->set('fk_id_empresa',Empresas::where('conexion','LIKE',$company)->first()->id_empresa);
        }

        $isSuccess = $this->entity->create($request->all());
		if ($isSuccess) {
			if(isset($request->_detalles)) {
				foreach ($request->_detalles as $detalle) {
                    if(empty($detalle['fk_id_upc'])){
                        $detalle['fk_id_upc'] = null;
                    }
                    if(empty($detalle['fk_id_cliente'])){
                        $detalle['fk_id_cliente'] = null;
                    }
                    if(empty($detalle['fk_id_proyecto'])){
                        $detalle['fk_id_proyecto'] = null;
                    }
                    if(empty($detalle['fecha_necesario'])){
                        $detalle['fecha_necesario'] = null;
                    }
					$isSuccess->detalleOrdenes()->save(new DetalleOrdenes($detalle));
				}
				$this->log('store', $isSuccess->id_solicitud);
			}
			return $this->redirect('store');
		} else {
			$this->log('error_store');
			return $this->redirect('error_store');
		}
	}

	public function show($company,$id,$attributes = [])
	{
        $proveedores = SociosNegocio::where('activo', 1)->whereHas('tipoSocio', function($q) {
            $q->where('fk_id_tipo_socio', 2);
        })->get()->pluck('nombre_corto','id_socio_negocio');
		$attributes = $attributes+['dataview'=>[
                'companies' => Empresas::where('activo',1)->where('conexion','<>',$company)->where('conexion','<>','corporativo')->get()->pluck('nombre_comercial','id_empresa'),
                'sucursales' => Sucursales::where('activo',1)->get()->pluck('sucursal','id_sucursal'),
                'proveedores' => $proveedores,
                'proyectos' => Proyectos::where('activo',1)->get()->pluck('proyecto','id_proyecto'),
                'tiposEntrega' => TiposEntrega::where('activo',1)->get()->pluck('tipo_entrega','id_tipo_entrega'),
                'condicionesPago' => CondicionesPago::where('activo',1)->get()->pluck('condicion_pago','id_condicion_pago'),
			]];
		return parent::show($company,$id,$attributes);
	}

	public function edit($company,$id,$attributes = [])
	{
        $clientes = SociosNegocio::where('activo', 1)->whereHas('tipoSocio', function($q) {
            $q->where('fk_id_tipo_socio', 1);
        })->get()->pluck('nombre_corto','id_socio_negocio');

		$attributes = $attributes+['dataview'=>[
                'companies' => Empresas::where('activo',1)->where('conexion','<>',$company)->where('conexion','<>','corporativo')->get()->pluck('nombre_comercial','id_empresa'),
                'sucursales' => Sucursales::where('activo',1)->get()->pluck('sucursal','id_sucursal'),
                'clientes' => $clientes,
                'proyectos' => Proyectos::where('activo',1)->get()->pluck('proyecto','id_proyecto'),
                'tiposEntrega' => TiposEntrega::where('activo',1)->get()->pluck('tipo_entrega','id_tipo_entrega'),
                'condicionesPago' => CondicionesPago::where('activo',1)->get()->pluck('condicion_pago','id_condicion_pago'),
			]];
		return parent::edit($company, $id, $attributes);
	}

	public function update(Request $request, $company, $id)
	{
//        dd($request->request);
		# ¿Usuario tiene permiso para actualizar?
		$this->authorize('update', $this->entity);

		# Validamos request, si falla regresamos atras
		$this->validate($request, $this->entity->rules);

		$entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
		if ($entity->save()) {
			if(isset($request->detalles)) {
				foreach ($request->detalles as $detalle) {
						$solicitud_detalle = $entity
							->findOrFail($id)
							->detalleSolicitudes()
							->where('id_solicitud_detalle', $detalle['id_solicitud_detalle'])
							->first();
						$solicitud_detalle->fill($detalle);
						$solicitud_detalle->save();
				}
			}
			if(isset($request->_detalles)){
				foreach ($request->_detalles as $detalle){
					$entity->detalleSolicitudes()->save(new DetalleSolicitudes($detalle));
				}
			}

			$this->log('update', $id);
			return $this->redirect('update');
		} else {
			$this->log('error_update', $id);
			return $this->redirect('error_update');
		}
	}

	public function destroy(Request $request, $company, $idOrIds)
	{
		if (!is_array($idOrIds)) {

			$isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)
				->update(['fk_id_estatus_solicitud' => 3,
					'motivo_cancelacion'=>$request->motivo_cancelacion,
					'fecha_cancelacion'=>DB::raw('now()')]);
			if ($isSuccess) {

				$this->log('destroy', $idOrIds);

				if ($request->ajax()) {
					# Respuesta Json
					return response()->json([
						'success' => true,
					]);
				} else {
					return $this->redirect('destroy');
				}

			} else {

				$this->log('error_destroy', $idOrIds);

				if ($request->ajax()) {
					# Respuesta Json
					return response()->json([
						'success' => false,
					]);
				} else {
					return $this->redirect('error_destroy');
				}
			}

			# Multiple
		} else {

			$isSuccess = $this->entity->whereIn($this->entity->getKeyName(), $idOrIds)
				->update(['fk_id_estatus_solicitud' => 3,
					'motivo_cancelacion'=>$request->motivo_cancelacion,
					'fecha_cancelacion'=>DB::raw('now()')]);
			if ($isSuccess) {

				# Shorthand
				foreach ($idOrIds as $id) $this->log('destroy', $id);

				if ($request->ajax()) {
					# Respuesta Json
					return response()->json([
						'success' => true,
					]);
				} else {
					return $this->redirect('destroy');
				}

			} else {

				# Shorthand
				foreach ($idOrIds as $id) $this->log('error_destroy', $id);

				if ($request->ajax()) {
					# Respuesta Json
					return response()->json([
						'success' => false,
					]);
				} else {
					return $this->redirect('error_destroy');
				}
			}
		}
	}

	public function print_solicitud($company,$id)
	{
//        $numero = 123456.50;

//        dd(\NumeroALetras::convertir(number_format($numero,2,',',''),'pesos'));
//        dd(PDF::loadView(currentRouteName('imprimir')));
		$pdf = PDF::loadView(currentRouteName('imprimir'));
		$pdf->setPaper('letter','landscape');


		return $pdf->stream('solicitud')->header('Content-Type',"application/pdf");
//        return view(currentRouteName('imprimir'));
	}

	public function getProveedores($company){
	    $proveedores = SociosNegocio::where('activo', 1)->whereHas('tipoSocio', function($q) {
            $q->where('fk_id_tipo_socio', 1);
        })->select('id_socio_negocio as id','nombre_corto as text','tiempo_entrega')->get();
	    return Response::json($proveedores);
    }
}
