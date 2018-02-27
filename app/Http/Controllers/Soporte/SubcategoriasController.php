<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\Categorias;
use App\Http\Models\Soporte\Subcategorias;

class SubcategoriasController extends ControllerBase
{

    public function __construct()
    {
        $this->entity = new Subcategorias;
    }
    
    public function getDataView($entity = null)
    {
        return [
            'categories' => Categorias::where('activo',1)->select('id_categoria', 'categoria')->orderBy('categoria')->pluck('categoria', 'id_categoria'),
        ];
    }
}