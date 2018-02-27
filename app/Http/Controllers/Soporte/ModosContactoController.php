<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\ModosContacto;

class ModosContactoController extends ControllerBase
{

    public function __construct()
    {
        $this->entity = new ModosContacto;
    }
}
