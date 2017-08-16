<?php

namespace App\Http\Controllers\RecursosHumanos;


use App\Http\Controllers\ControllerBase;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\RecursosHumanos\Departamentos;
use App\Http\Models\RecursosHumanos\Puestos;
use App\Http\Models\Administracion\Sucursales;


class EmpleadosController extends ControllerBase
{
    public function __construct(Empleados $entity)
    {
        $this->entity = $entity;
        
        $this->departments = [];
        $Departamentos = Departamentos::select('id_departamento','descripcion')->where('eliminar','=','0')->where('activo','=','1')->get();
        foreach($Departamentos as $row) {
            $this->departments[$row->id_departamento] = $row->descripcion;
        }
        
        $this->companies = [];
        $companies = Empresas::select('id_empresa','nombre_comercial')->where('activo','=','1')->get();
        foreach($companies as $row) {
            $this->companies[$row->id_empresa] = $row->nombre_comercial;
        }
        
        $this->titles = [];
        $companies = Puestos::select('id_puesto','descripcion')->where('eliminar','=','0')->where('activo','=','1')->get();
        foreach($companies as $row) {
            $this->titles[$row->id_puesto] = $row->descripcion;
        }
        
        $this->offices = [];
        $companies = Sucursales::select('id_sucursal','nombre_sucursal')->where('eliminar','=','0')->where('activo','=','1')->get();
        foreach($companies as $row) {
            $this->offices[$row->id_sucursal] = $row->nombre_sucursal;
        }
    }
    /*
    public function index($company, $attributes = ['where'=>['eliminar = 0']])
    {
        $attributes = $attributes+['dataview'=>[
            'companies'=>$this->companies,
            'departments'=>$this->departments,
            'titles'=>$this->titles,
            #'offices'=>$this->offices
        ]];
        return parent::index($company, $attributes);
    }*/

    public function create($company, $attributes = [])
    {
        $attributes = $attributes+['dataview'=>[
                'companies'=>$this->companies,
                'departments'=>$this->departments,
                'titles'=>$this->titles,
                'offices'=>$this->offices
        ]];
        return parent::create($company, $attributes);
    }
    
    public function show($company, $id, $attributes = [])
    {
        $attributes = $attributes+['dataview'=>[
            'companies'=>$this->companies,
            'departments'=>$this->departments,
            'titles'=>$this->titles,
            'offices'=>$this->offices
        ]];
        return parent::show($company, $id, $attributes);
    }
    
    public function edit($company, $id, $attributes = [])
    {
        $attributes = $attributes+['dataview'=>[
            'companies'=>$this->companies,
            'departments'=>$this->departments,
            'titles'=>$this->titles,
            'offices'=>$this->offices
        ]];
        return parent::edit($company, $id, $attributes);
    }
    
}
