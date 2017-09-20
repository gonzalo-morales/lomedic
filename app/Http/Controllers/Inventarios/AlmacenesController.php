<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Almacenes;
use Illuminate\Support\Facades\Response;

class AlmacenesController extends ControllerBase
{
    public function __construct(Almacenes $entity)
    {
        $this->entity = $entity;
    }

}
