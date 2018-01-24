<?php
namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\RecursosHumanos\Departamentos;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\RecursosHumanos\Puestos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use DB;

class EmpleadosController extends ControllerBase
{

    public function __construct(Empleados $entity)
    {
        $this->entity = $entity;
    }
    
    public function getDataView($entity = null)
    {
        return [
            'companies' => Empresas::select('id_empresa', 'nombre_comercial')->activos()->orderBy('nombre_comercial')->pluck('nombre_comercial', 'id_empresa'),
            'departments' => Departamentos::select('descripcion', 'id_departamento')->activos()->orderBy('descripcion')->pluck('descripcion', 'id_departamento'),
            'titles' => Puestos::select('id_puesto', 'descripcion')->activos()->orderBy('descripcion')->pluck('descripcion', 'id_puesto'),
            'offices' => Sucursales::select('id_sucursal', 'sucursal')->activos()->orderBy('sucursal')->pluck('sucursal', 'id_sucursal'),
        ];
    }

    public function obtenerEmpleados($company)
    {
        return Empleados::activos()->select("id_empleado as id",DB::Raw("concat(nombre,' ',apellido_paterno,' ',apellido_materno) as text"))->toJson();
    }

    public function obtenerEmpleado($company)
    {
        $fk_id_empleado = Usuarios::where('id_usuario', Auth::id())->first()->fk_id_empleado;
        $empleado = $this->entity->findOrFail($fk_id_empleado)->id_empleado;
        return Response::json($empleado);
    }
}