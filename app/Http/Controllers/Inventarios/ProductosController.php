<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Administracion\GrupoProductos;
use App\Http\Models\Administracion\SubgrupoProductos;
use App\Http\Models\Administracion\UnidadesMedidas;

class ProductosController extends ControllerBase
{
    public function __construct(Productos $entity)
    {
        $this->entity = $entity;
    }
    
    public function getDataView($entity = null)
    {
        return [
            'unidadmedida' => UnidadesMedidas::where('eliminar',0)->where('activo',1)->pluck('nombre','id_unidad_medida'),
            'grupo' => GrupoProductos::where('eliminar',0)->where('activo',1)->pluck('grupo','id_grupo'),
            'subgrupo' => SubgrupoProductos::where('eliminar',0)->where('activo',1)->pluck('subgrupo','id_subgrupo'),
        ];
    }

    public function obtenerSkus($company,Request $request)
    {
        $term = $request->term;
        $skus = Productos::where('activo','1')->where('sku','LIKE',$term.'%')->orWhere('nombre_comercial','LIKE','%'.$term.'%')->orWhere('descripcion','LIKE','%'.$term.'%')->get();
//        dd($skus);
        $skus_set = [];
        foreach ($skus as $sku)
        {
            $sku_data['id'] = (int)$sku->id_sku;
            $sku_data['text'] = $sku->sku;
            $sku_data['nombre_comercial'] = $sku->nombre_comercial;
            $sku_data['descripcion'] = $sku->descripcion;
            $skus_set[] = $sku_data;
        }
        return Response::json($skus_set);
    }

    public function obtenerUpcs($company, $id)
    {
        return $this->entity->find($id)->upcs()->select('id_upc as id','upc as text','descripcion')->get();
    }
}
