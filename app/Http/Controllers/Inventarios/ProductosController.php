<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Models\Inventarios\Productos;

class ProductosController extends ControllerBase
{
    public function __construct(Productos $entity)
    {
        $this->entity = $entity;
    }

    public function obtenerSkus($company,Request $request)
    {
        $term = $request->term;
        $skus = Productos::where('activo','1')->where('sku','LIKE',$term.'%')->orWhere('nombre_comercial','LIKE','%'.$term.'%')->orWhere('descripcion','LIKE','%'.$term.'%')->get();
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
