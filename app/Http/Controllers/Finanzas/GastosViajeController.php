<?php

namespace App\Http\Controllers\Finanzas;

use Illuminate\Http\Request;
use App\Http\Controllers\ControllerBase;
use App\Http\Models\Finanzas\GastosViaje;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Administracion\ConceptosViaje;
use App\Http\Models\Administracion\Impuestos;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class GastosViajeController extends ControllerBase
{
    public function __construct(GastosViaje $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {

        // if ($entity) {
        //     $total_dias = GastosViaje::selectRaw('SELECT *, EXTRACT(DAY FROM age(periodo_fin::date,periodo_inicio::date ) ) as total_dias FROM fin_opr_gastos');
        // }


        return [
            'fechaActual' => Carbon::now(),
            #Variable(s) para el select2
            'empleados' => Empleados::selectRaw("Concat(nombre,' ',apellido_paterno,' ',apellido_materno) as empleado, id_empleado")->where('activo',1)->pluck('empleado','id_empleado'),
            'conceptos' => ConceptosViaje::selectRaw("tipo_concepto as concepto, id_concepto")->where('activo',1)->pluck('concepto','id_concepto')->prepend('seleccione...',''),
            'impuestos' => Impuestos::selectRaw("impuesto as impuesto, id_impuesto")->where('activo',1)->pluck('impuesto','id_impuesto')->prepend('seleccione...',''),

            #Variables para las API donde tomará los valores requeridos al seleccionar un empleado 
            'departamento_js'  => Crypt::encryptString('"select": ["fk_id_departamento"], "conditions": [{"where":["id_empleado", "$id_empleado"]}], "with": ["departamento:id_departamento,descripcion"], "limit": "1"'),
            'puesto_js'        => Crypt::encryptString('"select": ["fk_id_puesto"], "conditions": [{"where":["id_empleado", "$id_empleado"]}], "with": ["puesto:id_puesto,descripcion"], "limit": "1"'),
            'sucursal_js'      => Crypt::encryptString('"select": ["fk_id_sucursal"], "conditions": [{"where":["id_empleado", "$id_empleado"]}], "with": ["sucursales:id_sucursal,sucursal"], "limit": "1"'),

            #Variable para las API donde tomará el valor requerido al seleccionar un impuesto
            'impuesto_js'      => Crypt::encryptString('"select": ["tasa_o_cuota"], "conditions": [{"where":["id_impuesto", "$id_impuesto"]}], "limit": "1"'),
            // 'total_dias' => $total_dias ?? '';
        ];
    }

    //"select "departamento" from "rh_cat_departamentos" where "rh_cat_departamentos"."id_departamento" in (18))"

}
