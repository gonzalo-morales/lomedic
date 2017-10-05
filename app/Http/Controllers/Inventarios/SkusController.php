<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Skus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SkusController extends ControllerBase
{
    public function __construct(Skus $entity)
    {
        $this->entity = $entity;
    }

    public function obtenerSkus($company,Request $request)
    {
        $term = $request->term;
        $skus = Skus::where('activo','1')->where('sku','LIKE',$term.'%')->orWhere('nombre_comercial','LIKE','%'.$term.'%')->orWhere('descripcion','LIKE','%'.$term.'%')->get();
//        dd($skus);
        $skus_set = [];
        foreach ($skus as $sku)
        {
            $sku_data['id'] = (int)$sku->id_sku;
            $sku_data['text'] = $sku->sku;
            $sku_data['nombre_comercial'] = $sku->nombre_comercial;
            $sku_data['descripcion'] = $sku->descripcion;
            $skus_set[] = $sku_data;
        }
        return Response::json($skus_set);
    }
}
