<?php

namespace App\Policies\Inventarios;

use App\Policies\PolicyBase;

class SolicitudesSalidaPolicy extends PolicyBase
{
	public function update(Usuarios $usuario)
    {
        return false;
    }
}