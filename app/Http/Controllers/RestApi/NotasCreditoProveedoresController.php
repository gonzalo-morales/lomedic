<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\Compras\NotasCreditoProveedor;
use App\Http\Models\Ventas\FacturasClientes;
use App\Http\Models\Ventas\NotasCreditoClientes;

class NotasCreditoProveedoresController extends Controller
{
    function __construct(NotasCreditoProveedor $entity)
    {
        $this->middleware('auth.api');
    }

    public function index()
    {
        $notas = NotasCreditoProveedor::whereNull('no_poliza')->whereNotNull('uuid')->orderBy('id_nota_credito_proveedor')->get();
        $notas->map(function($nota){
           return $nota->detalle;
        });
        $notas->map(function($nota){
            return $nota->relaciones;
        });
        foreach ($notas as $nota){
            $nota->relaciones->map(function ($relacion){
                return $relacion->documento;
            });
        }
        return response()->json(['status'=>200,'data'=>$notas], 200);
    }
}