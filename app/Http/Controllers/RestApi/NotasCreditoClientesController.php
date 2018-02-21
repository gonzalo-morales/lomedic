<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\Ventas\FacturasClientes;
use App\Http\Models\Ventas\NotasCreditoClientes;

class NotasCreditoClientesController extends Controller
{
    function __construct(NotasCreditoClientes $entity)
    {
        $this->middleware('auth.api');
    }

    public function index()
    {
        $notas = NotasCreditoClientes::whereNull('no_poliza')->whereNull('no_movimiento')->where('total','>',0)
            ->whereNotNull('uuid')->orderBy('id_documento')->get();
        $notas->map(function ($nota){
            $return = collect();
            $return->cliente = $nota->cliente;
            if(isset($nota->cliente->cuentacliente))
                $return->cuenta = $nota->cliente->cuentacliente;
            if(isset($nota->cliente->cuentacliente->padre))
                $return->padre = $nota->cliente->cuentacliente->padre;
            if(isset($nota->cliente->cuentacliente->agrupadorcuenta))
                $return->agrupadorsat = $nota->cliente->cuentacliente->agrupadorcuenta;
            return $return;
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