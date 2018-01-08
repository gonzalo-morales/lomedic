<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ClaveClienteProductos;

class ClaveClienteProductosController extends ControllerBase
{
    public function __construct(ClaveClienteProductos $entity)
    {
        $this->entity = $entity;
    }

    public function obtenerClavesCliente($company,$id)
    {
        return ClaveClienteProductos::where('fk_id_cliente',$id)->select('id_clave_cliente_producto as id','clave_producto_cliente as text','descripcion as descripcionClave','fk_id_sku','precio')->get();
    }
}