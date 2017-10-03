<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Almacenes;
use Illuminate\Http\Request;

class AlmacenesController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Almacenes $entity)
    {
        $this->entity = $entity;
    }

}