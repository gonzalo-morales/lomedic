<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\Ventas\FacturasClientes;

class FacturasClientesController extends Controller
{
    function __construct(FacturasClientes $entity)
    {
        $this->middleware('auth.api');
    }

    public function index()
    {
        $facturas = FacturasClientes::whereNull('no_poliza')->whereNotNull('uuid')->orderBy('id_documento')->get();
        $facturas->map(function($factura){
           return $factura->detalle;
        });
        $facturas->map(function($factura){
            return $factura->relaciones;
        });
        foreach ($facturas as $factura){
            $factura->relaciones->map(function ($relacion){
                return $relacion->documento;
            });
        }
        return response()->json(['status'=>200,'data'=>$facturas], 200);
    }
}