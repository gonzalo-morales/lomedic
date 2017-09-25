<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\Categorias;
use App\Http\Models\Soporte\Subcategorias;

class SubcategoriasController extends ControllerBase
{

    public function __construct(Subcategorias $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
        $this->categories = Categorias::select('id_categoria', 'categoria')->where('eliminar', '=', '0')
            ->where('activo', '=', '1')
            ->orderBy('categoria')
            ->get()
            ->pluck('categoria', 'id_categoria');
    }

    public function create($company, $attributes = [])
    {
        $attributes = $attributes + [
            'dataview' => [
                'categories' => $this->categories
            ]
        ];
        return parent::create($company, $attributes);
    }

    public function show($company, $id, $attributes = [])
    {
        $attributes = $attributes + [
            'dataview' => [
                'categories' => $this->categories
            ]
        ];
        return parent::show($company, $id, $attributes);
    }

    public function edit($company, $id, $attributes = [])
    {
        $attributes = $attributes + [
            'dataview' => [
                'categories' => $this->categories
            ]
        ];
        return parent::edit($company, $id, $attributes);
    }
}
