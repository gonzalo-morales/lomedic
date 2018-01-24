<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\SolicitudesPagos;
use App\Http\Models\RecursosHumanos\Empleados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Compras\CondicionesAutorizacion;

class SolicitudesPagosController extends ControllerBase
{
    public function __construct(SolicitudesPagos $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {

        return [
            'solicitantes'=>Empleados::select(DB::raw("concat(nombre,' ',apellido_paterno,' ',apellido_materno) as text"),'id_empleado')->where('activo','t')->pluck('text','id_empleado'),
            'formas_pago'=>FormasPago::select('descripcion','id_forma_pago')->where('activo','t')->pluck('descripcion','id_forma_pago'),
            'monedas'=>Monedas::select(DB::raw("concat('(',moneda,') ',descripcion) as text"),'id_moneda')->where('activo','t')->pluck('text','id_moneda'),
            'ordenes'=>Ordenes::select('id_orden')->where('fk_id_estatus_orden',1)->where('total_orden','>',0)->whereHas('detalleOrdenes')->pluck('id_orden','id_orden'),
            'js_sucursales'=>Crypt::encryptString('"select":["id_sucursal as id","sucursal as text"], "conditions":[{"where":["activo",1]}],"whereHas":[{"empleados":{"where":["id_empleado","$fk_id_empleado"]}}]'),
            'js_orden'=>Crypt::encryptString('"conditions":[{"where":["id_orden",$fk_id_orden]}]'),
            'sucursales' => empty($entity) ? [] : Sucursales::where('activo',1)->where('eliminar',0)->whereHas('empleados', function ($query) use ($entity) {
                $query->where('id_empleado', $entity->fk_id_solicitante);
            })->pluck('sucursal','id_sucursal'),
            'condiciones'=>Usuarios::find(Auth::id())->condiciones->where('fk_id_tipo_documento',10)->where('activo',1)->where('eliminar',0)
        ];
    }

    public function destroy(Request $request, $company, $idOrIds, $attributes = [])
    {
        $isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)
            ->update(['fk_id_estatus_solicitud_pago' => 3,
                'motivo_cancelacion'=>$request->motivo['motivo_cancelacion'],
                'fecha_cancelacion'=>DB::raw('now()')]);
        if ($isSuccess) {

            #$this->log('destroy', $idOrIds);

            if ($request->ajax()) {
                # Respuesta Json
                return response()->json([
                    'success' => true,
                ]);
            } else {
                return $this->redirect('destroy');
            }

        } else {

            #$this->log('error_destroy', $idOrIds);

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

