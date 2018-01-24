<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\Administracion\Laboratorios;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Administracion\PresentacionVenta;

class UpcsController extends ControllerBase
{
    public function __construct(Upcs $entity)
    {
        $this->entity = $entity;
    }
    
    public function getDataView($entity = null)
    {
        return [
            'presentaciones' => PresentacionVenta::select('presentacion_venta','id_presentacion_venta')->activos()->pluck('presentacion_venta','id_presentacion_venta')->sortBy('presentacion_venta')->prepend('Selecciona una Presentacion',''),
            'laboratorios' => Laboratorios::select('laboratorio','id_laboratorio')->activos()->pluck('laboratorio','id_laboratorio')->sortBy('laboratorio')->prepend('Selecciona un Laboratorio',''),
            'paises' => Paises::select('pais','id_pais')->activos()->pluck('pais','id_pais')->sortBy('pais')->prepend('Selecciona un Pais',''),
        ];
    }
    
    public function obtenerUpcs($company,$id)
    {
        return Upcs::where('fk_id_sku',$id)->select('id_upc as id','upc as text')->get();
    }
}