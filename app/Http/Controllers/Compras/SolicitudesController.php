<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Solicitudes;
use App\Http\Models\RecursosHumanos\Empleados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudesController extends ControllerBase
{
    public function __construct(Solicitudes $entity)
    {
        $this->entity = $entity;
    }

    public function index($company, $attributes = [])
    {
        $attributes = ['where'=>[]];
        return parent::index($company, $attributes);
    }

    public function store(Request $request, $company)
    {
        # ¿Usuario tiene permiso para crear?
        $this->authorize('create', $this->entity);

        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

        $request->request->set('fecha_creacion',DB::raw('now()'));
        if($request->fk_id_estatus_solicitud == 3)//Si es cancelado
            {$request->request->set('fecha_cancelacion',DB::raw('now'));}


        $request->request->set('fk_id_departamento',Empleados::where('id_empleado',$request->fk_id_solicitante)->first()->fk_id_departamento);

        $isSuccess = $this->entity->create($request->all());
        if ($isSuccess) {
            foreach ($request->detalles as $detalle){
                $isSuccess->detalleSolicitudes()->save(new DetalleSolicitudes($detalle));
            }
            $this->log('store', $isSuccess->id_banco);
            return $this->redirect('store');
        } else {
            $this->log('error_store');
            return $this->redirect('error_store');
        }
    }

}
