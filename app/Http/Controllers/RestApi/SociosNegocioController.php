<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Ventas\FacturasClientes;

class SociosNegocioController extends Controller
{
    function __construct(SociosNegocio $entity)
    {
        $this->middleware('auth.api');
    }

    public function index()
    {
//        $socios_negocio = FacturasClientes::whereNull('no_poliza')->whereNotNull('uuid')->orderBy('id_documento')->get();
//        return response()->json(['status'=>200,'data'=>$facturas], 200);
    }
}