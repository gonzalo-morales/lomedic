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
use App\Http\Models\Inventarios\SurtidoReceta;
use App\Http\Models\Servicios\Recetas;
use App\Http\Models\Servicios\RecetasDetalle;
use App\Http\Models\Servicios\RequisicionesHospitalarias;
use App\Http\Models\Administracion\Areas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Programas;
use App\Http\Models\Servicios\RequisicionesHospitalariasDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB;

class SurtidoRecetaController extends ControllerBase
{


    public function __construct(SurtidoReceta $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {
        return [
            'sucursales' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo',1)->pluck('sucursal', 'id_sucursal')->prepend('...', ''),
            'recetas' => empty($entity) ? [] : Recetas::select('folio','id_receta')->pluck('folio','id_receta')->prepend('...', ''),
//            'solicitante' => Usuarios::select(['id_usuario','nombre_corto'])->where('activo',1)->pluck('nombre_corto','id_usuario')->prepend('...', ''),
//            'areas' => PagosController::all()->pluck('area', 'id_area')->prepend('...', ''),
//            'programas' => Programas::get()->pluck('nombre_programa', 'id_programa')->prepend('Sin programa', ''),
            'fk_id_usuario_captura' =>  Auth::id(),
        ];

    }

    public function getReceta($company,Request $request)
    {
        $recetas = Recetas::whereIn('fk_id_estatus_receta',[1,3])
            ->where('fk_id_sucursal',$request->fk_id_sucursal)
            ->orderBy('folio', 'desc')
            ->pluck('folio','id_receta')
            ->prepend('...','')
            ->toJson();
        return $recetas;
    }

    public function getRecetaDetalle($company,Request $request)
    {

        $detalle_receta = RecetasDetalle::where('fk_id_receta',$request->fk_id_receta)->get();
        /*
        $json = [];
        foreach ($detalle_receta as $row => $detalle)
        {
            $json[$row] = [
                'id_receta_detalle' => $detalle->id_detalle_receta,
                'fk_id_receta' => $detalle->fk_id_receta,
//                'fk_id_area' => $detalle->fk_id_area,
                'fk_id_clave_cliente_producto' => $detalle->fk_id_clave_cliente_producto,
                'cantidad_solicitada' => $detalle->cantidad_pedida,
                'cantidad_surtida' => $detalle->cantidad_surtida,
                'cantidad_disponible' => $detalle->claveClienteProducto->stock($detalle->claveClienteProducto->fk_id_sku,$detalle->claveClienteProducto->fk_id_upc),
                'precio_unitario' => $detalle->claveClienteProducto->precio,
                'eliminar' => $detalle->eliminar,
                'clave_cliente_producto' => $detalle->claveClienteProducto['clave_producto_cliente'],
                'descripcion' => $detalle->claveClienteProducto->sku['descripcion'],

            ];
        }*/
        return $detalle_receta->toJson();
    }

}