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

    public function create($company, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
           'proyectos' => Proyectos::where('activo', '1')->pluck('proyecto','id_proyecto'),
        ]];

        return parent::create($company, $attributes);
    }

    public function show($company, $id, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
                'proyectos' => Proyectos::where('activo', '1')->pluck('proyecto','id_proyecto'),
            ]];
        return parent::show($company, $id, $attributes);
    }

    public function edit($company, $id, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
                'proyectos' => Proyectos::where('activo', '1')->pluck('proyecto','id_proyecto'),
            ]];
        return parent::edit($company, $id, $attributes);
    }
}
