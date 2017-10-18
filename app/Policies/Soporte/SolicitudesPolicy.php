<?php

namespace App\Policies\Soporte;

use App\Policies\PolicyBase;
use App\Http\Models\Administracion\Usuarios;

class SolicitudesPolicy extends PolicyBase
{
    public function update(Usuarios $usuario)
    {
        return false;
    }
    
    public function delete(Usuarios $usuario)
    {
        return false;
    }
}