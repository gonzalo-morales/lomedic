<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\TiposComprobantes;

class TiposComprobantesController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TiposComprobantes $entity)
    {
        $this->entity = $entity;
    }
}