<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\TiposProyectos;

class TiposProyectosController extends ControllerBase
{
    public function __construct(TiposProyectos $entity)
    {
        $this->entity = $entity;
    }
}
