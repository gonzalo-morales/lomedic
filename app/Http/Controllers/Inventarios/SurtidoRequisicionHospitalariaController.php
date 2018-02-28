<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 28/12/2017
 * Time: 12:41
 */

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Inventarios\SurtidoRequisicionHospitalaria;
use App\Http\Models\Servicios\RequisicionesHospitalarias;
use App\Http\Models\Administracion\Areas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Programas;
use App\Http\Models\Servicios\RequisicionesHospitalariasDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB;

class SurtidoRequisicionHospitalariaController extends ControllerBase
{


    public function __construct()
    {
        $this->entity = new SurtidoRequisicionHospitalaria;
    }

    public function getDataView($entity = null)
    {
        return [
            'sucursales' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo',1)->pluck('sucursal', 'id_sucursal')->prepend('...', ''),
            'requisiciones' => RequisicionesHospitalarias::pluck('folio','id_requisicion')->prepend('...', ''),
//            'solicitante' => Usuarios::select(['id_usuario','nombre_corto'])->where('activo',1)->pluck('nombre_corto','id_usuario')->prepend('...', ''),
//            'areas' => PagosController::all()->pluck('area', 'id_area')->prepend('...', ''),
//            'programas' => Programas::get()->pluck('nombre_programa', 'id_programa')->prepend('Sin programa', ''),
            'fk_id_usuario_captura' =>  Auth::id(),
        ];

    }

    public function getRequisiciones($company,Request $request)
    {
        $requisiciones = RequisicionesHospitalarias::whereIn('fk_id_estatus_requisicion_hospitalaria',[1,3])
            ->where('fk_id_sucursal',$request->fk_id_sucursal)
            ->pluck('folio','id_requisicion')
            ->prepend('...','')
            ->toJson();
        return $requisiciones;
    }

    public function getRequisicionDetalle($company,Request $request)
    {

        $detalle_requision = RequisicionesHospitalariasDetalle::where('fk_id_requisicion',$request->fk_id_requiscion)->get();

        $json = [];
        foreach ($detalle_requision as $row => $detalle)
        {
            $json[$row] = [
                'id_detalle_requisicon' => $detalle->id_detalle_requisicion,
                'fk_id_requisicion' => $detalle->fk_id_requisicion,
                'fk_id_area' => $detalle->fk_id_area,
                'fk_id_clave_cliente_producto' => $detalle->fk_id_clave_cliente_producto,
                'cantidad_solicitada' => $detalle->cantidad_solicitada,
                'cantidad_surtida' => $detalle->cantidad_surtida,
                'cantidad_disponible' => $detalle->claveClienteProducto->stock($detalle->claveClienteProducto->fk_id_sku,$detalle->claveClienteProducto->fk_id_upc),
                'precio_unitario' => $detalle->claveClienteProducto->precio,
                'eliminar' => $detalle->eliminar,
                'clave_cliente_producto' => $detalle->claveClienteProducto['clave_producto_cliente'],
                'descripcion' => $detalle->claveClienteProducto->sku['descripcion'],
                'sku' => $detalle->claveClienteProducto->sku['sku'],

            ];
        }
        return $json;
//        return $detalle_requision->toJson();
    }

}