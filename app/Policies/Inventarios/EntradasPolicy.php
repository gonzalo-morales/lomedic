<?php

namespace App\Policies\Inventarios;

use App\Policies\PolicyBase;

class EntradasPolicy extends PolicyBase
{
    public function update($user,$entity = null)
    {
        return false;
    }
    public function delete($user,$entity = null,$idOrIds = [])
    {
        return false;
    }
}