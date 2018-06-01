<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\Administracion\Laboratorios;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Administracion\PresentacionVenta;
use App\Http\Models\Administracion\IndicacionTerapeutica;
use App\Http\Models\Administracion\Presentaciones;
use App\Http\Models\Administracion\TiposProductos;
use App\Http\Models\Administracion\FormaFarmaceutica;
use App\Http\Models\Administracion\ViaAdministracion;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\FamiliasProductos;
use App\Http\Models\Administracion\GrupoProductos;
use App\Http\Models\Administracion\SubgrupoProductos;
use App\Http\Models\Administracion\Sales;
use Illuminate\Http\Request;
use App\Http\Models\Administracion\Especificaciones;
use Illuminate\Support\Facades\Crypt;

class UpcsController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Upcs;
    }
    
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
            'especificaciones'  => Especificaciones::where('activo',1)->pluck('especificacion','id_especificacion')->sortBy('especificacion'),
            'laboratorios'      => Laboratorios::select('laboratorio','id_laboratorio')->where('activo',1)->pluck('laboratorio','id_laboratorio')->sortBy('laboratorio')->prepend('...',''),
            'paises'            => Paises::select('pais','id_pais')->where('activo',1)->pluck('pais','id_pais')->sortBy('pais')->prepend('...',''),
            'indicaciones'      => IndicacionTerapeutica::select('indicacion_terapeutica','id_indicacion_terapeutica')->where('activo',1)->pluck('indicacion_terapeutica','id_indicacion_terapeutica'),
            'presentaciones'    => Presentaciones::join('gen_cat_unidades_medidas', 'gen_cat_unidades_medidas.id_unidad_medida', '=', 'adm_cat_presentaciones.fk_id_unidad_medida')
                                                ->whereNotNull('clave')->selectRaw("Concat(cantidad,' ',clave) as text, id_presentacion as id")->pluck('text','id'),
            'tipoproducto'      => TiposProductos::select('tipo_producto', 'id_tipo')->where('activo',1)->pluck('tipo_producto','id_tipo')->prepend('...',''),
            'presentacionventa' => PresentacionVenta::where('activo',1)->pluck('presentacion_venta','id_presentacion_venta')->sortBy('presentacion_venta')->prepend('...',''),
            'formafarmaceutica' => FormaFarmaceutica::where('activo',1)->pluck('forma_farmaceutica','id_forma_farmaceutica')->sortBy('forma_farmaceutica'),
            'viaadministracion' => ViaAdministracion::where('activo',1)->pluck('via_administracion','id_via_administracion')->sortBy('via_administracion')->prepend('...',''),
            'monedas'           => Monedas::where('activo',1)->selectRaw("Concat(moneda,'-',descripcion) as text, id_moneda as id")->pluck('text', 'id')->prepend('...',''),
            'familias'          => FamiliasProductos::where('activo',1)->pluck('descripcion','id_familia')->sortBy('descripcion')->prepend('...',''),
            'sales'             => Sales::where('activo',1)->pluck('nombre','id_sal')->sortBy('nombre'),
            'subgrupo'          => collect($subgrupos ?? [])->toArray(),
            'subgrupo_data'     => $subgrupo_data ?? []
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
    
}