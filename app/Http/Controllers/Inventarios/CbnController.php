<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Cbn;

class CbnController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Cbn;
    }
}