<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Solicitudes;
use App\Http\Models\Compras\Ofertas;
use App\Http\Models\Compras\Ordenes;
use Milon\Barcode\DNS2D;
use Milon\Barcode\DNS1D;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\SociosNegocio\TiposEntrega;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

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
        switch (\request('tipo_documento')){
            case 1:
                $documento = Solicitudes::find(\request('id'));
                $detalles_documento = $documento->detalleSolicitudes()->select('*','fk_id_solicitud as fk_id_documento')->get();
                break;
            case 2:
                $documento = Ofertas::find(\request('id'));
                $detalles_documento = $documento->DetalleOfertas()->select('*','fk_id_oferta as fk_id_documento')->get();
                break;
            case 3:
                $documento = Ofertas::find(\request('id'));
                $detalles_documento = $documento->DetalleOfertas()->get();
                break;
            default:
                $documento = null;
                $detalles_documento = null;
                break;
        }

	    $clientes = SociosNegocio::where('activo', 1)->whereHas('tipoSocio', function($q) {
	        $q->where('fk_id_tipo_socio', 1);
        })->pluck('nombre_corto','id_socio_negocio');

        $attributes = ['dataview'=>[
            'companies' => Empresas::where('activo',1)->where('conexion','<>',$company)->where('conexion','<>','corporativo')->pluck('nombre_comercial','id_empresa'),
            'documento' =>$documento,
            'detalles_documento'=>$detalles_documento,
            'tipo_documento' => \request('tipo_documento'),
            'sucursales' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal'),
            'clientes' => $clientes,
            'proyectos' => Proyectos::where('activo',1)->pluck('proyecto','id_proyecto'),
            'tiposEntrega' => TiposEntrega::where('activo',1)->pluck('tipo_entrega','id_tipo_entrega'),
            'condicionesPago' => CondicionesPago::where('activo',1)->pluck('condicion_pago','id_condicion_pago'),
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

        if(!empty($request->importacion)){
		    $request->request->set('importacion','t');
        }
//        dd($request->request);
        $now = DB::raw('now()');
        $request->request->set('fecha_estimada_entrega',DB::raw("date '$now' + integer '$request->tiempo_entrega'"));
//        dd($request->request);
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
			}
			if(isset($request->detalles)){
			    $id_documento = 0;
			    $tipo_documento = 0;
			    foreach ($request->detalles as $detalle){
                    $id_documento = $detalle['fk_id_documento'];
                    $tipo_documento = $detalle['fk_id_tipo_documento'];
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
                switch ($tipo_documento){
                    case 1:
                        $solicitud = Solicitudes::where('id_solicitud',$request->id_solicitud)->first();
                        $solicitud->fk_id_estatus_solicitud = 2;
                        $solicitud->save();
                        break;
                    case 2:
                        $oferta = Ofertas::where('id_oferta',$id_documento)->first();
                        $oferta->fk_id_estatus_oferta = 2;
                        $oferta->save();
                        break;
			    }
            }
            $this->log('store', $isSuccess->id_orden);
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
        })->pluck('nombre_corto','id_socio_negocio');
		$attributes = $attributes+['dataview'=>[
                'companies' => Empresas::where('activo',1)->where('conexion','<>','corporativo')->pluck('nombre_comercial','id_empresa'),
                'sucursales' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal'),
                'proveedores' => $proveedores,
                'proyectos' => Proyectos::where('activo',1)->pluck('proyecto','id_proyecto'),
                'tiposEntrega' => TiposEntrega::where('activo',1)->pluck('tipo_entrega','id_tipo_entrega'),
                'condicionesPago' => CondicionesPago::where('activo',1)->pluck('condicion_pago','id_condicion_pago'),
			]];
		return parent::show($company,$id,$attributes);
	}

	public function edit($company,$id,$attributes = [])
	{
        $clientes = SociosNegocio::where('activo', 1)->whereHas('tipoSocio', function($q) {
            $q->where('fk_id_tipo_socio', 1);
        })->pluck('nombre_corto','id_socio_negocio');

		$attributes = $attributes+['dataview'=>[
                'companies' => Empresas::where('activo',1)->where('conexion','<>','corporativo')->pluck('nombre_comercial','id_empresa'),
                'sucursales' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal'),
                'clientes' => $clientes,
                'proyectos' => Proyectos::where('activo',1)->pluck('proyecto','id_proyecto'),
                'tiposEntrega' => TiposEntrega::where('activo',1)->pluck('tipo_entrega','id_tipo_entrega'),
                'condicionesPago' => CondicionesPago::where('activo',1)->pluck('condicion_pago','id_condicion_pago'),
			]];
		return parent::edit($company, $id, $attributes);
	}

	public function update(Request $request, $company, $id)
	{
		# ¿Usuario tiene permiso para actualizar?
		$this->authorize('update', $this->entity);

		# Validamos request, si falla regresamos atras
		$this->validate($request, $this->entity->rules);
		$entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
//		dd($entity);
		if ($entity->save()) {
			if(isset($request->detalles)) {
				foreach ($request->detalles as $detalle) {
						$orden_detalle = $entity
							->findOrFail($id)
							->detalleOrdenes()
							->where('id_orden_detalle', $detalle['id_orden_detalle'])
							->first();
						$orden_detalle->fill($detalle);
						$orden_detalle->save();
				}
			}
			if(isset($request->_detalles)){
				foreach ($request->_detalles as $detalle){
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
					$entity->detalleOrdenes()->save(new DetalleOrdenes($detalle));
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
	    if($request->url() != companyAction('Compras\OrdenesController@destroyDetail')){
            if (!is_array($idOrIds)) {

                $isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)
                    ->update(['fk_id_estatus_orden' => 3,
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
                    ->update(['fk_id_estatus_orden' => 3,
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
        }else{
            DetalleOrdenes::whereIn('id_orden_detalle', $request->ids)->update(['cerrado' => 't']);
            return true;
        }
	}

    public function impress($company,$id)
    {
        $orden = Ordenes::find($id);
//        dd($orden->detalleOrdenes()->where('cerrado','f')->get());

        $subtotal = 0;
        $iva = 0;
        $total = 0;
        foreach ($orden->detalleOrdenes()->where('cerrado','f')->get() as $detalle)
        {
            $subtotal += $detalle->precio_unitario * $detalle->cantidad;
            $iva += (($detalle->precio_unitario*$detalle->cantidad)*$detalle->impuesto->porcentaje)/100;
            $total += $detalle->total;
        }
        $total = number_format($total,2,'.',',');

        $barcode = DNS1D::getBarcodePNG($orden->id_orden,'EAN8');
        $qr = DNS2D::getBarcodePNG(asset(companyAction('show',['id'=>$orden->id_orden])), "QRCODE");

        $pdf = PDF::loadView(currentRouteName('imprimir'),[
            'orden' => $orden,
//            'detalles' => $detalles,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'total_letra' => num2letras($total),
            'barcode' => $barcode,
            'qr' => $qr
        ]);

        $pdf->setPaper('letter','landscape');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(38,580,"Página {PAGE_NUM} de {PAGE_COUNT}",null,8,array(0,0,0));
        $canvas->text(665,580,'PSAI-PN06-F01 Rev. 01',null,8);
//        $canvas->image('data:image/png;charset=binary;base64,'.$barcode,355,580,100,16);

        return $pdf->stream('orden')->header('Content-Type',"application/pdf");
//        return view(currentRouteName('imprimir'));
    }

    public function getProveedores($company){
	    $proveedores = SociosNegocio::where('activo', 1)->whereHas('tipoSocio', function($q) {
            $q->where('fk_id_tipo_socio', 2);
        })->select('id_socio_negocio as id','nombre_corto as text','tiempo_entrega')->get();
	    return Response::json($proveedores);
    }

    public function createSolicitudOrden($company,$id){

        $data = $this->entity->getColumnsDefaultsValues();
        $validator = \JsValidator::make($this->entity->rules, [], $this->entity->niceNames, '#form-model');
        $clientes = SociosNegocio::where('activo', 1)->whereHas('tipoSocio', function($q) {
            $q->where('fk_id_tipo_socio', 1);
        })->pluck('nombre_corto','id_socio_negocio');
        $attributes = ['dataview'=>[
            'companies' => Empresas::where('activo',1)->where('conexion','<>',$company)->where('conexion','<>','corporativo')->pluck('nombre_comercial','id_empresa'),
            'sucursales' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal'),
            'clientes' => $clientes,
            'proyectos' => Proyectos::where('activo',1)->pluck('proyecto','id_proyecto'),
            'tiposEntrega' => TiposEntrega::where('activo',1)->pluck('tipo_entrega','id_tipo_entrega'),
            'condicionesPago' => CondicionesPago::where('activo',1)->pluck('condicion_pago','id_condicion_pago'),
            'solicitud'=>Solicitudes::where('id_solicitud',$id)->first(),
            'detalleSolicitud' => DetalleSolicitudes::where('fk_id_solicitud',$id)->get(),
        ]];
//        dd(currentRouteName('solicitudOrden'));
        return view(currentRouteName('solicitudOrden'),($attributes['dataview'] ?? []) + [
            'data' => $data,
            'validator' => $validator
            ]+ $this->getDataView());
    }
}
