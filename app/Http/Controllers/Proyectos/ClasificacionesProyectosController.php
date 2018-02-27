<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ClasificacionesProyectos;
use App\Http\Models\Proyectos\TiposProyectos;

class ClasificacionesProyectosController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new ClasificacionesProyectos;
    }
}