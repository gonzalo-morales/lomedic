<?php
namespace App\Http\Controllers\Servicios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Medicos;
use App\Http\Models\Administracion\Programas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Afiliaciones;
use App\Http\Models\Administracion\Areas;
use App\Http\Models\Administracion\Diagnosticos;
use App\Http\Models\Servicios\Recetas;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\Inventarios\Productos;
use Illuminate\Http\Request;

class RecetasController extends ControllerBase
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->entity = new Recetas;
    }

    public function getDataView($entity = null)
    {
        return [
            'localidades' => Sucursales::select('sucursal', 'id_sucursal')->where('activo',1)->pluck('sucursal', 'id_sucursal')->prepend('...', ''),
            'medicos' => Medicos::get()->pluck('nombre_completo', 'id_medico')->prepend('...', ''),
            'programas' => Programas::select('nombre_programa', 'id_programa')->pluck('nombre_programa', 'id_programa')->prepend('Sin programa', ''),
            'areas' => Areas::select('area', 'id_area')->pluck('area', 'id_area')->prepend('...', ''),
            'tipo_servicio' => [0 => 'afiliado', 1 => 'externo'],
            'afiliados' => empty($entity) ? [] : Afiliaciones::selectRAW("CONCAT(paterno,' ',materno,' ',nombre) as nombre_afiliado, id_afiliacion")->where('id_afiliacion', $entity->fk_id_afiliacion)->pluck('nombre_afiliado', 'id_afiliacion'),
            'diagnosticos' => empty($entity) ? [] : Diagnosticos::select('diagnostico', 'id_diagnostico')->where('id_diagnostico', $entity->fk_id_diagnostico)->where('activo',1)->pluck('diagnostico', 'id_diagnostico'),
            'proyectos' => empty($entity) ? [] : Proyectos::select('proyecto', 'id_proyecto')->where('id_proyecto', $entity->fk_id_proyecto)->where('fk_id_estatus',1)->pluck('proyecto', 'id_proyecto'),
        ];
    }

    public function getAfiliados($company, Request $request)
    {
        $term = $request->membership;
        return Afiliaciones::selectRaw("id_dependiente as id, CONCAT(id_afiliacion,' - ',paterno,' ',materno,' ',nombre) as text, id_afiliacion as afiliacion")
            ->where('id_afiliacion', 'ILIKE',"%$term%")->orWhereRaw("CONCAT(paterno,' ',materno, ' ',nombre) ILIKE '%$term%'")->get()->toJson();
    }

    public function getDiagnosticos($company, Request $request)
    {
        $term = $request->diagnostico;
        return Diagnosticos::selectRaw("id_diagnostico as id, CONCAT('(',clave_diagnostico,') ',diagnostico) as text")
            ->where('diagnostico', 'ILIKE', "%$term%")->orWhere('clave_diagnostico', 'ILIKE',"%$term%")->where('activo',1)->get()->toJson();
    }

    public function getMedicamentos($company, Request $request)
    {
        $json = [];
        $term = $request->medicamento;
        $skus = Productos::where('activo',1)->where('sku', 'ILIKE', '%' . $term . '%')->orWhere('descripcion_corta', 'ILIKE', "%$term%")->orWhere('descripcion', 'ILIKE', "%$term%")->get();

        foreach ($skus as $sku) {
            if($sku->clave_cliente_productos['cantidad_presentacion'] != null && $sku->clave_cliente_productos['tope_receta'] != null && $sku->clave_cliente_productos['disponibilidad'] != null ){
                $json[] = [
                    'id' => (int)$sku->id_sku,
                    'sku' => $sku->sku,
                    'fk_id_clave_cliente_producto' => $sku->clave_cliente_productos['id_clave_cliente_producto'],
                    'clave_cliente_producto' => $sku->clave_cliente_productos['clave_producto_cliente'],
                    'text' => $sku->descripcion,
                    'cantidad_presentacion' => $sku->clave_cliente_productos['cantidad_presentacion'],
                    'familia' => $sku->fk_id_familia,
                    'tope_receta' => $sku->clave_cliente_productos['tope_receta'],
                    'disponible' => $sku->clave_cliente_productos['disponibilidad'],
                    'id_cuadro' => $sku->fk_id_proyecto
                ];
            }
        }
        return json_encode($json);
    }
    
    public function getProyectos($company, Request $request)
    {
        return Proyectos::select('proyecto','id_proyecto')->where('fk_id_sucursal',$request->fk_id_sucursal)->pluck('proyecto','id_proyecto')->toJson();
    }
}