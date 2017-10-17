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
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Administracion\Familiasproductos;
use App\Http\Models\Administracion\PresentacionVenta;
use App\Http\Models\SociosNegocio\TiposSocioNegocio;

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
            'impuesto' => Impuestos::where('eliminar',0)->where('activo',1)->pluck('impuesto','id_impuesto')->sortBy('impuesto')->prepend('Selecciona una opcion...',''),
            'familia' => Familiasproductos::where('eliminar',0)->where('activo',1)->pluck('descripcion','id_familia')->sortBy('descripcion')->prepend('Selecciona una familia...',''),
            'presentacionventa' => PresentacionVenta::where('eliminar',0)->where('activo',1)->pluck('presentacion_venta','id_presentacion_venta')->sortBy('presentacion_venta')->prepend('Selecciona una Presentacion de venta...',''),
            'sociosnegocio' => TiposSocioNegocio::where('id_tipo_socio',2)->first()->sng()->where('eliminar',0)->where('activo',1)
            ->pluck('nombre_corto','id_socio_negocio')->sortBy('nombre_corto')->prepend('Selecciona un Proveedor...',''),
        ];
    }

    public function obtenerSkus($company,Request $request)
    {
        $term = $request->term;
        $skus = Productos::where('activo','1')->where('sku','LIKE','%'.$term.'%')->orWhere('descripcion_corta','LIKE','%'.$term.'%')->orWhere('descripcion','LIKE','%'.$term.'%')->get();

        $skus_set = [];
        foreach ($skus as $sku)
        {
            $sku_data['id'] = (int)$sku->id_sku;
            $sku_data['text'] = $sku->sku;
            $sku_data['descripcion_corta'] = $sku->descripcion_corta;
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
