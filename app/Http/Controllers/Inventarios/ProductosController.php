<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Administracion\GrupoProductos;
use App\Http\Models\Administracion\SubgrupoProductos;
use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\Administracion\SeriesSkus;

class ProductosController extends ControllerBase
{
    public function __construct(Productos $entity)
    {
        $this->entity = $entity;
    }
    
    public function getDataView($entity = null)
    {
        $grupos = GrupoProductos::where('eliminar',0)->where('activo',1)->pluck('grupo','id_grupo')->sortBy('grupo');

        foreach ($grupos as $id => $grupo) {
            $subgrupo = SubgrupoProductos::where('fk_id_grupo',$id)->where('eliminar',0)->where('activo',1)->pluck('subgrupo','id_subgrupo')->sortBy('subgrupo')->toArray();
            if(!empty($subgrupo))
            { $subgrupos[$grupo] = $subgrupo; }
        }

        return [
            'seriesku' => SeriesSkus::where('activo',1)->pluck('nombre_serie','id_serie_sku')->sortBy('nombre_serie')->prepend('Selecciona una opcion...',''),
            'unidadmedida' => UnidadesMedidas::where('eliminar',0)->where('activo',1)->pluck('nombre','id_unidad_medida')->sortBy('nombre')->prepend('Selecciona una unidad de medida',''),
            'subgrupo' => collect($subgrupos ?? [])->prepend('Selecciona un subgrupo','')->toArray(),
        ];
    }

    public function obtenerSkus($company,Request $request)
    {
        $term = $request->term;
        $skus = Productos::where('activo','1')->where('sku','LIKE',$term.'%')->orWhere('nombre_comercial','LIKE','%'.$term.'%')->orWhere('descripcion','LIKE','%'.$term.'%')->get();

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
