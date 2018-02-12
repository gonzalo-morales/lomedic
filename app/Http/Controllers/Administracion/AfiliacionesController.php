<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Afiliaciones;
use Illuminate\Http\Request;
use App\Http\Models\Administracion\Parentescos;
use DB;


class AfiliacionesController extends ControllerBase
{

    public function __construct(Afiliaciones $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {
        return [
            'afiliados' => empty($entity) ? Afiliaciones::where('id_dependiente',1)->selectRAW("CONCAT(id_afiliacion,' ','-',' ',paterno,' ',materno,' ',nombre) as nombre_afiliado, id_afiliacion")->orderBy('id_afiliacion')->pluck('nombre_afiliado', 'id_afiliacion')->prepend('Nuevo paciente', '') : Afiliaciones::all()->where('id_afiliacion', $entity->id_afiliacion),
            'parentescos' => Parentescos::where('activo',1)->where('eliminar',0)->pluck('nombre','id_parentesco'),
        ];

    }

    public function getDependientes($company, Request $request)
    {
        $json = [];

        $afiliaciones = Afiliaciones::where('id_afiliacion',$request->fk_id_afiliacion)
            ->selectRAW("CONCAT(nombre,' ',paterno,' ',materno) as nombre, id_afiliacion as numero_afiliacion,*")
            ->where('eliminar',0)
            ->get();
        foreach ($afiliaciones as $afiliado) {
            $json[] = [
                'id_afiliacion' => $afiliado->numero_afiliacion,
                'nombre' => $afiliado->nombre,
                'genero' => $afiliado->genero,
                'parentesco' => $afiliado->parentesco['nombre'],
                'fecha_nacimiento' => $afiliado->fecha_nacimiento,
            ];
        }
        return $json;
    }


}
