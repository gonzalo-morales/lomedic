<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\Compras\Pagos;

class PagosController extends Controller
{
    function __construct(Pagos $entity)
    {
        $this->middleware('auth.api');
    }

    public function index()
    {

        $pagos = Pagos::whereNull('no_poliza')->orderBy('id_pago')->get();
        $pagos->map(function($pago){
           return $pago->detalle;
        });
        return response()->json(['status'=>200,'data'=>$pagos], 200);
    }
}