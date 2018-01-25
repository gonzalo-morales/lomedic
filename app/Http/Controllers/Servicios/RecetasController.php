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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Inventarios\Productos;

class RecetasController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Recetas $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {
        return [
            'localidades' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo',1)->pluck('sucursal', 'id_sucursal')->prepend('Selecciona una opcion...', ''),
            'medicos' => Medicos::get()->pluck('nombre_completo', 'id_medico')->prepend('Selecciona una opcion...', ''),
            'programas' => Programas::get()->pluck('nombre_programa', 'id_programa')->prepend('Sin programa', ''),
            'areas' => Areas::all()->pluck('area', 'id_area')->prepend('Selecciona una opcion...', ''),
            'tipo_servicio' => [0 => 'afiliado', 1 => 'externo'],
            'afiliados' => empty($entity) ? [] : Afiliaciones::selectRAW("CONCAT(paterno,' ',materno,' ',nombre) as nombre_afiliado, id_afiliacion")->where('id_afiliacion', $entity->fk_id_afiliacion)->pluck('nombre_afiliado', 'id_afiliacion'),
            'diagnosticos' => empty($entity) ? [] : Diagnosticos::where('id_diagnostico', $entity->fk_id_diagnostico)->where('activo',1)->pluck('diagnostico', 'id_diagnostico'),
            'proyectos' => empty($entity) ? [] : Proyectos::where('id_proyecto', $entity->fk_id_proyecto)->where('fk_id_estatus',1)->pluck('proyecto', 'id_proyecto'),
        ];


    }


    public function getAfiliados($company, Request $request)
    {
        $json = [];
        $term = strtoupper($request->membership);
        $afiliados = Afiliaciones::where('id_afiliacion', 'LIKE', $term . '%')->orWhere(DB::raw("CONCAT(paterno,' ',materno, ' ',nombre)"), 'LIKE', '%' . $term . '%')->get();
        foreach ($afiliados as $afiliado) {
            $json[] = ['id' => $afiliado->id_dependiente,
                'text' => $afiliado->id_afiliacion . " - " . $afiliado->paterno . " " . $afiliado->materno . " " . $afiliado->nombre,
                'afiliacion' => $afiliado->id_afiliacion];
        }
        return json_encode($json);
    }

    public function getDiagnosticos($company, Request $request)
    {
        $json = [];
        $term = strtoupper($request->diagnostico);
        $diagnosticos = Diagnosticos::where('diagnostico', 'LIKE', '%' . $term . '%')->orWhere('clave_diagnostico', 'LIKE', $term . '%')->where('activo',1)->get();
        foreach ($diagnosticos as $diagnostico) {
            $json[] = ['id' => $diagnostico->id_diagnostico,
                'text' => '(' . $diagnostico->clave_diagnostico . ') ' . $diagnostico->diagnostico];
        }
        return json_encode($json);
    }

    public function getMedicamentos($company, Request $request)
    {

        $json = [];

        $term = $request->medicamento;
        $skus = Productos::where('activo',1)->where('sku', 'ILIKE', '%' . $term . '%')->orWhere('descripcion_corta', 'LIKE', '%' . $term . '%')->orWhere('descripcion', 'LIKE', '%' . $term . '%')->get();

        foreach ($skus as $sku) {
            $json[] = [
                'id' => (int)$sku->id_sku,
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
        return json_encode($json);

    }
    public function getProyectos($company, Request $request)
    {

        $detalle_requision = Proyectos::where('fk_id_sucursal',$request->fk_id_sucursal)
            ->pluck('proyecto','id_proyecto')
            ->toJson();

        return $detalle_requision;
    }

}
