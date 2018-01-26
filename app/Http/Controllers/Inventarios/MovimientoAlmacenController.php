<?php

namespace App\Http\Controllers\Inventarios;

use Illuminate\Http\Request;
use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\MovimientoAlmacen;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Inventarios\Stock;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Ubicaciones;
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

        $almacenes = [];
        $ubicaciones_det = [];
        // $skus_data = [];
        if($entity != null){


            // $skus = Productos::whereHas('stock',function ($q) use ($entity) {
            //     $q->where('fk_id_almacen', $entity->fk_id_almacen)->where('activo',1);
            // })->get()->tap(function($collection) use (&$skus_data) {
            //     $skus_data = $collection->mapWithKeys(function($item){
            //         return [$item['id_sku'] => [
            //             'data-sku' => $item['sku'],
            //             'data-descripcion_corta' => $item['descripcion']
            //         ]];

            //     })->toArray();
            // })->pluck('sku','id_sku');
            $almacenes = Almacenes::where('fk_id_sucursal',$entity->fk_id_sucursal)->where('activo',1)->pluck('almacen','id_almacen');
            $ubicaciones_det = Ubicaciones::where('fk_id_almacen',$entity->fk_id_almacen)->where('activo',1)->pluck('ubicacion','id_ubicacion');
            $skus = Productos::whereHas('stock',function ($q) use ($entity){
                $q->where('fk_id_almacen',$entity->fk_id_almacen)->where('activo',1);
            })->pluck('sku','id_sku');
        }
        $fechaActual = Carbon::now();

        return [
            // #Variable(s) para el select2
            'sucursales'  => Sucursales::whereHas('empleados',function ($q){$q->where('id_empleado',Auth::user()->fk_id_empleado);})->pluck('sucursal','id_sucursal')->prepend('Seleccione la sucursal',''),
            'almacenes'  => $almacenes,
            'ubicaciones_det' => $ubicaciones_det,
            'fechaActual' => $fechaActual,
            // 'skus_data' => $skus_data,

            // #Variables para las API 
            'almacen_js' => Crypt::encryptString('
                "select":["id_almacen","almacen"],
                "with":["ubicaciones:fk_id_almacen,id_ubicacion,ubicacion"],
                "conditions":[{"where":["fk_id_sucursal", "$fk_id_sucursal"]}]
            '),
            'ubicacion_js' => Crypt::encryptString('
                "select":["id_ubicacion","ubicacion"],
                "conditions":[{"where":["fk_id_almacen", "$fk_id_almacen"]}]
            '),
            'sku_js'     => Crypt::encryptString('
                "select":["id_stock","fk_id_sku","fk_id_upc","lote","fecha_caducidad","stock","fk_id_almacen","fk_id_ubicacion"],
                "with":["sku:id_sku,sku,descripcion","upc:id_upc,upc,nombre_comercial,descripcion","almacen:id_almacen,almacen","ubicacion:id_ubicacion,ubicacion"],
                "conditions":[{"where":["fk_id_almacen", "$fk_id_almacen"]}]
            '),
        ];

    }

    public function store(Request $request,$company, $compact = false){
        $request->request->set('fk_id_usuario',Auth::id());
        $request->request->set('total_productos',count($request->relations['has']['detalle']));
        return parent::store($request, $company, $compact);
    }

}
