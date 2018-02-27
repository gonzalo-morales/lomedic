<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\SeriesDocumentos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\TiposDocumentos;

class SeriesDocumentosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct()
	{
	    $this->entity = new SeriesDocumentos;
	}
	
	public function getDataView($entity = null)
	{
	    return [
	        'empresas' => Empresas::select(['id_empresa', 'razon_social'])->orderBy('razon_social')->pluck('razon_social','id_empresa'),
	        'tiposdocumentos' => TiposDocumentos::select(['id_tipo_documento', 'nombre_documento'])->orderBy('nombre_documento')->pluck('nombre_documento','id_tipo_documento'),
	    ];
	}
}