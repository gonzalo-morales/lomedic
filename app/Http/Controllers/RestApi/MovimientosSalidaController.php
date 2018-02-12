<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\Inventarios\Entradas;
use App\Http\Models\Inventarios\MovimientoAlmacen;
use App\Http\Models\Inventarios\Salidas;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Models\Compras\Pagos;

class MovimientosSalidaController extends Controller
{
    function __construct(Salidas $entity)
    {
        $this->middleware('auth.api');
    }

    public function index()
    {
        $salidas = Salidas::whereNull('no_poliza')->orderBy('id_salida')->get();
        $salidas->map(function($salida){
           return $salida->detalle;
        });
        return response()->json(['status'=>200,'data'=>$salidas], 200);
    }
}