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
            $entity = $return['entity'];
            if($request->especificaciones)
            {
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

    public function getUpcsFromSku($company, $id, Request $request)
    {
        $sku_data = $this->entity->find($id);
        $sku_forma = $sku_data->fk_id_forma_farmaceutica;
        $sku_presen = $sku_data->fk_id_presentaciones;
        $data_sales = $sku_data->presentaciones ?? [];
        $data_especificaciones = $sku_data->especificaciones ?? [];
        $upcs = Upcs::where('fk_id_forma_farmaceutica',$sku_forma)->where('fk_id_presentaciones',$sku_presen)->where('activo',1)->with('laboratorio')->get();
        $upcsDone = $this->filterUpcs($upcs,$data_sales,$data_especificaciones);

        return json_encode($upcsDone);
    }

    public function getUpcs()
    {
        $id_forma_farmaceutica = request()->id_forma;
        $id_presentaciones = request()->id_presentaciones;
        $data_sales = json_decode(request()->arr_sales);
        $data_especificaciones = json_decode(request()->arr_especificaciones);
        $module_upc = request()->upc ?? null;
        $upcs = Upcs::where('fk_id_forma_farmaceutica',$id_forma_farmaceutica)->where('fk_id_presentaciones',$id_presentaciones)->where('activo',1)->with('laboratorio')->get();
        $upcsDone = $this->filterUpcs($upcs,$data_sales,$data_especificaciones);

        if(empty($module_upc))
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
            })->get();
            return json_encode($idsClaves);
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
                            $fk_id_presentacion = isset($value->fk_id_presentaciones) ? $value->fk_id_presentaciones : $value->fk_id_concentracion;

                            if($presentacion->fk_id_presentaciones == $fk_id_presentacion && $presentacion->fk_id_sal == $value->fk_id_sal)
                            {
                                $id_presentaciones_founded[] = true;
                            }
                        }
                    }
                    if($numPreseRelations == $numSalesData)
                    {
                        if(isset($id_presentaciones_founded))
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
        return $upcFiltered->toArray();
    }

    public function filterSkus($skus,$data_sales,$data_especificaciones)
    {
        $skuFiltered = $skus->first(function($sku) use ($data_sales,$data_especificaciones){
            $numEspecRelations = $sku->especificaciones()->count();
            $numPreseRelations = $sku->presentaciones()->count();
            $numEspecData = count($data_especificaciones);
            $numSalesData = count($data_sales);
            $statusEspecificaciones = false;
            $statusPresentaciones = false;
            if($numEspecRelations > 0)
            {
                foreach ($sku->especificaciones as $especificacion)
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
                    foreach ($sku->presentaciones as $presentacion)
                    {
                        foreach ($data_sales as $value)
                        {
                            $skupresentacion = isset($value->fk_id_concentracion) ? $value->fk_id_concentracion : $value->fk_id_presentaciones;

                            if($presentacion->fk_id_presentaciones == $skupresentacion && $presentacion->fk_id_sal == $value->fk_id_sal)
                            {
                                $id_presentaciones_founded[] = true;
                            }
                        }
                    }
                    if($numPreseRelations == $numSalesData)
                    {
                        if(isset($id_presentaciones_founded))
                            if(!in_array(false,$id_presentaciones_founded,true) && count($id_presentaciones_founded) == $numPreseRelations)
                            {
                                $statusPresentaciones = true;
                            }
                    }
                }
                if($numPreseRelations == 1)
                {
                    foreach ($sku->presentaciones as $presentacion)
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
            if(($statusEspecificaciones == true && $statusPresentaciones == true) && ($sku->especificaciones()->exists() && $sku->presentaciones()->exists()))
            {
                return $sku;
            }
            else if($statusEspecificaciones == true && ($sku->especificaciones()->exists() && !$sku->presentaciones()->exists()))
            {
                return $sku;
            }
            else if($statusPresentaciones == true && ($sku->presentaciones()->exists() && !$sku->especificaciones()->exists()))
            {
                return $sku;
            }
            else
            {
                return false;
            }
        });
        return $skuFiltered;
    }

    public function getRelatedSkus()
    {
        $fk_id_forma_farmaceutica = request()->fk_id_forma_farmaceutica;
        $fk_id_presentaciones = request()->fk_id_presentaciones;
        $sales = json_decode(request()->sales);
        $especificaciones = json_decode(request()->especificaciones);
        $sku = $fk_id_presentaciones > 0 ? Productos::select('id_sku','sku','fk_id_presentaciones','fk_id_forma_farmaceutica')->where('fk_id_forma_farmaceutica',$fk_id_forma_farmaceutica)->where('fk_id_presentaciones',$fk_id_presentaciones)->where('activo',1)->get() : Productos::select('id_sku','sku','fk_id_presentaciones','fk_id_forma_farmaceutica')->where('fk_id_forma_farmaceutica',$fk_id_forma_farmaceutica)->where('activo',1)->get();

        $sku = $this->filterSkus($sku,$sales,$especificaciones);
        $upcs = $sku->fk_id_presentaciones > 0 ? Upcs::select('id_upc','upc','nombre_comercial','marca','descripcion','fk_id_laboratorio')->where('fk_id_forma_farmaceutica',$sku->fk_id_forma_farmaceutica)->where('fk_id_presentaciones',$sku->fk_id_presentaciones)->where('activo',1)->with('laboratorio:id_laboratorio,laboratorio')->get() : Upcs::select('id_upc','upc','nombre_comercial','marca','descripcion','fk_id_laboratorio')->where('fk_id_forma_farmaceutica',$sku->fk_id_forma_farmaceutica)->where('activo',1)->with('laboratorio:id_laboratorio,laboratorio')->get();
        $sku['upcs'] = array_values($this->filterUpcs($upcs,$sales,$especificaciones));


        return json_encode($sku);
    }
}
