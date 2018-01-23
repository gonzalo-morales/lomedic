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
use App\Http\Models\Administracion\FamiliasProductos;
use App\Http\Models\Administracion\PresentacionVenta;
use App\Http\Models\SociosNegocio\TiposSocioNegocio;
use App\Http\Models\Inventarios\Upcs;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use DB;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\Periodos;
use App\Http\Models\Administracion\MetodosValoracion;

class ProductosController extends ControllerBase
{
    public function __construct(Productos $entity)
    {
        $this->entity = $entity;
    }

    public function update(Request $request, $company, $id, $compact = false)
    {
        # ¿Usuario tiene permiso para actualizar?
        #$this->authorize('update', $this->entity);
        
        # Validamos request, si falla regresamos atras
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);
        
        DB::beginTransaction();
        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        
        if ($entity->save()) {
            if(isset($request->detalles)) {
                foreach ($request->detalles as $detalle) {
                    $sync[$detalle['fk_id_upc']] = $detalle;
                }
                $entity->findOrFail($id)->upcs()->sync($sync);
            }
            DB::commit();
            
            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();
            
            $this->log('update', $id);
            return $this->redirect('update');
        } else {
            DB::rollBack();
            $this->log('error_update', $id);
            return $this->redirect('error_update');
        }
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
            'familia' => FamiliasProductos::where('eliminar',0)->where('activo',1)->pluck('descripcion','id_familia')->sortBy('descripcion')->prepend('Selecciona una familia...',''),
            'presentacionventa' => PresentacionVenta::where('eliminar',0)->where('activo',1)->pluck('presentacion_venta','id_presentacion_venta')->sortBy('presentacion_venta')->prepend('Selecciona una Presentacion de venta...',''),
            'metodovaloracion' => MetodosValoracion::where('eliminar',0)->where('activo',1)->pluck('metodo_valoracion','id_metodo_valoracion')->sortBy('metodo_valoracion')->prepend('Selecciona una opcion...',''),
            'periodos' => Periodos::where('eliminar',0)->where('activo',1)->pluck('periodo','id_periodo')->sortBy('periodo')->prepend('Selecciona una opcion...',''),
            'sociosnegocio' => SociosNegocio::where('activo',1)->where('eliminar',0)->whereNotNull('fk_id_tipo_socio_compra')
                ->pluck('nombre_comercial','id_socio_negocio')->sortBy('nombre_comercial')->prepend('Selecciona un Proveedor...',''),
            'upcs' => Upcs::where('activo',1)->where('eliminar',0)->select('id_upc','upc')->pluck('upc','id_upc')->sortBy('upc')->prepend('Selecciona un upc',''),
            'api_js'=>Crypt::encryptString('"select": ["nombre_comercial", "descripcion","fk_id_laboratorio"], "conditions": [{"where": ["id_upc","$id_upc"]}], "with": ["laboratorio"]')
        ];
    }

    public function obtenerSkus($company,Request $request)
    {
        $term = $request->term;
        $id_proyecto = $request->fk_id_proyecto;
        $id_socio = $request->fk_id_socio_negocio;
        $skus = null;
        if($id_proyecto) {
            $skus = Productos::where('activo', true)->whereRaw("(sku ILIKE '%$term%' OR descripcion_corta ILIKE '%$term%' OR descripcion ILIKE '%$term%')")->
            whereHas('clave_cliente_productos', function ($q) use ($id_proyecto) {
                $q->whereHas('proyectos', function ($q2) use ($id_proyecto) {
                    $q2->where('id_proyecto', $id_proyecto);
                });
            })
                ->whereIn('id_sku',SociosNegocio::find($id_socio)->productos->pluck('fk_id_sku'))
                ->get();
        }else{
            $skus = Productos::where('activo','1')->whereRaw("(sku ILIKE '%$term%' OR descripcion_corta ILIKE '%$term%' OR descripcion ILIKE '%$term%')")->whereIn('id_sku',SociosNegocio::find($id_socio)->productos->pluck('fk_id_sku'))->get();
        }
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