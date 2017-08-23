<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\Impactos;

class ImpactosController extends ControllerBase
{

    public function __construct(Impactos $entity)
    {
        $this->entity = $entity;
    }
}
