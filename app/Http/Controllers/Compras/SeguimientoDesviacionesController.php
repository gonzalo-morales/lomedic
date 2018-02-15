<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\SeguimientoDesviacion;
use App\Http\Models\Compras\DetalleSeguimientoDesviacion;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Compras\FacturasProveedores;
// use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeguimientoDesviacionesController extends ControllerBase
{
    public function __construct(SeguimientoDesviacion $entity)
    {
        $this->entity = $entity;
    }

    // public function edit($company, Request $request){
    //     dd($request);
    // }
    public function getDataView($entity = null)
    {
        return [
            'proveedores'       => SociosNegocio::where('activo',1)->where('eliminar',0)->pluck('nombre_comercial','id_socio_negocio')->prepend('Selecciona una opcion...', ''),
            'detalleDesviacion' => DetalleSeguimientoDesviacion::all(),
            // 'localidades' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo', 1)->pluck('sucursal', 'id_sucursal')->prepend('Selecciona una opcion...', ''),

            // 'proveedores'       => SociosNegocio::where('activo',1)->pluck('nombre_comercial','id_socio_negocio')->prepend('...', '')
            // 'localidades' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo',1)->pluck('sucursal', 'id_sucursal')->prepend('...', ''),
        ];


    }

    // public function getDocumentos($company, Request $request, $id_tipo_documento)
    public function getDocumentos($company, Request $request)
    {
        $json = [];
        $documentosProveedor = null;
        if ($request->id_tipo_documento == "7") { // Factura
            $documentosProveedor = FacturasProveedores::where('fk_id_socio_negocio',$request->fk_id_proveedor)
                                                        // ->where('id_factura_proveedor', 'LIKE', '%' . (string)$request->term . '%')
                                                        ->where(DB::raw("CONCAT(serie_factura,'',folio_factura)"), 'LIKE', '%' . $request->term . '%')
                                                        ->where('serie_factura','<>','null')->where('folio_factura','<>','null')->get(['id_factura_proveedor','serie_factura','folio_factura']);
            foreach ($documentosProveedor as $document) {
                $json[] = [
                    'id'    => $document->id_factura_proveedor,
                    'text'  => $document->serie_factura . "-" . $document->folio_factura,
                ];
            }
            return json_encode($json);
        }else if ($request->id_tipo_documento == "3"){ // Orden de compra
            $documentosProveedor = Ordenes::where('fk_id_socio_negocio',$request->fk_id_proveedor)
                                            // ->where('id_documento', 'LIKE', '%' . $_POST['term']."" . '%')
                                            ->where('id_documento', '=', $_POST['term'])
                                            ->get(['id_documento','id_documento']);
            foreach ($documentosProveedor as $document) {
                $json[] = [
                    'id'    => $document->id_documento,
                    'text'  => $document->id_documento,
                ];
            }
            return json_encode($json);
        }else {
            return json_encode($json);
        }
    }

    public function getDesviaciones($company, Request $request){
        $json = [];
        $desviaciones = null;
        // return Response::json($request);
        $tipoDesviacion = $request->id_tipo_documento == "7" ? 2 : 1 ;
        /*if ($request->id_tipo_documento == "7") { // Factura
            $desviaciones = SeguimientoDesviacion::where('fk_id_proveedor',$request->fk_id_proveedor)->where('tipo',2)->get();
            // print_r($desviaciones);
            $detDesviaciones = [];
            // foreach ($desviaciones as $detalle) {
            //     $detDesviaciones[] = $detalle->detallesSeguimientoDesviacion;
            // }
            // return Response::json($detDesviaciones);
            return Response::json($desviaciones);
            // echo SeguimientoDesviacion::count();
        }else if($request->id_tipo_documento == "3"){

        }*/
        // $detDesviaciones = [];
        $desviaciones = SeguimientoDesviacion::where('fk_id_proveedor',$request->fk_id_proveedor)->where('tipo',$tipoDesviacion)->get();
        return Response::json($desviaciones);


        // return json_encode($json);
    }
    public function getDetalleDesviacion($company, Request $request){
        $detalleDesviacion = DetalleSeguimientoDesviacion::where('fk_id_seguimiento_desviacion',$request->fk_id_seguimiento_desviacion)->get();
        return Response::json($detalleDesviacion);
    }

    public function verdetalledesviacion(){

        return view('compras.seguimientodesviacion.verdetalledesviacion');
    }

}
