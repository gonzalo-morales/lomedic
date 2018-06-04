<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 11/10/2017
 * Time: 12:00
 */

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TiposDocumentos;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Inventarios\Entradas;
use App\Http\Models\Inventarios\EntradaDetalle;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\ModelBase;
use App\Http\Models\SociosNegocio\SociosNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradasController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Entradas;
    }

    public function getDataView($entity = null)
    {

//        dd($entity);
        return [
            'tipo_documento' => TiposDocumentos::select('id_tipo_documento','nombre_documento')->orderBy('id_tipo_documento')->pluck('nombre_documento','id_tipo_documento')->prepend('...',''),
//            'tipo_documento' => !(empty($entity)) ? [] : TiposDocumentos::select('id_tipo_documento','nombre_documento')->orderBy('id_tipo_documento')->pluck('nombre_documento','id_tipo_documento')->prepend('...',''),
            'numero_documento' => !(empty($entity)) ? Entradas::select('id_documento','numero_documento')->pluck('numero_documento','id_documento'):[],
            'sucursal' => !(empty($entity)) ? Sucursales::  ,
            'proceedor' => !(empty($entity)) ? ,
        ];


    }


    public function getDocumento($company,Request $request)
    {

//        $modelo_base = new ModelBase();
//        dump($modelo_base->documentos_destino($request->fk_id_tipo_documento)->first()->documento()->toSql());

        switch ($request->fk_id_tipo_documento)
        {
            case 1:

            break;
            case 2:

            break;
            case 3:
                $documentos = Ordenes::where('fk_id_tipo_documento',$request->fk_id_tipo_documento)->orderBy('id_documento')->pluck('id_documento');
            break;

        }
        return $documentos;

    }

    public function getDetalleDocumento($company,Request $request)
    {
        $documento = Ordenes::where('fk_id_tipo_documento',$request->fk_id_tipo_documento)
            ->where('id_documento',$request->numero_documento)->first();

        $detalles = DetalleOrdenes::where('fk_id_tipo_documento',$request->fk_id_tipo_documento)
            ->where('fk_id_documento',$request->numero_documento)->get();

        foreach ( $detalles as $id_row => $detalle){

            $upcs[] = $detalle->upc->upc;

            $detalle_documento[$id_row] = [
                'fk_id_sku' => $detalle->fk_id_sku,
                'fk_id_upc' => $detalle->fk_id_upc,
                'fk_id_proyecto' => $detalle->fk_id_proyecto,
                'precio_unitario' => $detalle->precio_unitario,
                'cantidad' => $detalle->canitdad,
                'total' => $detalle->total,
                'fk_id_documento' => $detalle->fk_id_documento,
                'fk_id_tipo_documento' => $detalle->fk_id_tipo_documento,
//                'fk_id_tipo_documento_base' => $detalle->fk_id_tipo_documento_base,
                'fk_id_linea' => $detalle->id_documento_detalle,
//                'fk_id_documento_base' => $detalle->fk_id_documento_base,
                'sku' => $detalle->sku['sku'],
                'upc' => $detalle->upc->upc,
                'descripcion' => $detalle->upc->nombre_comercial,
                'cliente' => $detalle->proyecto->cliente->nombre_comercial,
                'proyecto' => $detalle->proyecto->proyecto,
                'precio_unitario' => round($detalle->precio_unitario,2),
                'total' => round($detalle->total,2),
                'cantidad' => $detalle->cantidad,
                'cantidad_surtida' => self::cantidad_surtida($detalle->fk_id_documento,$detalle->fk_id_upc),
                'sucursal' => $documento->sucursales->sucursal,
            ];
        }
        return [
            'sucursal' => $documento->sucursales->sucursal,
            'detalle'=>$detalle_documento,
            'proveedor' => $documento->proveedor->razon_social,
            'upcs' => $upcs,
        ];
    }

    public function cantidad_surtida($numero_documento,$fk_id_upc)
    {
        $no_entrada = Entradas::where('numero_documento',$numero_documento)->pluck('id_documento');
        $cantidad_surtida = EntradaDetalle::whereIn('fk_id_documento',$no_entrada)
            ->where('fk_id_upc',$fk_id_upc)
            ->sum('cantidad_surtida');

        return $cantidad_surtida;
    }

}
