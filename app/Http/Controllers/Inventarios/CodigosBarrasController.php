<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Upcs;

class CodigosBarrasController extends ControllerBase
{
    public function __construct(Upcs $entity)
    {
        $this->entity = $entity;
    }

    public function obtenerCodigosBarras($company,$id)
    {
        return $this->entity->where('fk_id_sku',$id)->get();
    }
}
