<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Models\Compras\Pagos;

class PagosController extends Controller
{
    function __construct(Pagos $entity)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return response()->json(['status'=>'ok','data'=>"INDEX"], 200);
    }

    public function show()
    {
        return response()->json(['status'=>'ok','data'=>"SHOW"], 200);
    }
}