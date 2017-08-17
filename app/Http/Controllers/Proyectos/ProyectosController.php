<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\Proyectos;
use Illuminate\Support\Facades\Response;

class ProyectosController extends ControllerBase
{
    public function __construct(Proyectos $entity)
    {
        $this->entity = $entity;
    }

    public function obtenerProyectos()
    {
        $proyectos = Proyectos::all()->where('activo','1');
        foreach ($proyectos as $proyecto)
        {
            $proyecto_data['id'] = (int)$proyecto->id_proyecto;
            $proyecto_data['text'] = $proyecto->proyecto;
            $proyectos_set[] = $proyecto_data;
        }
        return Response::json($proyectos_set);
    }
}
