<?php

namespace App\Policies\Soporte;

use App\Policies\PolicyBase;
use App\Http\Models\Administracion\Usuarios;

class SolicitudesPolicy extends PolicyBase
{
    public function update($usuario, $entity = null)
    {
        return false;
    }

    public function delete($usuario, $entity = null,$idOrIds = [])
    {
        return false;
    }
}