<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Administracion\GrupoProductos;
use App\Http\Models\Administracion\SubgrupoProductos;
use App\Http\Models\Administracion\SeriesSkus;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Administracion\FamiliasProductos;
use App\Http\Models\Administracion\Presentaciones;
use App\Http\Models\Administracion\Sales;
use App\Http\Models\Administracion\FormaFarmaceutica;
use App\Http\Models\SociosNegocio\TiposSocioNegocio;
use App\Http\Models\Inventarios\Cbn;
use App\Http\Models\Inventarios\Upcs;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use DB;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\Periodos;
use App\Http\Models\Administracion\MetodosValoracion;
use App\Http\Models\Administracion\Especificaciones;
use App\Http\Models\Proyectos\ClaveClienteProductos;

class ProductosController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Productos;
    }

    // public function update(Request $request, $company, $id, $compact = false)
    // {
    //     # ¿Usuario tiene permiso para actualizar?
    //     #$this->authorize('update', $this->entity);

    //     # Validamos request, si falla regresamos atras
    //     $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);

    //     DB::beginTransaction();
    //     $entity = $this->entity->findOrFail($id);
    //     $entity->fill($request->all());

    //     if ($entity->save()) {
    //         if(isset($request->detalles)) {
    //             foreach ($request->detalles as $detalle) {
    //                 $sync[$detalle['fk_id_upc']] = $detalle;
    //             }
    //             $entity->findOrFail($id)->upcs()->sync($sync);
    //         }
    //         DB::commit();

    //         # Eliminamos cache
    //         Cache::tags(getCacheTag('index'))->flush();
    //         #$this->log('update', $id);
    //         return $this->redirect('update');
    //     } else {
    //         DB::rollBack();
    //         #$this->log('error_update', $id);
    //         return $this->redirect('error_update');
    //     }
    // }

    public function getDataView($entity = null)
    {
        $grupos = GrupoProductos::where('activo',1)->get()->sortBy('grupo');

        foreach ($grupos as $grupo) {
            $_subgrupos = SubgrupoProductos::where('fk_id_grupo',$grupo['id_grupo'])->where('activo',1)->get()->sortBy('subgrupo')->toArray();
            if(!empty($_subgrupos)){
                    $subgrupos[$grupo['grupo']] = collect($_subgrupos)->pluck('subgrupo','id_subgrupo');
            }
            $subgrupo_data[$grupo['grupo']] = collect($_subgrupos)->mapWithKeys(function ($item) use ($grupo){
                $sales = $grupo->sales  ? 'true' : 'false';
                $especificaciones = $grupo->especificaciones ? 'true' : 'false';
                return [
                    $item['id_subgrupo'] => [
                        "data-grupo"=>$item['fk_id_grupo'],
                        "data-sales"=>$sales,
                        "data-especificaciones"=>$especificaciones]
                ];
            })->toArray();
        }

        return [
            'especificaciones' => Especificaciones::where('activo',1)->pluck('especificacion','id_especificacion')->sortBy('especificacion'),
            'seriesku'         => SeriesSkus::where('activo',1)->pluck('nombre_serie','id_serie_sku')->sortBy('nombre_serie')->prepend('...',''),
            'subgrupo'         => collect($subgrupos ?? [])->toArray(),
            'subgrupo_data'    => $subgrupo_data ?? [],
            'formafarmaceutica'=> FormaFarmaceutica::where('activo',1)->pluck('forma_farmaceutica','id_forma_farmaceutica')->sortBy('forma_farmaceutica'),
            'cbn'              => Cbn::where('activo',1)->selectRaw("Concat(clave_cbn,'-',descripcion) as text,id_cbn as id")->pluck('text','id')->prepend('...',''),
            'impuesto'         => Impuestos::where('activo',1)->pluck('impuesto','id_impuesto')->sortBy('impuesto')->prepend('...',''),
            'familia'          => FamiliasProductos::where('activo',1)->pluck('descripcion','id_familia')->sortBy('descripcion')->prepend('...',''),
            'metodovaloracion' => MetodosValoracion::where('activo',1)->pluck('metodo_valoracion','id_metodo_valoracion')->sortBy('metodo_valoracion')->prepend('...',''),
            'periodos'         => Periodos::where('activo',1)->pluck('periodo','id_periodo')->sortBy('periodo')->prepend('...',''),
            'sociosnegocio'    => SociosNegocio::where('activo',1)->whereNotNull('fk_id_tipo_socio_compra')
                                                ->pluck('nombre_comercial','id_socio_negocio')->sortBy('nombre_comercial')->prepend('...',''),
            'presentaciones'   => Presentaciones::join('gen_cat_unidades_medidas', 'gen_cat_unidades_medidas.id_unidad_medida', '=', 'adm_cat_presentaciones.fk_id_unidad_medida')
                                                ->whereNotNull('clave')->selectRaw("Concat(cantidad,' ',clave) as text, id_presentacion as id")->pluck('text','id'),
            'sales'            => Sales::where('activo',1)->pluck('nombre','id_sal')->sortBy('nombre'),
            'api_js'           => Crypt::encryptString('"select": ["nombre_comercial", "descripcion","fk_id_laboratorio"],"with": ["laboratorio"], "conditions": [{"where": ["id_upc","$id_upc"]}]'),
            // 'upcs_js'          => Crypt::encryptString('
            // "select":["id_upc","upc","descripcion","marca","nombre_comercial"],
            // "conditions":
            //     [{"where":["fk_id_forma_farmaceutica",$fk_id_forma_farmaceutica],["fk_id_presentaciones", $fk_id_presentaciones]}],
            // "whereHas": [{"presentaciones":{"where":["fk_id_presentaciones", "$fk_id_presentaciones"],["fk_id_sal", "$fk_id_sal"]}}]
            // ')
        ];
    }

    public function store(Request $request, $company, $compact = false)
    {
        $return = parent::store($request, $company, true);

        if(is_array($return))
        {
            if($request->especificaciones)
            {
                $entity = $return['entity'];
                $sync = [];
                foreach ($request->especificaciones as $especificacion){
                    $sync[]=['fk_id_especificacion'=>$especificacion['fk_id_especificacion']];
                }
                $insert = $entity->especificaciones()->sync($sync);
    
                if($insert)
                {
                    return $return['redirect'];
                }
                else
                {
                    return $this->redirect('error_store');
                }
            } 
            else
            {
                return $return['redirect'];
            }
            
        }
        else
        {
            return $this->redirect('error_store');
        }
    }

    public function update(Request $request, $company, $id, $compact = false)
    {
        $return = parent::update($request, $company, $id, true);
        if(is_array($return))
        {
            if($request->especificaciones)
            {
                $entity = $return['entity'];
                $sync = [];
                foreach ($request->especificaciones as $especificacion){
                    $sync[]=['fk_id_especificacion'=>$especificacion['fk_id_especificacion']];
                }
                $insert = $entity->especificaciones()->sync($sync);
    
                if($insert)
                {
                    return $return['redirect'];
                }
                else
                {
                    return $this->redirect('error_store');
                }
            } 
            else
            {
                return $return['redirect'];
            }
            
        }
        else
        {
            return $this->redirect('error_store');
        }
    }

    public function obtenerSkus($company,Request $request)
    {
        $term = $request->term;     
        $id_proyecto = $request->fk_id_proyecto;
        $id_socio = $request->fk_id_socio_negocio;
        $skus = null;
        if($id_proyecto) {
            $skus = Productos::where('activo',1)->whereRaw("(sku ILIKE '%$term%' OR descripcion_corta ILIKE '%$term%' OR descripcion ILIKE '%$term%')")->
            whereHas('clave_cliente_productos', function ($q) use ($id_proyecto) {
                $q->whereHas('proyectos', function ($q2) use ($id_proyecto) {
                    $q2->where('id_proyecto', $id_proyecto);
                });
            })
                ->whereIn('id_sku',SociosNegocio::find($id_socio)->productos->pluck('fk_id_sku'))
                ->get();
        }else{
            $skus = Productos::where('activo',1)->whereRaw("(sku ILIKE '%$term%' OR descripcion_corta ILIKE '%$term%' OR descripcion ILIKE '%$term%')")->whereIn('id_sku',SociosNegocio::find($id_socio)->productos->pluck('fk_id_sku'))->get();
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

    public function getUpcs()
    {
        $id_forma_farmaceutica = request()->id_forma;
        $id_presentaciones = request()->id_presentaciones;
        $data_sales = json_decode(request()->arr_sales);
        $data_especificaciones = json_decode(request()->arr_especificaciones);
        $module_upc = request()->upc;
        $upcs = Upcs::where('fk_id_forma_farmaceutica',$id_forma_farmaceutica)->where('fk_id_presentaciones',$id_presentaciones)->where('activo',1)->with('laboratorio')->get();
        if(count($data_sales) > 0 && count($data_especificaciones) > 0)
        {
            $upcsDone = $this->filterUpcs($upcs,$data_sales,$data_especificaciones);
            if($module_upc !== 'true')
            {
                return json_encode($upcsDone);
            }
            else
            {
                $ids_upc = $upcsDone->map(function($upc){
                    return $upc->id_upc;
                })->toArray();
                $idsClaves =  ClaveClienteProductos::where('activo',1)->whereHas('productos',function($c) use ($ids_upc){
                    $c->whereIn('fk_id_upc',$ids_upc);
                });
                return json_encode($idsClaves);
            }
        }
        else
        {
            $upcsDone = $this->filterUpcs($upcs,$data_sales,$data_especificaciones);
            if($module_upc !== 'true')
            {
                return json_encode($upcsDone);
            }
            else
            {
                $ids_upc = $upcsDone->map(function($upc){
                    return $upc->id_upc;
                })->toArray();
                $idsClaves =  ClaveClienteProductos::where('activo',1)->whereHas('productos',function($c) use ($ids_upc){
                    $c->whereIn('fk_id_upc',$ids_upc);
                });
                return json_encode($idsClaves);
            }
        }
    }

    public function filterUpcs($upcs,$data_sales,$data_especificaciones)
    {  
        $upcFiltered = $upcs->filter(function($upc) use ($data_sales,$data_especificaciones){
            $numEspecRelations = $upc->especificaciones()->count();
            $numPreseRelations = $upc->presentaciones()->count();
            $numEspecData = count($data_especificaciones);
            $numSalesData = count($data_sales);
            $statusEspecificaciones = false;
            $statusPresentaciones = false;
            if($numEspecRelations > 0)
            {
                foreach ($upc->especificaciones as $especificacion)
                {
                    if($numEspecRelations > 1)
                    {
                        $id_founded[] = array_search($especificacion->id_especificacion, $data_especificaciones);
                        if($numEspecRelations == $numEspecData)
                        {
                            if(!in_array(false,$id_founded,true))
                            {
                                $statusEspecificaciones = true;
                            }
                        }

                    }
                    else
                    {
                        foreach ($data_especificaciones as $value)
                        {
                            $value = is_object($value) ? $value->id_especificacion : $value;
                            if($especificacion->id_especificacion == $value)
                            {
                                $statusEspecificaciones = true;
                            }
                        }            
                    }
                }
            }
            if($numPreseRelations > 0)
            {
                if($numPreseRelations > 1 && $numSalesData > 1)
                {
                    foreach ($upc->presentaciones as $presentacion)
                    {
                        foreach ($data_sales as $value)
                        {
                            if($presentacion->fk_id_presentaciones == $value->fk_id_presentaciones && $presentacion->fk_id_sal == $value->fk_id_sal)
                            {
                                $id_presentaciones_founded[] = true;
                            }
                        }
                    }
                    if($numPreseRelations == $numSalesData)
                    {
                        if(!in_array(false,$id_presentaciones_founded,true) && count($id_presentaciones_founded) == $numPreseRelations)
                        {
                            $statusPresentaciones = true;
                        }
                    }
                }
                if($numPreseRelations == 1)
                {
                    foreach ($upc->presentaciones as $presentacion)
                    {
                        foreach ($data_sales as $value)
                        {
                            if($presentacion->fk_id_presentaciones == $value->fk_id_presentaciones && $presentacion->fk_id_sal == $value->fk_id_sal)
                            {
                                $statusPresentaciones = true;
                            }
                        }
                    }
                }
            }
            if(($statusEspecificaciones == true && $statusPresentaciones == true) && ($upc->especificaciones()->exists() && $upc->presentaciones()->exists()))
            {
                return $upc;
            }
            else if($statusEspecificaciones == true && ($upc->especificaciones()->exists() && !$upc->presentaciones()->exists()))
            {
                return $upc;
            }
            else if($statusPresentaciones == true && ($upc->presentaciones()->exists() && !$upc->especificaciones()->exists()))
            {
                return $upc;
            }
            else
            {
                return false;
            }
        });
        return $upcFiltered;
    }

    public function getThisSkus($company, $id, Request $request)
    {
        $sku_data = $this->entity->find($id);
        $sku_forma = $sku_data->fk_id_forma_farmaceutica;
        $sku_presen = $sku_data->fk_id_presentaciones;
        $bolean = $sku_data->material_curacion;
        $upcs = Upcs::where('fk_id_forma_farmaceutica',$sku_forma)->where('fk_id_presentaciones',$sku_presen)->where('material_curacion',$bolean)->where('activo',1)->with('laboratorio')->get();

        if($bolean == false)
        {
            $sales = $sku_data->presentaciones()->get();
            return $this->filterUpcs($upcs,$sales,'presentaciones');
        } 
        else
        {
            $sku_all_espe = $sku_data->especificaciones()->get();
            foreach ($sku_all_espe as $key => $especificacion) {
                $especificaciones[] = $especificacion->id_especificacion;
            }
            return $this->filterUpcs($upcs,$especificaciones,'especificaciones');
        }
        return false;
    }

    public function getRelatedSkus()
    {
        $fk_id_forma_farmaceutica = request()->fk_id_forma_farmaceutica;
        $fk_id_presentaciones = request()->fk_id_presentaciones;
        $sales = json_decode(request()->sales);
        $especificaciones = json_decode(request()->especificaciones);
        $material_curacion = json_decode(request()->material_curacion) == 'true' ? 1 : 0;
        $skus = $fk_id_presentaciones > 0 ? Productos::select('id_sku','sku','fk_id_presentaciones','fk_id_forma_farmaceutica','material_curacion')->where('material_curacion',$material_curacion)->where('fk_id_forma_farmaceutica',$fk_id_forma_farmaceutica)->where('fk_id_presentaciones',$fk_id_presentaciones)->where('activo',1)->get() : Productos::select('id_sku','sku','fk_id_presentaciones','fk_id_forma_farmaceutica','material_curacion')->where('material_curacion',$material_curacion)->where('fk_id_forma_farmaceutica',$fk_id_forma_farmaceutica)->where('activo',1)->get();

        if(!empty($sales))
            $skus = collect(json_decode($this->filterSkus($skus,$sales,'presentaciones')));
        elseif (!empty($especificaciones))
            $skus = collect(json_decode($this->filterSkus($skus,$especificaciones,'especificaciones')));

        $skus->filter(function ($sku)use($sales,$especificaciones){
            $upcs = $sku->fk_id_presentaciones > 0 ? Upcs::select('id_upc','upc','nombre_comercial','marca','descripcion','fk_id_laboratorio')->where('material_curacion',$sku->material_curacion)->where('fk_id_forma_farmaceutica',$sku->fk_id_forma_farmaceutica)->where('fk_id_presentaciones',$sku->fk_id_presentaciones)->where('activo',1)->with('laboratorio:id_laboratorio,laboratorio')->get() : Upcs::select('id_upc','upc','nombre_comercial','marca','descripcion','fk_id_laboratorio')->where('material_curacion',$sku->material_curacion)->where('fk_id_forma_farmaceutica',$sku->fk_id_forma_farmaceutica)->where('activo',1)->with('laboratorio:id_laboratorio,laboratorio')->get();
            $relacion = null;
            $array = [];
            if(!empty($sales)){
                $array = $sku->presentaciones;
                $relacion = 'presentaciones';
            }
            elseif(!empty($especificaciones)){
                $array = $sku->especificaciones;
                $relacion = 'especificaciones';
            }

            return $sku->upcs = json_decode($this->filterUpcs($upcs,$array,$relacion));
        });

        return json_encode($skus);
    }

}
