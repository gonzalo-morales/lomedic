<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\Compras\DetalleOfertas;
use App\Http\Models\Compras\Ofertas;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\Solicitudes;
use Carbon\Carbon;
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
use Illuminate\Support\Facades\Crypt;

class OfertasController extends ControllerBase
{
	public function __construct(Ofertas $entity)
	{
		$this->entity = $entity;
	}
	
	public function getDataView($entity = null)
	{
	    return [
	        'companies' => Empresas::where('activo',1)->where('conexion','<>',request()->company)->where('conexion','<>','corporativo')->where('activo',1)->pluck('nombre_comercial','id_empresa'),
            'actual_company_id'=>Empresas::where('conexion','LIKE',request()->company)->first()->id_empresa,
	        'sucursales' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal'),
	        'monedas'=>Monedas::where('activo',1)->select('id_moneda',DB::raw("concat(descripcion,' (',moneda,')') as moneda"))->pluck('moneda','id_moneda'),
//            'proyectos' => !empty($entity) ? Proyectos::where('fk_id_estatus',1)->pluck('proyecto','id_proyecto')->prepend('Sin proyecto','0') : ['0'=>'Sin proyecto'],
	        'unidadesmedidas'=>UnidadesMedidas::where('activo',1)->pluck('nombre','id_unidad_medida'),
            "solicitud"=>Solicitudes::find(\request()->id_solicitud),
	        "proveedores"=>SociosNegocio::where('activo',1)->whereHas('empresas',function ($q){
                $q->where('conexion',\request()->company);
            })->whereNotNull('fk_id_tipo_socio_compra')->pluck('nombre_comercial','id_socio_negocio'),
            "clientes"=>SociosNegocio::where('activo',1)->whereHas('empresas',function ($q){
                $q->where('conexion',\request()->company);
            })->where('fk_id_tipo_socio_venta',1)->pluck('nombre_comercial','id_socio_negocio'),
            'js_proyectos' => Crypt::encryptString('"select":["id_proyecto as id","proyecto as text"],"conditions":[{"where":["fk_id_estatus",1]},{"where":["fk_id_cliente",$fk_id_cliente]}]'),
            'js_tiempo_entrega' => Crypt::encryptString('"selectRaw":["max(tiempo_entrega) as tiempo_entrega"],"conditions":[{"whereRaw":["(fk_id_socio_negocio IS NULL OR fk_id_socio_negocio = \'$fk_id_socio_negocio\') AND fk_id_sku = \'$fk_id_sku\' AND ($fk_id_upc IS NULL OR fk_id_upc = $fk_id_upc)"]}]')
	    ];
	}

	public function store(Request $request, $company, $compact = false)
	{
        # ¿Usuario tiene permiso para crear?
		$this->authorize('create', $this->entity);

		$request->request->set('fk_id_estatus_oferta',1);
		if(empty($request->fk_id_empresa)){
		    $request->request->set('fk_id_empresa',Empresas::where('conexion','LIKE',$company)->first()->id_empresa);
        }
        if(empty($request->descuento_oferta)){
		    $request->request->set('descuento_oferta',0);
        }
        if($request->fk_id_proyecto == 0){
		    $request->fk_id_proyecto = null;
        }
        $request->request->set('fecha_creacion',Carbon::now()->toDateString());

        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

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
//                    dd($detalle);
                    $detalle_oferta = new DetalleOfertas($detalle);
//                    dd($detalle_oferta);
					$isSuccess->DetalleOfertas()->save($detalle_oferta);
				}
			}
			if(isset($request->detalles)){
			    foreach ($request->detalles as $detalle){
                    if(empty($detalle['fk_id_upc'])){
                        $detalle['fk_id_upc'] = null;
                    }
                    if(empty($detalle['fk_id_cliente'])){
                        $detalle['fk_id_cliente'] = null;
                    }
                    if(empty($detalle['fk_id_proyecto'])){
                        $detalle['fk_id_proyecto'] = null;
                    }
                    $detalle_oferta = new DetalleOfertas($detalle);
                    $isSuccess->DetalleOfertas()->save($detalle_oferta);
                }
            }
            #$this->log('store', $isSuccess->id_documento);
            return $this->redirect('store');
		} else {
			#$this->log('error_store');
			return $this->redirect('error_store');
		}
	}

	public function update(Request $request, $company, $id, $compact = false)
	{
		# ¿Usuario tiene permiso para actualizar?
		$this->authorize('update', $this->entity);

		$entity = $this->entity->findOrFail($id);

		if($request->fk_id_empresa == 0){
		    $request->request->set('fk_id_empresa',$entity->fk_id_empresa);
        }
        if($request->fk_id_cliente == 0){
            $request->request->set('fk_id_cliente',$entity->fk_id_cliente);
        }

        $request->request->set('fk_id_estatus_oferta',$entity->fk_id_estatus_oferta);
        $request->request->set('fecha_creacion',$entity->fecha_creacion);
//        dd($request->request,$this->entity->rules);
        # Validamos request, si falla regresamos atras
        $this->validate($request, $this->entity->rules);

        $entity->fill($request->all());
		if ($entity->save()) {
			if(isset($request->detalles)) {
				foreach ($request->detalles as $detalle) {
						$oferta_detalle = $entity
							->findOrFail($id)
							->DetalleOfertas()
							->where('id_documento_detalle', $detalle['id_documento_detalle'])
							->first();
						$oferta_detalle->fill($detalle);
						$oferta_detalle->save();
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
					$entity->DetalleOfertas()->save(new DetalleOfertas($detalle));
				}
			}

			#$this->log('update', $id);
			return $this->redirect('update');
		} else {
			#$this->log('error_update', $id);
			return $this->redirect('error_update');
		}
	}

	public function destroy(Request $request, $company, $idOrIds, $attributes = [])
	{
        if(!isset($request->ids)){
            if (!is_array($idOrIds)) {

                $isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)
                    ->update(['fk_id_estatus_oferta' => 3]);
                if ($isSuccess) {

                    #$this->log('destroy', $idOrIds);

                    if ($request->ajax()) {
                        # Respuesta Json
                        return response()->json([
                            'success' => true,
                        ]);
                    } else {
                        return $this->redirect('destroy');
                    }

                } else {

                    #$this->log('error_destroy', $idOrIds);

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
                    ->update(['fk_id_estatus_oferta' => 3]);
                if ($isSuccess) {

                    # Shorthand
                    #foreach ($idOrIds as $id) $this->log('destroy', $id);

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
                    #foreach ($idOrIds as $id) $this->log('error_destroy', $id);

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
            DetalleOfertas::whereIn('id_documento_detalle', $request->ids)->update(['cerrado' => 't']);
            return 'Eliminado con éxito';
        }
	}

    public function impress($company,$id)
    {
        $oferta = Ofertas::find($id);

        $subtotal = 0;
        $iva = 0;
        $total = 0;

        foreach ($oferta->DetalleOfertas()->where('cerrado',0)->get() as $detalle)
        {
            $subtotal += $detalle->precio_unitario * $detalle->cantidad;
            $iva += (($detalle->precio_unitario*$detalle->cantidad)*$detalle->impuesto->porcentaje)/100;
            $total += $detalle->total_producto;
        }
        $total = number_format($total,2,'.',',');

        $barcode = DNS1D::getBarcodePNG($oferta->id_documento,'EAN8');
        $qr = DNS2D::getBarcodePNG(asset(companyAction('show',['id'=>$oferta->id_documento])), "QRCODE");
        $pdf = PDF::loadView(currentRouteName('compras.ofertas.imprimir'),[
            'oferta' => $oferta,
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
//        $canvas->text(665,580,'PSAI-PN06-F01 Rev. 01',null,8);
//        $canvas->image('data:image/png;charset=binary;base64,'.$barcode,355,580,100,16);

        return $pdf->stream('oferta')->header('Content-Type',"application/pdf");
//        return view(currentRouteName('imprimir'));
    }

    public function getProveedores($company){
        $proveedores = SociosNegocio::where('activo',1)->whereNotNull('fk_id_tipo_socio_compra')->select('id_socio_negocio as id','nombre_comercial as text','tiempo_entrega')->get();
	    return Response::json($proveedores);
    }
}
