<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\SeguimientoDesviacion;
use App\Http\Models\Compras\DetalleSeguimientoDesviacion;
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
            'proveedores'       => SociosNegocio::where('activo',1)->where('eliminar',0)->pluck('nombre_comercial','id_socio_negocio')->prepend('Selecciona una opcion...', '')
            // 'localidades' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo', 1)->pluck('sucursal', 'id_sucursal')->prepend('Selecciona una opcion...', ''),
            // 'programas' => Programas::get()->pluck('nombre_programa', 'id_programa')->prepend('Sin programa', ''),
            // 'afiliados' => empty($entity) ? [] : Afiliaciones::selectRAW("CONCAT(paterno,' ',materno,' ',nombre) as nombre_afiliado, id_afiliacion")->where('id_afiliacion', $entity->fk_id_afiliacion)->pluck('nombre_afiliado', 'id_afiliacion'),
            // 'diagnosticos' => empty($entity) ? [] : Diagnosticos::where('id_diagnostico', $entity->fk_id_diagnostico)->where('activo', '1')->pluck('diagnostico', 'id_diagnostico'),
            // 'proyectos' => empty($entity) ? [] : Proyectos::where('id_proyecto', $entity->fk_id_proyecto)->where('eliminar', 'false')->pluck('proyecto', 'id_proyecto'),
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
                                            // ->where('id_orden', 'LIKE', '%' . $_POST['term']."" . '%')
                                            ->where('id_orden', '=', $_POST['term'])
                                            ->get(['id_orden','id_orden']);
            foreach ($documentosProveedor as $document) {
                $json[] = [
                    'id'    => $document->id_orden,
                    'text'  => $document->id_orden,
                ];
            }
            return json_encode($json);
        }else {
            return json_encode($json);
        }
    }

    public function getDesviaciones($company, Request $request){
        $json = [];
        $detalleDesviacion = null;
        if ($request->id_tipo_documento == "7") { // Factura
            $detalleDesviacion = SeguimientoDesviacion::where('fk_id_proveedor',$request->fk_id_proveedor)->where('tipo',2)->get();
            print_r($detalleDesviacion);
            foreach ($detalleDesviacion as $detalle) {
                $det = $detalle->detallesSeguimientoDesviacion;
                // $json[] = [];
                print_r($det);
            }
            // echo SeguimientoDesviacion::count();
        }


        return json_encode($json);
    }

}
