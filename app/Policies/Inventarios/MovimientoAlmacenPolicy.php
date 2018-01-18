<?php

namespace App\Policies\Inventarios;

use App\Policies\PolicyBase;
use App\Http\Models\Administracion\Usuarios;

class MovimientoAlmacenPolicy extends PolicyBase
{

	public function update(Usuarios $usuario)
    {
        return false;
    }
}