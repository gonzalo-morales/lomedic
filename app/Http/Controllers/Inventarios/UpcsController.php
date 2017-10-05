<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Upcs;

class UpcsController extends ControllerBase
{
    public function __construct(Upcs $entity)
    {
        $this->entity = $entity;
    }

    public function obtenerUpcs($company,$id)
    {
        return Upcs::where('fk_id_sku',$id)->select('id_upc as id','descripcion as text')->get();
    }

}
