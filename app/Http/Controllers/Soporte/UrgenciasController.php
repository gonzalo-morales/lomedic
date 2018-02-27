<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\Urgencias;

class UrgenciasController extends ControllerBase
{

    public function __construct()
    {
        $this->entity = new Urgencias;
    }
}
