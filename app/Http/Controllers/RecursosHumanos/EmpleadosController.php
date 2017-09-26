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
        
        $this->departments = Departamentos::select('descripcion', 'id_departamento')->where('eliminar', '=', '0')
            ->where('activo', '=', '1')
            ->orderBy('descripcion')
            ->get()
            ->pluck('descripcion', 'id_departamento');
        
        $this->companies = Empresas::select('id_empresa', 'nombre_comercial')->where('activo', '=', '1')
            ->orderBy('nombre_comercial')
            ->get()
            ->pluck('nombre_comercial', 'id_empresa');
        
        $this->titles = [];
        $this->titles = Puestos::select('id_puesto', 'descripcion')->where('eliminar', '=', '0')
            ->where('activo', '=', '1')
            ->orderBy('descripcion')
            ->get()
            ->pluck('descripcion', 'id_puesto');
        
        $this->offices = Sucursales::select('id_sucursal', 'nombre_sucursal')->where('eliminar', '=', '0')
            ->where('activo', '=', '1')
            ->orderBy('nombre_sucursal')
            ->get()
            ->pluck('nombre_sucursal', 'id_sucursal');
    }

    public function create($company, $attributes = [])
    {
        $attributes = $attributes + [
            'dataview' => [
                'companies' => $this->companies,
                'departments' => $this->departments,
                'titles' => $this->titles,
                'offices' => $this->offices
            ]
        ];
        return parent::create($company, $attributes);
    }

    public function show($company, $id, $attributes = [])
    {
        $attributes = $attributes + [
            'dataview' => [
                'companies' => $this->companies,
                'departments' => $this->departments,
                'titles' => $this->titles,
                'offices' => $this->offices
            ]
        ];
        return parent::show($company, $id, $attributes);
    }

    public function edit($company, $id, $attributes = [])
    {
        $attributes = $attributes + [
            'dataview' => [
                'companies' => $this->companies,
                'departments' => $this->departments,
                'titles' => $this->titles,
                'offices' => $this->offices
            ]
        ];
        return parent::edit($company, $id, $attributes);
    }

    public function obtenerEmpleados($company)
    {
        return Empleados::where('activo','1')->where('eliminar','0')->select("id_empleado as id",DB::Raw("concat(nombre,' ',apellido_paterno,' ',apellido_materno) as text"))->get()->toJson();
    }

    public function obtenerEmpleado($company)
    {
        $fk_id_empleado = Usuarios::where('id_usuario', Auth::id())->first()->fk_id_empleado;
        $empleado = $this->entity->findOrFail($fk_id_empleado)->id_empleado;
        return Response::json($empleado);
    }
}
