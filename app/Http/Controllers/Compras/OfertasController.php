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
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Administracion\Impuestos;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;

class OfertasController extends ControllerBase
{
	public function __construct()
	{
		$this->entity = new Ofertas;
	}
	
	public function getDataView($entity = null)
	{
	    return [
            'sucursales' 	   => Sucursales::hasEmpresa()->hasUsuario()->isActivo()->pluck('sucursal','id_sucursal'),
	        // 'companies'        => Empresas::where('activo',1)->where('conexion','<>',request()->company)->where('conexion','<>','corporativo')->where('activo',1)->pluck('nombre_comercial','id_empresa'),
            // 'actual_company_id'=> Empresas::where('conexion','LIKE',request()->company)->first()->id_empresa,
	        'monedas'          => Monedas::where('activo',1)->select('id_moneda',DB::raw("concat(descripcion,' (',moneda,')') as moneda"))->pluck('moneda','id_moneda'),
	        'unidadesmedidas'  => UnidadesMedidas::where('activo',1)->pluck('nombre','id_unidad_medida'),
            "solicitud"        => Solicitudes::find(\request()->id_solicitud),
	        "proveedores"      => SociosNegocio::where('activo',1)->where('fk_id_tipo_socio_compra',3)->whereHas('empresas',function ($empresa){
                $empresa->where('id_empresa',dataCompany()->id_empresa)->where('eliminar','f');
            })->pluck('nombre_comercial','id_socio_negocio'),
            'js_proyectos'=>Crypt::encryptString('
                "select":["id_proyecto as id","proyecto as text"],
                "whereHas": [{
                    "productos": {
                        "cwhereHas": [{
                            "claveClienteProducto": [{
                                "whereRaw": "($fk_id_sku = NULL OR fk_id_sku = $fk_id_sku) AND ($fk_id_upc = NULL OR fk_id_upc = $fk_id_upc)"
                            }]
                        }]
                    }
                }]
            '),
            'js_porcentaje'    => Crypt::encryptString('"select": ["tasa_o_cuota"], "conditions": [{"where":["id_impuesto", "$id_impuesto"]}], "limit": "1"'),
            'js_tiempo_entrega' => Crypt::encryptString('
                "selectRaw": ["max(tiempo_entrega) as tiempo_entrega"],
                "withFunction": [{
                "productos": {
                    "selectRaw": ["max(tiempo_entrega) as tiempo_entrega"],
                    "whereRaw": ["($fk_id_socio_negocio = NULL OR fk_id_socio_negocio = $fk_id_socio_negocio) AND fk_id_sku = $fk_id_sku AND ($fk_id_upc = NULL OR fk_id_upc = $fk_id_upc)"],
                    "groupBy": ["fk_id_socio_negocio","fk_id_sku","fk_id_upc"]
                }
                }],
                "groupBy": ["fk_id_socio_negocio","fk_id_sku","fk_id_upc"]'),
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

        return parent::store($request,$company,$compact);
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

        return parent::update($request,$company,$id,$compact);
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

        $barcode = DNS1D::getBarcodePNG($oferta->id_documento,'EAN8');
        $qr = DNS2D::getBarcodePNG(asset(companyAction('show',['id'=>$oferta->id_documento])), "QRCODE");
        $pdf = PDF::loadView(currentRouteName('compras.ofertas.imprimir'),[
            'oferta' => $oferta,
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
