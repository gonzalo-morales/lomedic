<?php

namespace App\Http\Controllers\Inventarios;

use Illuminate\Http\Request;
use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\MovimientoAlmacen;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Inventarios\Stock;
use App\Http\Models\Inventarios\Almacenes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class MovimientoAlmacenController extends ControllerBase
{
    public function __construct(MovimientoAlmacen $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {
        return [
            // #Variable(s) para el select2
            'sucursales'  => Sucursales::whereHas('empleados',function ($q){$q->where('id_empleado',Auth::user()->fk_id_empleado);})->pluck('sucursal','id_sucursal')->prepend('Seleccione la sucursal',''),
            //Variable para tomar la fecha actual
            'fechaActual' => Carbon::now(),

            // #Variables para las API 
            'almacen_js' => Crypt::encryptString('
                "select":["id_almacen","almacen"],
                "with":["ubicaciones:fk_id_almacen,id_ubicacion,ubicacion"],
                "conditions":[{"where":["fk_id_sucursal", "$fk_id_sucursal"]}]
            '),
            'sku_js'     => Crypt::encryptString('
                "select":["id_stock","fk_id_sku","fk_id_upc","lote","fecha_caducidad","stock","fk_id_almacen","fk_id_ubicacion"],
                "with":["sku:id_sku,sku,descripcion","upc:id_upc,upc,nombre_comercial,descripcion","almacen:id_almacen,almacen","ubicacion:id_ubicacion,ubicacion"],
                "conditions":[{"where":["fk_id_almacen", "$fk_id_almacen"]}]
            '),
        ];

           // Stock::whereHas('almacen.sucursal', function($q) {
        //     $q->where('id_sucursal', $fk_id_sucursal)
        // })->get();




    }

}
