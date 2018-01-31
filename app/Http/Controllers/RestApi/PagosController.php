<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\Compras\Pagos;

class PagosController extends Controller
{
    public function index()
    {
        return response()->json(['status'=>'ok','data'=>Pagos::all()], 200);
    }
}