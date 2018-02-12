<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\Compras\FacturasProveedores;

class FacturasProveedoresController extends Controller
{
    function __construct(FacturasProveedores $entity)
    {
        $this->middleware('auth.api');
    }

    public function index()
    {
        $facturas = FacturasProveedores::whereNull('no_poliza')->whereNotNull('uuid')->orderBy('id_factura_proveedor')->get();
        $facturas->map(function($factura){
           return $factura->detalle_facturas_proveedores;
        });
        $facturas->map(function($factura){
            return $factura->notas;
        });
//        foreach ($facturas as $factura){
//            $factura->relaciones->map(function ($relacion){
//                return $relacion->documento;
//            });
//        }
        return response()->json(['status'=>200,'data'=>$facturas], 200);
    }
}