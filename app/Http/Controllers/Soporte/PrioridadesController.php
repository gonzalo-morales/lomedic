<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\Prioridades;

class PrioridadesController extends ControllerBase
{

    public function __construct()
    {
        $this->entity = new Prioridades;
    }
}
