<?php

namespace App\Policies\Compras;

use App\Policies\PolicyBase;
use App\Http\Models\Administracion\Usuarios;

class AutorizacionesPolicy extends PolicyBase
{
    public function create(Usuarios $usuario)
    {
        return false;
    }
}