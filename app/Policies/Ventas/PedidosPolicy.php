<?php

namespace App\Policies\Ventas;

use App\Policies\PolicyBase;

class PedidosPolicy extends PolicyBase
{

    public function ImportarProductos()
    {
        return true;
    }
}