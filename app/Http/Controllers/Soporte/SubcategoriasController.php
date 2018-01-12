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
    }
    
    public function getDataView($entity = null)
    {
        return [
            'categories' => Categorias::select('id_categoria', 'categoria')->where('eliminar', '=', '0')->where('activo', '=', '1')->orderBy('categoria')->pluck('categoria', 'id_categoria'),
        ];
    }
}