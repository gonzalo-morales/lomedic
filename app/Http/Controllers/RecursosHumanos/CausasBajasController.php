<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Models\RecursosHumanos\CausasBajas;
use App\Http\Models\Administracion\Empresas;
use App\Http\Controllers\ControllerBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;

class CausasBajasController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new CausasBajas;
    }
}
