<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Controllers\Inventarios\ProductosController;
use App\Http\Models\Administracion\Especificaciones;
use App\Http\Models\Administracion\FormaFarmaceutica;
use App\Http\Models\Administracion\Presentaciones;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\Administracion\ClavesProductosServicios;
use App\Http\Models\Administracion\ClavesUnidades;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Administracion\Sales;
use App\Http\Models\Inventarios\Upcs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ClaveClienteProductosController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new ClaveClienteProductos;
    }
    
    public function getDataView($entity = null)
    {
        $claveproductoservicio = null;
        $upcs = null;
        if($entity){
            $claveproductoservicio = ClavesProductosServicios::selectRaw("id_clave_producto_servicio as id, CONCAT(clave_producto_servicio,' - ',descripcion) as text")->where('id_clave_producto_servicio',$entity->fk_id_clave_producto_servicio)->orderBy('text')->pluck('text','id');

            $fk_id_forma_farmaceutica = $entity->fk_id_forma_farmaceutica != 0 ? $entity->fk_id_forma_farmaceutica: null;
            $fk_id_presentaciones = $entity->fk_id_presentacion != 0 ? $entity->fk_id_presentacion : null;
            $sales = $entity->concentraciones ?? null;
            $especificaciones = $entity->especificaciones ?? null;
            $material_curacion = $entity->material_curacion == 'true' ? 1 : 0;
            $skus = $fk_id_presentaciones > 0 ? Productos::select('id_sku','sku','fk_id_presentaciones','fk_id_forma_farmaceutica','material_curacion')->where('material_curacion',$material_curacion)->where('fk_id_forma_farmaceutica',$fk_id_forma_farmaceutica)->where('fk_id_presentaciones',$fk_id_presentaciones)->where('activo',1)->get() : Productos::select('id_sku','sku','fk_id_presentaciones','fk_id_forma_farmaceutica','material_curacion')->where('material_curacion',$material_curacion)->where('fk_id_forma_farmaceutica',$fk_id_forma_farmaceutica)->where('activo',1)->get();
            $productocontroller = new ProductosController();

            if(!empty($sales))
                $skus = collect(json_decode($productocontroller->filterSkus($skus,$sales,'presentaciones')));
            elseif (!empty($especificaciones))
                $skus = collect(json_decode($productocontroller->filterSkus($skus,$especificaciones,'especificaciones')));

            $skus->filter(function ($sku)use($sales,$especificaciones,$productocontroller){
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

                return $sku->upcs = json_decode($productocontroller->filterUpcs($upcs,$array,$relacion));
            });

        }
        return [
            'clientes' => SociosNegocio::where('activo',1)->where('fk_id_tipo_socio_venta',1)->whereHas('empresas',function ($empresa){
                $empresa->where('id_empresa',request()->empresa->id_empresa)->where('eliminar','f');
            })->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
            'unidadesmedidas' => UnidadesMedidas::where('activo',1)->orderBy('nombre')->pluck('nombre','id_unidad_medida'),
            'clavesproductosservicios' => $claveproductoservicio,
            'clavesunidades' => ClavesUnidades::selectRaw("id_clave_unidad, CONCAT(clave_unidad,' - ',descripcion) as descripcion")->where('activo',1)->orderBy('descripcion')
                ->pluck('descripcion','id_clave_unidad'),
            'impuestos' => Impuestos::where('activo',1)->orderBy('impuesto')->pluck('impuesto','id_impuesto'),
            'skus' => $skus ?? null,
//            'upcs' => $upcs,
            'formafarmaceutica' => FormaFarmaceutica::where('activo',1)->where('eliminar',0)->pluck('forma_farmaceutica','id_forma_farmaceutica')->prepend('...',''),
            'presentaciones' => Presentaciones::join('gen_cat_unidades_medidas', 'gen_cat_unidades_medidas.id_unidad_medida', '=', 'adm_cat_presentaciones.fk_id_unidad_medida')
                ->whereNotNull('clave')->selectRaw("Concat(cantidad,' ',clave) as text, id_presentacion as id")->pluck('text','id')->prepend('...',''),
            'sales' => Sales::where('activo',1)->pluck('nombre','id_sal')->sortBy('nombre'),
            'js_upcs' => Crypt::encryptString('"selectRaw":["id_upc as id, CONCAT(upc,\' - \',nombre_comercial) as text"],"whereHas":[{"skus":{"where":["fk_id_sku",$fk_id_sku]}}]'),
            'js_cantidad_upc' => Crypt::encryptString('"conditions":[{"where":["id_upc","$fk_id_upc"]}],"whereHas":[{"skus": {"where": ["fk_id_sku", "$fk_id_sku"]}}],"pivot":["skus"]'),
            'js_clave_producto_servicio' => Crypt::encryptString('"selectRaw":["id_clave_producto_servicio as id, CONCAT(clave_producto_servicio,\' - \',descripcion) as text"],"conditions":[{"where":["clave_producto_servicio","ILIKE","%$term%"]},{"orWhere":["descripcion","ILIKE","%$term%"]}]'),
            'js_clave_unidad' => Crypt::encryptString('"selectRaw":["id_clave_unidad as id, CONCAT(clave_unidad,\' - \',descripcion) as text"],"conditions":[{"where":["clave_unidad","ILIKE","%$term%"]},{"orWhere":["descripcion","ILIKE","%$term%"]}]'),
            'especificaciones' => Especificaciones::where('activo',1)->pluck('especificacion','id_especificacion')
            ];
    }

    public function obtenerClavesCliente($company,$id)
    {
        return ClaveClienteProductos::where('fk_id_cliente',$id)->select('id_clave_cliente_producto as id','clave_producto_cliente as text','descripcion as descripcionClave','precio','fk_id_impuesto')->get();
    }

    public function store(Request $request, $company, $compact = false)
    {
        $return = parent::store($request, $company, true);

        if(is_array($return)){
            $entity = $return['entity'];
            $sync = [];
            foreach ($request->productos as $producto){
                $sync[]=['fk_id_upc'=>$producto['fk_id_upc']];
            }
            $productos = $entity->productos()->sync($sync);
            $especificaciones = true;
            if(isset($request->especificaciones)) {
                $sync = [];
                foreach ($request->especificaciones as $especificacion) {
                    $sync[] = ['fk_id_especificacion' => $especificacion['fk_id_especificacion']];
                }
                $especificaciones = $entity->especificaciones()->sync($sync);
            }

            if($productos || $especificaciones){
                return $return['redirect'];
            }else{
                return $this->redirect('error_store');
            }
        }else{
            return $this->redirect('error_store');
        }
    }

    public function update(Request $request, $company, $id, $compact = false)
    {
        $return = parent::update($request, $company, $id, true);
        if(is_array($return)){
            $entity = $return['entity'];
            $sync = [];
            foreach ($request->productos as $producto){
                $sync[]=['fk_id_upc'=>$producto['fk_id_upc']];
            }
            $insert = $entity->productos()->sync($sync);
            if($insert){
                return $return['redirect'];
            }else{
                return $this->redirect('error_store');
            }
        }else{
            return $this->redirect('error_store');
        }
    }
}