<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ProyectosProductos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProyectosProductosController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new ProyectosProductos;
    }

    public function destroy(Request $request, $company, $idOrIds, $attributes = [])
    {
        # ¿Usuario tiene permiso para eliminar?
//        $this->authorize('delete', $this->entity);

        $isSuccess = $this->entity->whereIn($this->entity->getKeyName(), $request->ids)->update(['eliminar' => 't']);
        if ($isSuccess) {

            # Shorthand
            #foreach ($request->ids as $id) $this->log('destroy', $id);

            if ($request->ajax()) {
                # Respuesta Json
                return response()->json([
                    'success' => true,
                ]);
            } else {
                return $this->redirect('destroy');
            }

        } else {

            # Shorthand
            #foreach ($request->ids as $id) $this->log('error_destroy', $id);

            if ($request->ajax()) {
                # Respuesta Json
                return response()->json([
                    'success' => false,
                ]);
            } else {
                return $this->redirect('error_destroy');
            }
        }
    }

}
