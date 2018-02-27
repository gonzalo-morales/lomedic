<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 04/09/2017
 * Time: 12:37
 */

namespace App\Http\Controllers\Servicios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\Servicios\RequisicionesHospitalarias;
use App\Http\Models\Administracion\Areas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Programas;
use Illuminate\Support\Facades\Auth;

use DB;

class RequisicionesHospitalariasController extends ControllerBase
{


    public function __construct()
    {
        $this->entity = new RequisicionesHospitalarias;
    }

    public function getDataView($entity = null)
    {
        return [
            'localidades' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo',1)->pluck('sucursal', 'id_sucursal')->prepend('...', ''),
            'solicitante' => Usuarios::select(['id_usuario','nombre_corto'])->where('activo',1)->pluck('nombre_corto','id_usuario')->prepend('...', ''),
            'areas' => Areas::all()->pluck('area', 'id_area')->prepend('...', ''),
            'programas' => Programas::get()->pluck('nombre_programa', 'id_programa')->prepend('Sin programa', ''),
            'proyectos' => empty($entity) ? [] : Proyectos::select(['id_proyecto','proyecto'])->pluck('proyecto','id_proyecto')->prepend('...', ''),
            'fk_id_usuario_captura' =>  Auth::id(),
        ];

    }



}