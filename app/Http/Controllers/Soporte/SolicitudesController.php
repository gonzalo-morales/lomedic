<?php

namespace App\Http\Controllers\Soporte;

//Used models
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Soporte\EstatusTickets;
use App\Http\Models\Soporte\Categorias;
use App\Http\Models\Soporte\Solicitudes;
use App\Http\Models\Soporte\Subcategorias;
use App\Http\Models\Soporte\Acciones;
use App\Http\Models\Soporte\Prioridades;
use App\Http\Models\Soporte\ModosContacto;
use App\Http\Models\Soporte\Impactos;
use App\Http\Models\Soporte\Urgencias;
use App\Http\Models\Logs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SolicitudesController extends Controller
{
    public function __construct(Solicitudes $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
    }

    public function index($company)
    {
        Logs::createLog($this->entity->getTable(),$company,null,'index', null);

        return view(Route::currentRouteName(),[
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->all()->where('eliminar','0'),
        ]);
    }
}
