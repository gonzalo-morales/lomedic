<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Solicitudes;
use App\Http\Models\Compras\Ofertas;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\CondicionesAutorizacion;
use App\Http\Models\Compras\Autorizaciones;
use App\Http\Models\SociosNegocio\ProductosSociosNegocio;
use Carbon\Carbon;
use function foo\func;
use Milon\Barcode\DNS2D;
use Milon\Barcode\DNS1D;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\TiposEntrega;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class OrdenesController extends ControllerBase
{
	public function __construct()
	{
	    $this->entity = new Ordenes;
	}

	public function getDataView($entity = null)
    {
        switch (\request('tipo_documento')){
            case 1:
                $documento = Solicitudes::find(\request('id'));
                $detalles_documento = $documento->detalle()->where('cerrado',0)->select('*','fk_id_documento','importe as total_producto')->get();
                break;
            case 2:
                $documento = Ofertas::find(\request('id'));
                $detalles_documento = $documento->DetalleOfertas()->where('cerrado',0)->select('*','fk_id_documento')->get();
                break;
            default:
                $documento = null;
                $detalles_documento = null;
                break;
        }
        $clientes = SociosNegocio::where('activo',1)->where('fk_id_tipo_socio_venta',1)->whereHas('empresas',function ($empresa){
            $empresa->where('id_empresa',dataCompany()->id_empresa)->where('eliminar','f');
        })->pluck('nombre_comercial','id_socio_negocio');
        $proveedores = SociosNegocio::where('activo',1)->where('fk_id_tipo_socio_compra',3)->whereHas('empresas',function ($empresa){
            $empresa->where('id_empresa',dataCompany()->id_empresa)->where('eliminar','f');
        })->pluck('nombre_comercial','id_socio_negocio');
        return [
            'companies' => Empresas::where('activo',1)->where('conexion','<>',\request()->company)->where('conexion','<>','corporativo')->pluck('nombre_comercial','id_empresa'),
            'documento' =>$documento,
            'detalles_documento'=>$detalles_documento,
            'tipo_documento' => \request('tipo_documento'),
            'sucursales' 	=> Sucursales::whereHas('usuario_sucursales',
                function ($q){
                    $q->where('id_usuario',Auth::id());})
                ->whereHas('empresa_sucursales',function ($empresa){
                    $empresa->where('id_empresa',dataCompany()->id_empresa);
                })->pluck('sucursal','id_sucursal'),
            'clientes' => $clientes ?? '',
            'proveedores' => $proveedores ?? '',
            'proyectos' => Proyectos::where('fk_id_estatus',1)->pluck('proyecto','id_proyecto'),
            'tiposEntrega' => TiposEntrega::where('activo',1)->pluck('tipo_entrega','id_tipo_entrega'),
            'condicionesPago' => CondicionesPago::where('activo',1)->pluck('condicion_pago','id_condicion_pago'),
//            'js_tiempo_entrega' => Crypt::encryptString('"selectRaw":["max(tiempo_entrega) as tiempo_entrega"],"conditions":[{"whereRaw":["(fk_id_socio_negocio IS NULL OR fk_id_socio_negocio = \'$fk_id_socio_negocio\') AND fk_id_sku = \'$fk_id_sku\' AND ($fk_id_upc IS NULL OR fk_id_upc = $fk_id_upc)"]}]'),
            'js_tiempo_entrega' => Crypt::encryptString('
                "selectRaw": ["max(tiempo_entrega)"],
                "withFunction": [{
                "productos": {
                    "selectRaw": ["max(tiempo_entrega) as tiempo_entrega"],
                    "whereRaw": ["(fk_id_socio_negocio IS NULL OR fk_id_socio_negocio = \'$fk_id_socio_negocio\') AND fk_id_sku = \'$fk_id_sku\' AND ($fk_id_upc IS NULL OR fk_id_upc = $fk_id_upc)"],
                    "groupBy": ["fk_id_socio_negocio","fk_id_sku","fk_id_upc"]
                }
                }],
                "groupBy": ["fk_id_socio_negocio","fk_id_sku","fk_id_upc"]
            '),
        ];

    }

    public function index($company, $attributes=[]){
		$attributes = $attributes+['dataview'=>[
				'detalles' => $this->entity->detalle->where('cerrado',false),
				'estatus' => 1,
			]];
			return parent::index($company,$attributes);
	}

	public function create($company, $attributes =[])
	{
	    $documento = $this->getDataView()['documento'];
        $data = $this->entity->getColumnsDefaultsValues();
        if(!empty($documento) && $documento->fk_id_tipo_documento == 2){
            $data['fk_id_empresa'] = $documento->fk_id_empresa;
            $data['fk_id_sucursal'] = $documento->fk_id_sucursal;
            $attributes['data'] = $data;
        }
		 return parent::create($company,$attributes);
	}

	public function store(Request $request, $company, $compact = false)
	{
	    $return = parent::store($request,$company,$compact);

	    $datos = $return['entity'];

	    if($datos){
//            switch ($tipo_documento){
//                case 1:
//                    $solicitud = Solicitudes::find($id_documento);
//                    $solicitud->fk_id_estatus_solicitud = 2;
//                    $solicitud->save();
//                    break;
//                case 2:
//                    $oferta = Ofertas::find($id_documento);
//                    $oferta->fk_id_estatus_oferta = 2;
//                    $oferta->save();
//                    break;
//            }
        }

//        # ¿Usuario tiene permiso para crear?
//		$this->authorize('create', $this->entity);
//
//		$request->request->set('fecha_creacion',Carbon::now()->toDateString());
//
//		$request->request->set('fk_id_estatus_orden',1);
//		if(empty($request->fk_id_empresa)){
//		    $request->request->set('fk_id_empresa',Empresas::where('conexion','LIKE',$company)->first()->id_empresa);
//        }
//        if(!empty($request->importacion)){
//		    $request->request->set('importacion','t');
//        }
//
//        if(empty($request->tiempo_entrega || empty($request->fecha_estimada_entrega))){
//		    $request->request->set('tiempo_entrega',null);
//		    $request->request->set('fecha_estimada_entrega',null);
//        }
//
//        $request->request->set('fecha_cancelacion',null);
//        $request->request->set('motivo_cancelacion',null);
//
////        dd($request->request,$this->entity->rules);
//        # Validamos request, si falla regresamos pagina
//        $this->validate($request, $this->entity->rules);
//        $isSuccess = $this->entity->create($request->all());
//		if ($isSuccess) {
//		    $descuento_rows = 0;
//			if(isset($request->_detalles)) {
//				foreach ($request->_detalles as $detalle) {
//				    $descuento_rows += $detalle['descuento_detalle'];
//                    if(empty($detalle['fk_id_upc'])){
//                        $detalle['fk_id_upc'] = null;
//                    }
//                    if(empty($detalle['fk_id_cliente'])){
//                        $detalle['fk_id_cliente'] = null;
//                    }
//                    if(empty($detalle['fk_id_proyecto'])){
//                        $detalle['fk_id_proyecto'] = null;
//                    }
//                    if(empty($detalle['fecha_necesario'])){
//                        $detalle['fecha_necesario'] = null;
//                    }
//					$isSuccess->detalle()->save(new DetalleOrdenes($detalle));
//				}
//			}
//			if(isset($request->detalles)){
//			    $id_documento = 0;
//			    $tipo_documento = 0;
//			    foreach ($request->detalles as $detalle){
//                    $descuento_rows += $detalle['descuento_detalle'];
//                    $id_documento = $detalle['fk_id_documento_base'];
//                    $tipo_documento = $detalle['fk_id_tipo_documento_base'];
//                    if(empty($detalle['fk_id_upc'])){
//                        $detalle['fk_id_upc'] = null;
//                    }
//                    if(empty($detalle['fk_id_cliente'])){
//                        $detalle['fk_id_cliente'] = null;
//                    }
//                    if(empty($detalle['fk_id_proyecto'])){
//                        $detalle['fk_id_proyecto'] = null;
//                    }
//                    if(empty($detalle['fecha_necesario'])){
//                        $detalle['fecha_necesario'] = null;
//                    }
//                    $isSuccess->detalle()->save(new DetalleOrdenes($detalle));
//                }
//            }
//            $isSuccess->descuento_total = $descuento_rows + $isSuccess->descuento_general;
//			$isSuccess->save();
//
//			#$this->log('store', $isSuccess->id_documento);
//
//			// dd($isSuccess->id_documento);
//			// $this->evaluarCondiciones($request, $isSuccess->id_documento);
//
//            return $this->redirect('store');
//		} else {
//			#$this->log('error_store');
//			return $this->redirect('error_store');
//		}
	}

	public function update(Request $request, $company, $id, $compact = false)
	{
		# ¿Usuario tiene permiso para actualizar?
		$this->authorize('update', $this->entity);
        $entity = $this->entity->findOrFail($id);

        $request->request->set('fecha_creacion',$entity->fecha_creacion);

        $request->request->set('fk_id_estatus_orden',$entity->fk_id_estatus_orden);
        if(empty($request->fk_id_empresa)){
            $request->request->set('fk_id_empresa',Empresas::where('conexion','LIKE',$company)->first()->id_empresa);
        }
        if(!empty($request->importacion)){
            $request->request->set('importacion','t');
        }

        $request->request->set('fecha_cancelacion',$entity->fecha_cancelacion);
        $request->request->set('motivo_cancelacion',$entity->motivo_cancelacion);

        if(empty($request->tiempo_entrega || empty($request->fecha_estimada_entrega))){
            $request->request->set('tiempo_entrega',null);
            $request->request->set('fecha_estimada_entrega',null);
        }

		# Validamos request, si falla regresamos atras
        $this->validate($request, $this->entity->rules);
        $entity->fill($request->all());

		if ($entity->save()) {
            $descuento_rows = 0;
            if(isset($request->detalles)) {
				foreach ($request->detalles as $detalle) {
                    $orden_detalle = $entity
                        ->findOrFail($id)
                        ->detalle()
                        ->where('id_documento_detalle', $detalle['id_documento_detalle'])
                        ->first();
                    $descuento_rows += $orden_detalle->descuento_detalle;
				}
			}
			if(isset($request->_detalles)){
				foreach ($request->_detalles as $detalle){
                    $descuento_rows += $detalle['descuento_detalle'];
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
					$entity->detalle()->save(new DetalleOrdenes($detalle));
				}
			}
            $entity->descuento_total = $entity->descuento_general + $descuento_rows;
			$entity->save();
			
			#$this->log('update', $id);
			return $this->redirect('update');
		} else {
			#$this->log('error_update', $id);
			return $this->redirect('error_update');
		}
	}

    public function impress($company,$id)
    {
        $orden = Ordenes::find($id);

        $subtotal = 0;
        $iva = 0;
        $total = 0;
        foreach ($orden->detalle()->where('cerrado','f')->get() as $detalle)
        {
            $subtotal += $detalle->precio_unitario * $detalle->cantidad;
            $iva += (($detalle->precio_unitario*$detalle->cantidad)*$detalle->impuesto->porcentaje)/100;
            $total += $detalle->total;
        }
        $total = number_format($total,2,'.',',');

        $barcode = DNS1D::getBarcodePNG($orden->id_documento,'EAN8');
        $qr = DNS2D::getBarcodePNG(asset(companyAction('show',['id'=>$orden->id_documento])), "QRCODE");

        $pdf = PDF::loadView(currentRouteName('compras.ordenes.imprimir'),[
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
	    $id_empresa = \request()->fk_id_empresa > 0 ? \request()->fk_id_empresa : Empresas::where('conexion',$company)->first()->id_empresa;
	    $proveedores = SociosNegocio::where('activo',1)->whereHas('empresas',function ($q) use ($id_empresa){
            $q->where('id_empresa',$id_empresa);
        })->where('fk_id_tipo_socio_compra',3)->select('id_socio_negocio as id','nombre_comercial as text','tiempo_entrega')->get();
	    return Response::json($proveedores);
    }

    public function getDetallesOrden()
    {
        $result = DetalleOrdenes::join('inv_cat_skus','com_det_ordenes.fk_id_sku','=','inv_cat_skus.id_sku')
            ->leftJoin('maestro.inv_cat_upcs',function($join){
                $join->on('com_det_ordenes.fk_id_upc','=','maestro.inv_cat_upcs.id_upc');
            })
            ->where('fk_id_documento','=',$_POST['id_orden'])
            ->select(db::raw("concat(inv_cat_skus.sku, ' - ' ,maestro.inv_cat_upcs.upc) as value"),'com_det_ordenes.id_documento_detalle as id')
            ->get();

        return $result;
    }
}
