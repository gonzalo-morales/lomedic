<?php
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Soporte\Solicitudes;

function main_menu()
{
    return \Auth::user()->modulos_anidados(dataCompany());
}

function empresa_menu()
{
    return Empresas::where('conexion','!=', null)->where('conexion', '!=', request()->company)->get();
}

function ticket_menu()
{
    return Solicitudes::where('fk_id_empleado_solicitud',Auth::id())->where('fecha_hora_resolucion',null)->take(5)->get();
}