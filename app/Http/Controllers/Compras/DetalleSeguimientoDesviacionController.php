<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\DetalleSeguimientoDesviacion;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Compras\FacturasProveedores;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetalleSeguimientoDesviacionController extends ControllerBase
{
    public function __construct(DetalleSeguimientoDesviacion $entity)
    {
        $this->entity = $entity;
    }

    // public function getDataView($entity = null)
    // {
    //     return [
    //         'proveedores'       => SociosNegocio::where('activo',1)->where('eliminar',0)->pluck('nombre_comercial','id_socio_negocio')->prepend('Selecciona una opcion...', ''),
    //         'detalleDesviacion' => DetalleSeguimientoDesviacion::all(),
    //     ];
    // }

    public function actualizarEstatus($company, Request $request){
        $json_response = [];

        $detSeguimientoDesviacion = DetalleSeguimientoDesviacion::find($request->id_detalle_seguimiento_desviacion);
        $detSeguimientoDesviacion->fk_id_estatus = $request->fk_id_estatus;
        $detSeguimientoDesviacion->observaciones = $request->observaciones;
        $is_saved = $detSeguimientoDesviacion->save();
        if ($is_saved) {
            $json_response = ['status' => 1];
        }else {
            $json_response = ['status' => 0];
        }

        return Response::json($json_response);
    }
}
