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
            'clientes' => SociosNegocio::where('activo',1)->where('eliminar',0)->whereNotNull('fk_id_tipo_socio_venta')->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio')->prepend('Selecciona una opcion...',''),
            'unidadesmedidas' => UnidadesMedidas::where('activo',1)->where('eliminar',0)->orderBy('nombre')->pluck('nombre','id_unidad_medida')->prepend('Selecciona una opcion...',''),
            'clavesproductos' => ClavesProductosServicios::selectRaw("id_clave_producto_servicio, CONCAT(clave_producto_servicio,' - ',descripcion) as descripcion")->where('activo',1)->where('eliminar',0)->orderBy('descripcion')
                ->limit(100)->pluck('descripcion','id_clave_producto_servicio')->prepend('Selecciona una opcion...',''),
            'clavesunidades' => ClavesUnidades::selectRaw("id_clave_unidad, CONCAT(clave_unidad,' - ',descripcion) as descripcion")->where('activo',1)->where('eliminar',0)->orderBy('descripcion')
                ->pluck('descripcion','id_clave_unidad')->prepend('Selecciona una opcion...',''),
            'impuestos' => Impuestos::where('activo',1)->where('eliminar',0)->pluck('impuesto','id_impuesto')->prepend('Selecciona una opcion...',''),
            
            'skus' => Productos::where('activo',1)->where('eliminar',0)->pluck('descripcion_corta','id_sku')->prepend('Selecciona una opcion...',''),
            'upcs' => Upcs::where('activo',1)->where('eliminar',0)->pluck('nombre_comercial','id_upc')->prepend('Selecciona una opcion...',''),
            ];
    }

    public function obtenerClavesCliente($company,$id)
    {
        return ClaveClienteProductos::where('fk_id_cliente',$id)->select('id_clave_cliente_producto as id','clave_producto_cliente as text','descripcion as descripcionClave','fk_id_sku','precio')->get();
    }
}