<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\Autorizaciones;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\LogModulos;

class AutorizacionesController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Autorizaciones;
    }

    public function update(Request $request, $company, $id, $compact = false)
    {
        $entity = $this->entity->findOrFail($id);
        $request->request->set('fk_id_usuario_autoriza',Auth::id());
        $request->request->set('fecha_autorizacion',Carbon::now());
        $request->request->set('fecha_creacion',$entity->fecha_creacion);
        $request->request->set('fk_id_documento',$entity->fk_id_documento);
        $request->request->set('fk_id_condicion',$entity->fk_id_condicion);
        $request->request->set('fk_id_tipo_documento',$entity->fk_id_tipo_documento);
        return parent::update($request,$company,$id,$compact);
    }
    
    public function destroy(Request $request, $company, $idOrIds, $attributes = ['fk_id_estatus'=>5])
    {
        return parent::destroy($request, $company, $idOrIds, $attributes);
    }
}