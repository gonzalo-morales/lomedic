<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\TipoAlmacen;

class TipoAlmacenController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TipoAlmacen $entity)
    {
        $this->entity = $entity;
    }
}