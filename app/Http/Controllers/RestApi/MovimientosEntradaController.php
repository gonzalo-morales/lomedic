<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\Inventarios\Entradas;
use App\Http\Models\Inventarios\MovimientoAlmacen;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Models\Compras\Pagos;

class MovimientosEntradaController extends Controller
{
    function __construct(Entradas $entity)
    {
        $this->middleware('auth.api');
    }

    public function index()
    {
        $entradas = Entradas::whereNull('no_poliza')->orderBy('id_entrada_almacen')->get();
        $entradas->map(function($entrada){
           return $entrada->productos;
        });
        return response()->json(['status'=>200,'data'=>$entradas], 200);
    }
}