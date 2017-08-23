<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Models\RecursosHumanos\Departamentos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Controllers\ControllerBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;

class DepartamentosController extends ControllerBase
{
    public function __construct(Departamentos $entity)
    {
        $this->entity = $entity;
        #$this->entity_name = strtolower(class_basename($entity));
        $this->companies = Empresas::all();
    }
}
