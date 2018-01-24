<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Impuestos;
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

    public function obtenerImpuestos($company)
    {
        $impuestosSet = [];
        $impuestosSet[] = ['id'=>'0', 'text'=>'Selecciona un tipo de impuesto','disabled'=>'true','selected'=>'selected'];
        $impuestos = Impuestos::select('id_impuesto','impuesto','porcentaje')->activos()->get();
        foreach ($impuestos as $impuesto){
            $impuestosSet[] = ['id'=>$impuesto->id_impuesto,
                'text' => $impuesto->impuesto,
                'porcentaje' => $impuesto->porcentaje];
        }

        return Response::json($impuestosSet);
    }
}
