<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Administracion\TiposDocumentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstatusDocumentosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new EstatusDocumentos;
	}

	public function getDataView($entity = null)
    {
        return ['tiposdocumentos'=>TiposDocumentos::where('activo',1)->orderBy('nombre_documento')->pluck('nombre_documento','id_tipo_documento')];
    }

    public function store(Request $request, $company, $compact = true)
    {
        $return = parent::store($request, $company, $compact);
        $entity = $return['entity'];
        if ($entity) {
            if (isset($request->tiposdocumentos)) {
                $sync = [];
                foreach ($request->tiposdocumentos as $tipodocumento) {
                    if ($tipodocumento) {
                        array_push($sync ,['fk_id_estatus'=>$entity->id_estatus,'fk_id_tipo_documento'=>$tipodocumento]);
                    }
                }
                $entity->tiposdocumentos()->sync($sync);
            }
        }
        return $return['redirect'];
    }

    public function update(Request $request, $company, $id, $compact = true)
    {
        $return = parent::update($request, $company, $id, $compact);
        $entity = $return['entity'];
        if ($entity) {
            if (isset($request->tiposdocumentos)) {
                $sync = [];
                foreach ($request->tiposdocumentos as $tipodocumento) {
                    if ($tipodocumento) {
                        array_push($sync ,['fk_id_estatus'=>$entity->id_estatus,'fk_id_tipo_documento'=>$tipodocumento]);
                    }
                }
                $entity->tiposdocumentos()->sync($sync);
            }
        }
        return $return['redirect'];
    }
}