<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Especificaciones;


class EspecificacionesController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Especificaciones;
    }
}
