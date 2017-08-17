<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\CodigosBarras;
use App\Http\Models\Inventarios\Skus;

class CodigosBarrasController extends ControllerBase
{
    public function __construct(CodigosBarras $entity)
    {
        $this->entity = $entity;
    }

    public function obtenerCodigosBarras($company,$id)
    {
        return CodigosBarras::where('fk_id_sku',$id)->get();
    }
}
