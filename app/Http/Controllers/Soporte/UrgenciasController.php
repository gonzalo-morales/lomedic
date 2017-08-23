<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\Urgencias;

class UrgenciasController extends ControllerBase
{

    public function __construct(Urgencias $entity)
    {
        $this->entity = $entity;
    }
}
