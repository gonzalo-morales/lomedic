<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\RecursosHumanos\Empleados;

class SucursalesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Sucursales $entity)
	{
		$this->entity = $entity;
	}

    public function obtenerSucursales($company)
    {
        $sucursales = Sucursales::all()->where('activo','1');

        foreach($sucursales as $sucursal){
            $sucursal_data['id'] = (int)$sucursal->id_sucursal;
            $sucursal_data['text'] = $sucursal->nombre_sucursal;
            $sucursal_set[] = $sucursal_data;
        }
        return Response::json($sucursal_set);
    }

    public function sucursalesEmpleado($company,$id)
    {
        return Empleados::where('id_empleado',$id)->first()->sucursales()->get();
    }
}