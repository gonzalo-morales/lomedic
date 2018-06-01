<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Medicos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MedicosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new Medicos;
	}

	public function getDataView($entity = null)
    {
        return [
            'clientes' => SociosNegocio::where('activo',1)->whereNotNull('fk_id_tipo_socio_venta')->whereHas('empresas',function ($empresa){
                $empresa->where('id_empresa',request()->empresa->id_empresa)->where('eliminar','f');
            })->pluck('nombre_comercial','id_socio_negocio'),
        ];

	}
}