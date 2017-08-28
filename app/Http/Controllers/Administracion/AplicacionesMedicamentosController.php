<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\AplicacionesMedicamentos;
use Illuminate\Support\Facades\Schema;

class AplicacionesMedicamentosController extends ControllerBase
{
    public function __construct(AplicacionesMedicamentos $entity)
    {
        $this->entity = $entity;
    }
}
