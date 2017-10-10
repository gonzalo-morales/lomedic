<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Zonas;

class ZonasController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Zonas $entity)
    {
        $this->entity = $entity;
    }
}