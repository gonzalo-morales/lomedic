<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Soporte\Acciones;
use App\Http\Models\Soporte\Subcategorias;

class AccionesController extends ControllerBase
{

    public function __construct()
    {
        $this->entity = new Acciones;
    }
    
    public function getDataView($entity = null)
    {
        return [
            'subcategorys' => Subcategorias::where('activo',1)->select('subcategoria','id_subcategoria')->orderBy('subcategoria')->pluck('subcategoria', 'id_subcategoria'),
        ];
    }
}