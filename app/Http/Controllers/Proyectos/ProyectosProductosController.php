<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ProyectosProductos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProyectosProductosController extends ControllerBase
{
    public function __construct(ProyectosProductos $entity)
    {
        $this->entity = $entity;
    }

    public function create($company, $attributes = [])
    {
        $attributes += ['dataview'=>[
            'clientes' => SociosNegocio::where('activo', 1)
                ->whereHas('tipoSocio',
                    function($q) {
                        $q->where('fk_id_tipo_socio', 1);
                    })->pluck('nombre_corto','id_socio_negocio'),
        ]];
        return parent::create($company, $attributes);
    }

    public function store(Request $request, $company)
    {
        # ¿Usuario tiene permiso para crear?
//		$this->authorize('create', $this->entity);

        $guardados = 0;

        foreach ($request->_detalles as $proyecto_producto) {
            if(empty($proyecto_producto['fk_id_upc'])){
                $proyecto_producto['fk_id_upc'] = null;
            }
            if(!isset($proyecto_producto['activo'])){
                $proyecto_producto['activo'] = '0';
            }
            $isSuccess = $this->entity->create($proyecto_producto);
            if ($isSuccess) {
                # Eliminamos cache
                $this->log('store', $isSuccess->id_proyecto_producto);

                $guardados++;
            } else {
                $this->log('error_store');
                return $this->redirect('error_store');
            }
        }
        if($guardados>0){
            Cache::tags(getCacheTag('index'))->flush();
            return $this->redirect('store');
        }
    }
}
