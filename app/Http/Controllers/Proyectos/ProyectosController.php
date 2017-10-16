<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ClasificacionesProyectos;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use Illuminate\Support\Facades\Response;

class ProyectosController extends ControllerBase
{
    public function __construct(Proyectos $entity)
    {
        $this->entity = $entity;
    }

    public function create($company, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
            'clientes' => SociosNegocio::where('activo', 1)
                ->whereHas('tipoSocio',
                    function($q) {
                    $q->where('fk_id_tipo_socio', 1);
                })->pluck('nombre_corto','id_socio_negocio'),
            'clasificaciones' => ClasificacionesProyectos::where('activo',1)
                ->pluck('clasificacion','id_clasificacion_proyecto'),
            ]];

        return parent::create($company, $attributes);
    }

    public function obtenerProyectos()
    {
        $proyectos = Proyectos::all()->where('activo','1');
        foreach ($proyectos as $proyecto)
        {
            $proyecto_data['id'] = (int)$proyecto->id_proyecto;
            $proyecto_data['text'] = $proyecto->proyecto;
            $proyectos_set[] = $proyecto_data;
        }
        return Response::json($proyectos_set);
    }

    public function obtenerProyectosCliente($company,$id)
    {
        return Response::json($this->entity->where('fk_id_cliente',$id)->select('proyecto as text','id_proyecto as id')->get());
    }
}
