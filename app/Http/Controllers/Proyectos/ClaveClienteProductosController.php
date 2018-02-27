<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\Administracion\ClavesProductosServicios;
use App\Http\Models\Administracion\ClavesUnidades;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Upcs;
use Illuminate\Support\Facades\Crypt;

class ClaveClienteProductosController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new ClaveClienteProductos;
    }
    
    public function getDataView($entity = null)
    {

        $upcs = Upcs::select("id_upc")->get();

//        $datos = $upcs->map( function ($key, $upc) {
//           $upc->skus->map( function ($key, $sku) use($upc) {
//              return $sku->pivot;
//           });
//        }

////        );
//        $datos = null;
//        foreach ($upcs as $upc){
//            $datos = $upc->skus->map( function ($sku) use($upc) {
//                return $sku->pivot;
//            });
//        }
//        dd($datos);
//
//        exit();
        $claveproductoservicio = null;
        $upcs = null;
        if($entity){
            $claveproductoservicio = ClavesProductosServicios::selectRaw("id_clave_producto_servicio as id, CONCAT(clave_producto_servicio,' - ',descripcion) as text")->where('id_clave_producto_servicio',$entity->fk_id_clave_producto_servicio)->orderBy('text')->pluck('text','id');
            $upcs = Upcs::selectRaw("id_upc as id, CONCAT(upc,' - ',nombre_comercial) as text")->where('activo',1)->whereHas('skus',function ($q) use ($entity){
                $q->where('id_sku',$entity->fk_id_sku);
            })->pluck('text','id');
        }
        return [
            'clientes' => SociosNegocio::where('activo',1)->whereNotNull('fk_id_tipo_socio_venta')->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
            'unidadesmedidas' => UnidadesMedidas::where('activo',1)->orderBy('nombre')->pluck('nombre','id_unidad_medida'),
            'clavesproductosservicios' => $claveproductoservicio,
            'clavesunidades' => ClavesUnidades::selectRaw("id_clave_unidad, CONCAT(clave_unidad,' - ',descripcion) as descripcion")->where('activo',1)->orderBy('descripcion')
                ->pluck('descripcion','id_clave_unidad'),
            'impuestos' => Impuestos::where('activo',1)->orderBy('impuesto')->pluck('impuesto','id_impuesto'),
            'skus' => Productos::selectRaw("id_sku, CONCAT(sku,' - ',descripcion_corta) as descripcion")->where('activo',1)->orderBy('descripcion')->pluck('descripcion','id_sku'),
            'upcs' => $upcs,
            'js_upcs' => Crypt::encryptString('"selectRaw":["id_upc as id, CONCAT(upc,\' - \',nombre_comercial) as text"],"whereHas":[{"skus":{"where":["fk_id_sku",$fk_id_sku]}}]'),
            'js_cantidad_upc' => Crypt::encryptString('"conditions":[{"where":["id_upc","$fk_id_upc"]}],"whereHas":[{"skus": {"where": ["fk_id_sku", "$fk_id_sku"]}}],"pivot":["skus"]'),
            'js_clave_producto_servicio' => Crypt::encryptString('"selectRaw":["id_clave_producto_servicio as id, CONCAT(clave_producto_servicio,\' - \',descripcion) as text"],"conditions":[{"where":["clave_producto_servicio","ILIKE","%$term%"]},{"orWhere":["descripcion","ILIKE","%$term%"]}]'),
            'js_clave_unidad' => Crypt::encryptString('"selectRaw":["id_clave_unidad as id, CONCAT(clave_unidad,\' - \',descripcion) as text"],"conditions":[{"where":["clave_unidad","ILIKE","%$term%"]},{"orWhere":["descripcion","ILIKE","%$term%"]}]'),
            ];
    }

    public function obtenerClavesCliente($company,$id)
    {
        return ClaveClienteProductos::where('fk_id_cliente',$id)->select('id_clave_cliente_producto as id','clave_producto_cliente as text','descripcion as descripcionClave','fk_id_sku','precio')->get();
    }
}