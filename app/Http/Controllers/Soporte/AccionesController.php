<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\Acciones;
use App\Http\Models\Soporte\Subcategorias;

class AccionesController extends ControllerBase
{

    public function __construct(Acciones $entity)
    {
        $this->entity = $entity;
        
        $this->subcategorys = Subcategorias::select('subcategoria', 'id_subcategoria')->where('eliminar', '=', '0')
            ->where('activo', '=', '1')
            ->orderBy('subcategoria')
            ->get()
            ->pluck('subcategoria', 'id_subcategoria');
    }

    public function create($company, $attributes = ['where'=>['eliminar = 0']])
    {
        $attributes = $attributes + [
            'dataview' => [
                'subcategorys' => $this->subcategorys
            ]
        ];
        return parent::create($company, $attributes);
    }

    public function show($company, $id, $attributes = ['where'=>['eliminar = 0']])
    {
        $attributes = $attributes + [
            'dataview' => [
                'subcategorys' => $this->subcategorys
            ]
        ];
        return parent::show($company, $id, $attributes);
    }

    public function edit($company, $id, $attributes = ['where'=>['eliminar = 0']])
    {
        $attributes = $attributes + [
            'dataview' => [
                'subcategorys' => $this->subcategorys
            ]
        ];
        return parent::edit($company, $id, $attributes);
    }
}
