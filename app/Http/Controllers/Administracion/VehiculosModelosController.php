<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\VehiculosMarcas;
use App\Http\Models\Administracion\VehiculosModelos;

class VehiculosModelosController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VehiculosModelos $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {
        return [
            'brands' => VehiculosMarcas::select(['marca', 'id_marca'])->where('activo',1)->pluck('marca', 'id_marca'),
        ];
    }
}