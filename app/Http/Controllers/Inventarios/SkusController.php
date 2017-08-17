<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Skus;
use Illuminate\Support\Facades\Response;

class SkusController extends ControllerBase
{
    public function __construct(Skus $entity)
    {
        $this->entity = $entity;
    }

    public function obtenerSkus()
    {
        $skus = Skus::all()->where('activo','1');
        foreach ($skus as $sku)
        {
            $sku_data['id'] = (int)$sku->id_sku;
            $sku_data['text'] = $sku->sku;
            $skus_set[] = $sku_data;
        }
        return Response::json($skus_set);
    }
}
