<?php

namespace App\Http\Controllers\Inventarios;

use Illuminate\Http\Request;
use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Stock;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\Ubicaciones;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Localidades;
use Illuminate\Support\Facades\Crypt;

class StockController extends ControllerBase
{
    public function __construct(Stock $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {

        return [
            // #Variable(s) para el select2
            'localidades' => Localidades::selectRaw("localidad as localidad, id_localidad")->where('activo',1)->where('eliminar',0)->pluck('localidad','id_localidad')->prepend('Seleccione una localidad',0),

            // #Variables para las API donde tomará los valores requeridos al seleccionar un(a) localidad/sucursal/almacen/ubicación 
            'sucursal_js'  => Crypt::encryptString('"select":["id_sucursal as id","sucursal as text"], "conditions":[{"where":["activo",1]}],"whereHas":[{"localidad":{"where":["id_localidad",$fk_id_localidad]}}]'),
            'almacen_js'   => Crypt::encryptString('"select":["id_almacen as id","almacen as text"],
                "conditions":[{"where":["activo",1]}],"whereHas":[{"sucursal":{"where":["id_sucursal",$fk_id_sucursal]}}]'),
            'ubicacion_js' => Crypt::encryptString('"select":["id_ubicacion as id","ubicacion as text","fk_id_almacen"],
                "conditions":[{"where":["activo",1]}],"whereHas":[{"almacen":{"where":["id_almacen",$fk_id_almacen]}}]'),
        ];
    }

}
