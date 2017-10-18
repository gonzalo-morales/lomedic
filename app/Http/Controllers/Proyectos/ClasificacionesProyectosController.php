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

    public function create($company, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
           'tiposProyectos' => TiposProyectos::where('activo', '1')->pluck('tipo_proyecto','id_tipo_proyecto'),
        ]];

        return parent::create($company, $attributes);
    }

    public function show($company, $id, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
                'tiposProyectos' => TiposProyectos::where('activo', '1')->pluck('tipo_proyecto','id_tipo_proyecto'),
            ]];
        return parent::show($company, $id, $attributes);
    }

    public function edit($company, $id, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
                'tiposProyectos' => TiposProyectos::where('activo', '1')->pluck('tipo_proyecto','id_tipo_proyecto'),
            ]];
        return parent::edit($company, $id, $attributes);
    }
}
