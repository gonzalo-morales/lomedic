<?php

namespace App\Http\Controllers\Finanzas;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Finanzas\Impuestos;
use Illuminate\Support\Facades\Response;

class ImpuestosController extends ControllerBase
{
    public function __construct(Impuestos $entity)
    {
        $this->entity = $entity;
    }

    public function obtenerPorcentaje($company,$id)
    {
        return Response::json(Impuestos::where('id_impuesto',$id)->first()->porcentaje);
    }
}
