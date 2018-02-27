<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\Categorias;

class CategoriasController extends ControllerBase
{

    public function __construct()
    {
        $this->entity = new Categorias;
    }
}
