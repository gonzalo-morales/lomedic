<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ClasificacionesProyectos;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\Proyectos\TiposProductosProyectos;
use App\Http\Models\Proyectos\TiposProyectos;

class TiposProductosProyectosController extends ControllerBase
{
    public function __construct(TiposProductosProyectos $entity)
    {
        $this->entity = $entity;
    }
    
    public function getDataView($entity = null)
    {
        return [
            'proyectos' => Proyectos::where('fk_id_estatus', '1')->pluck('proyecto','id_proyecto'),
        ];
    }
}