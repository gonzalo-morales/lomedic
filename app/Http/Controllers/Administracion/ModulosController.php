<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\ControllerBase;
use Illuminate\Support\Facades\Route;

use App\Http\Models\Administracion\Modulos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Perfiles;
use App\Http\Models\Logs;

class ModulosController extends ControllerBase
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct()
    {
        $this->entity = new Modulos;
    }

    public function getDataView($entity = null)
    {
        return [];
    }

}
