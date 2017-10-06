<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\TipoSucursal;

class TipoSucursalController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TipoSucursal $entity)
    {
        $this->entity = $entity;
    }
}