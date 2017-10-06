<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TipoAlmacen;
use App\Http\Models\Inventarios\Almacenes;

class AlmacenesController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Almacenes $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {
        // if ($entity) {
        //     return [
        //         'localidades' => Localidades::select(['localidad','id_localidad'])->where('activo', 1)->pluck('localidad','id_localidad'),
        //         'zonas' => Zonas::select(['zona','id_zona'])->where('activo', 1)->pluck('zona','id_zona'),
        //         'paises' => Paises::select(['pais','id_pais'])->where('activo', 1)->pluck('pais','id_pais'),
        //         'estados' => Estados::select(['estado','id_estado'])->where('activo', 1)->where('fk_id_pais', $entity->fk_id_pais)->pluck('estado','id_estado'),
        //         'municipios' => Municipios::select(['municipio','id_municipio'])->where('activo', 1)->whereHas('estado', function($q) use ($entity) {
        //                             $q->where('id_estado', $entity->fk_id_estado);
        //                             $q->whereHas('pais', function($q) use ($entity) {
        //                                 $q->where('id_pais', $entity->fk_id_pais);
        //                             });
        //                         })->pluck('municipio','id_municipio'),
        //         'sucursales' => $this->entity->select(['sucursal','id_sucursal'])->where('activo', 1)->pluck('sucursal','id_sucursal'),
        //     ];
        // }
        return [
            // 'localidades' => Localidades::select(['localidad','id_localidad'])->where('activo', 1)->pluck('localidad','id_localidad'),
            // 'zonas' => Zonas::select(['zona','id_zona'])->where('activo', 1)->pluck('zona','id_zona'),
            // 'paises' => Paises::select(['pais','id_pais'])->where('activo', 1)->pluck('pais','id_pais'),
            'sucursales' => Sucursales::select(['sucursal','id_sucursal'])->where('activo', 1)->pluck('sucursal','id_sucursal'),
            'tipos' => TipoAlmacen::select(['tipo','id_tipo'])->where('activo', 1)->pluck('tipo','id_tipo'),
        ];
    }

}