<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\DetalleSolicitudes;
use Illuminate\Http\Request;

class DetalleSolicitudesController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new DetalleSolicitudes;
    }

    public function destroy(Request $request, $company, $idOrIds, $attributes = [])
    {
        # ¿Usuario tiene permiso para eliminar?
//        $this->authorize('delete', $this->entity);

        $isSuccess = $this->entity->whereIn($this->entity->getKeyName(), $idOrIds)->update(['cerrado' => 't']);
        if ($isSuccess) {

            # Shorthand
            #foreach ($idOrIds as $id) $this->log('destroy', $id);

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
            #foreach ($idOrIds as $id) $this->log('error_destroy', $id);

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
