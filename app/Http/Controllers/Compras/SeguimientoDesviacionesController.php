<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\SeguimientoDesviacion;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Compras\FacturasProveedores;
use Illuminate\Http\Response;
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

    public function getDataView($entity = null)
    {
        return [
            'proveedores'       => SociosNegocio::where('activo',1)->pluck('nombre_comercial','id_socio_negocio')->prepend('...', '')
            // 'localidades' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo',1)->pluck('sucursal', 'id_sucursal')->prepend('...', ''),
            // 'programas' => Programas::get()->pluck('nombre_programa', 'id_programa')->prepend('Sin programa', ''),
            // 'afiliados' => empty($entity) ? [] : Afiliaciones::selectRAW("CONCAT(paterno,' ',materno,' ',nombre) as nombre_afiliado, id_afiliacion")->where('id_afiliacion', $entity->fk_id_afiliacion)->pluck('nombre_afiliado', 'id_afiliacion'),
            // 'diagnosticos' => empty($entity) ? [] : Diagnosticos::where('id_diagnostico', $entity->fk_id_diagnostico)->where('activo',1)->pluck('diagnostico', 'id_diagnostico'),
            // 'proyectos' => empty($entity) ? [] : Proyectos::where('id_proyecto', $entity->fk_id_proyecto)->pluck('proyecto', 'id_proyecto'),
        ];


    }

    // public function getDocumentos($company, Request $request, $tipoDocumento)
    public function getDocumentos($company, Request $request)
    {
        $json = [];
        $documentosProveedor = null;
        if ($request->tipoDocumento == "7") { // Factura
            $documentosProveedor = FacturasProveedores::where('fk_id_socio_negocio',$request->fk_id_proveedor)->where('serie_factura','<>','null')->where('folio_factura','<>','null')->get(['id_factura_proveedor','serie_factura','folio_factura']);
            foreach ($documentosProveedor as $document) {
                $json[] = [
                    'id'            => $document->id_factura_proveedor,
                    'identificador' => $document->serie_factura . "-" . $document->folio_factura,
                ];
            }
            // return json_encode($documentosProveedor);
            return json_encode($json);
            // return json_encode(array('data'=>$json));
        }else {
            return json_encode($json);
        }
        // $json = [];
        // $afiliados = Afiliaciones::where('id_afiliacion', 'LIKE', $term . '%')->orWhere(DB::raw("CONCAT(paterno,' ',materno, ' ',nombre)"), 'LIKE', '%' . $term . '%')->get();
        // foreach ($documentosProveedor as $document) {
        //     $json[] = [
        //             'id'            => $document->id_factura_proveedor,
        //             'identificador' => $document->serie_factura . "-" . $document->folio_factura,
        //         ];
        // }
        // return json_encode($json);
    }

}
