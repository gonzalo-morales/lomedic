<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ClasificacionesProyectos;
use App\Http\Models\Proyectos\TiposProyectos;

class ClasificacionesProyectosController extends ControllerBase
{
    public function __construct(ClasificacionesProyectos $entity)
    {
        $this->entity = $entity;
    }
    
    public function getDataView($entity = null)
    {
        return [
            'tiposProyectos' => TiposProyectos::where('activo', '1')->pluck('tipo_proyecto','id_tipo_proyecto'),
        ];
    }
}