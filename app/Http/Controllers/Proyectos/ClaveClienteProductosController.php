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

class ClaveClienteProductosController extends ControllerBase
{
    public function __construct(ClaveClienteProductos $entity)
    {
        $this->entity = $entity;
    }
    
    public function getDataView($entity = null)
    {
        return [
            'clientes' => SociosNegocio::where('activo',1)->where('eliminar',0)->whereNotNull('fk_id_tipo_socio_venta')->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
            'unidadesmedidas' => UnidadesMedidas::where('activo',1)->where('eliminar',0)->orderBy('nombre')->pluck('nombre','id_unidad_medida'),
            #'clavesproductos' => ClavesProductosServicios::selectRaw("id_clave_producto_servicio, CONCAT(clave_producto_servicio,' - ',descripcion) as descripcion")->where('activo',1)->where('eliminar',0)
            #    ->orderBy('descripcion')->pluck('descripcion','id_clave_producto_servicio'),
            'clavesunidades' => ClavesUnidades::selectRaw("id_clave_unidad, CONCAT(clave_unidad,' - ',descripcion) as descripcion")->where('activo',1)->where('eliminar',0)->orderBy('descripcion')
                ->pluck('descripcion','id_clave_unidad'),
            'impuestos' => Impuestos::where('activo',1)->where('eliminar',0)->orderBy('impuesto')->pluck('impuesto','id_impuesto'),
            'skus' => Productos::selectRaw("id_sku, CONCAT(sku,' - ',descripcion_corta) as descripcion")->where('activo',1)->where('eliminar',0)->orderBy('descripcion')->pluck('descripcion','id_sku'),
            'upcs' => Upcs::selectRaw("id_upc, CONCAT(upc,' - ',nombre_comercial) as descripcion")->where('activo',1)->where('eliminar',0)->orderBy('descripcion')->pluck('descripcion','id_upc'),
            ];
    }

    public function obtenerClavesCliente($company,$id)
    {
        return ClaveClienteProductos::where('fk_id_cliente',$id)->select('id_clave_cliente_producto as id','clave_producto_cliente as text','descripcion as descripcionClave','fk_id_sku','precio')->get();
    }
}