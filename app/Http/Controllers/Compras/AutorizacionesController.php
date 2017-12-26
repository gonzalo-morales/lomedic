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

class AutorizacionesController extends ControllerBase
{
    public function __construct(Autorizaciones $entity)
    {
        $this->entity = $entity;
    }

    public function update(Request $request, $company, $id)
    {
        $request->request->set('fk_id_usuario_autoriza',Auth::id());
        $request->request->set('fecha_autorizacion',Carbon::now());
        # ¿Usuario tiene permiso para actualizar?
        //		$this->authorize('update', $this->entity);

        # Validamos request, si falla regresamos atras
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);
        DB::beginTransaction();
        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        if ($entity->save()) {
            DB::commit();
            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();
            $this->log('update', $id);
            return \response()->json([
                'status'=>'1'
            ]);
        } else {
            DB::rollBack();
            $this->log('error_update', $id);
            return \response()->json([
                'status'=>'0'
            ]);
        }
    }
}

