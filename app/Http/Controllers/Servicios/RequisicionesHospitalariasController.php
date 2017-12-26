<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 04/09/2017
 * Time: 12:37
 */

namespace App\Http\Controllers\Servicios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Servicios\RequisicionesHospitalarias;
use App\Http\Models\Administracion\Areas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Inventarios\Productos;
use Illuminate\Http\Request;

use DB;

class RequisicionesHospitalariasController extends ControllerBase
{


    public function __construct(RequisicionesHospitalarias $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {


        return [
            'localidades' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo', 1)->pluck('sucursal', 'id_sucursal')->prepend('Selecciona una opcion...', ''),
            'solicitante' => Usuarios::select(['id_usuario','nombre_corto'])->where('activo',1)->pluck('nombre_corto','id_usuario')->prepend('Selecciona una opcion...', ''),
            'areas' => Areas::all()->pluck('area', 'id_area')->prepend('Selecciona una opcion...', ''),

//            'tipo_servicio' => [0 => 'afiliado', 1 => 'externo'],
//            'afiliados' => empty($entity) ? [] : Afiliaciones::selectRAW("CONCAT(paterno,' ',materno,' ',nombre) as nombre_afiliado, id_afiliacion")->where('id_afiliacion', $entity->fk_id_afiliacion)->pluck('nombre_afiliado', 'id_afiliacion'),
//            'diagnosticos' => empty($entity) ? [] : Diagnosticos::where('id_diagnostico', $entity->fk_id_diagnostico)->where('activo', '1')->pluck('diagnostico', 'id_diagnostico'),
        ];


    }

    public function getMedicamentos($company, Request $request)
    {

        $json = [];

        $term = $request->medicamento;
        $skus = Productos::where('activo', '1')->where('sku', 'ILIKE', '%' . $term . '%')->orWhere('descripcion_corta', 'LIKE', '%' . $term . '%')->orWhere('descripcion', 'LIKE', '%' . $term . '%')->get();

        foreach ($skus as $sku) {
            $json[] = ['id' => (int)$sku->id_sku,
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
    /*
    public function __construct(RequisicionesHospitalarias $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView()
    {
        return [];
    }

    public function index($company, $attributes = [])
    {

        return parent::index($company, $attributes = []);

    }



    public function create($company, $attributes =[])
    {


        $data = $this->entity->getColumnsDefaultsValues();
        $localidades = Sucursales::where('tipo',0)->where('id_cliente',135)
                                    ->where('estatus','=',1)
                                    ->where('tipo','=',0)
                                    ->pluck('sucursal','id_sucursal');
        $estatus = EstatusRequisiciones::all()->pluck('estatus','id_estatus');
        $dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];

        return view(currentRouteName('smart'), $dataview+[
            'data'=>$data,
            'localidades'=>$localidades,
            'estatus'=>$estatus,
        ]);
    }

    public function store(Request $request, $company)
    {

        $this->validate($request, $this->entity->rules);

        $isSuccess = $this->entity->create($request->all()+['fecha' => date('Y-m-d h:i:s'),'id_usuario_captura'=>2]);
        foreach ($request->input('producto_requisicion') as $productos_requiscion )
        {
            DB::table('ss_qro_requisicion_detalle')->insert([
                'id_requisicion' => $isSuccess->id_requisicion ,
                'clave_cliente' => $productos_requiscion['producto_clave'],
                'id_cuadro' => 155,
                'id_area' => $productos_requiscion['id_area'],
                'cantidad_pedida' => $productos_requiscion['cantidad'],

            ]);
        }


            return $this->redirect('store');

    }

    public function show($company, $id, $attributes = [])
    {

        $datos_requerimiento = RequisicionesHospitalarias::all()->where('id_requisicion','=',$id)->first();

        $localidad = RequisicionesHospitalarias::join('cat_localidad','ss_qro_requisicion.id_localidad','=','cat_localidad.id_localidad')
            ->where('ss_qro_requisicion.id_requisicion','=',$id)
            ->select('cat_localidad.id_localidad','cat_localidad.localidad')
            ->get();

        $usuario = RequisicionesHospitalarias::join('adm_usuario','ss_qro_requisicion.id_solicitante','=','adm_usuario.id_usuario')
            ->select(DB::raw("CONCAT(adm_usuario.nombre,' ',adm_usuario.paterno,' ',adm_usuario.materno) AS nombre"))
            ->where('ss_qro_requisicion.id_requisicion','=',$id)
            ->pluck('nombre');

        $estatus = EstatusRequisiciones::all()->pluck('estatus','id_estatus');

        $detalle_requisicion = DB::select("SELECT rd.*, a.area, substring(cp.descripcion from 1 for 250) as descripcion
            FROM ss_qro_requisicion_detalle as rd
            LEFT JOIN cat_area as a ON a.id_area = rd.id_area
            LEFT JOIN cat_cuadro c ON C.id_cuadro = rd.id_cuadro
            LEFT JOIN cat_cuadro_producto cp ON cp.id_cuadro = c.id_cuadro AND c.id_cliente = 135 AND cp.estatus = '1' AND cp.clave_cliente = rd.clave_cliente
            WHERE rd.id_requisicion = $id");


        $data = $this->entity->findOrFail($id);
        $dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];

        return view(currentRouteName('smart'), $dataview+[
            'data'=>$data,
            'localidades'=>$localidad->pluck('sucursal','id_sucursal'),
            'solicitante'=>$usuario,
            'datos_requisicion'=> $datos_requerimiento,
            'detalle_requisicion'=> $detalle_requisicion,
            'estatus' => $estatus
            ]);
    }


    public function surtir(Request $request, $company, $id, $attributes = [])
    {


        if ($request->isMethod('get')) {
            $datos_requisicion = RequisicionesHospitalarias::all()->where('id_requisicion','=',$id)->first();

            $localidad = RequisicionesHospitalarias::join('cat_localidad','ss_qro_requisicion.id_localidad','=','cat_localidad.id_localidad')
                ->where('ss_qro_requisicion.id_requisicion','=',$id)
                ->select('cat_localidad.id_localidad','cat_localidad.localidad')
                ->get();

            $usuario = RequisicionesHospitalarias::join('adm_usuario','ss_qro_requisicion.id_solicitante','=','adm_usuario.id_usuario')
                ->select(DB::raw("CONCAT(adm_usuario.nombre,' ',adm_usuario.paterno,' ',adm_usuario.materno) AS nombre"))
                ->where('ss_qro_requisicion.id_requisicion','=',$id)
                ->pluck('nombre');

            $estatus = EstatusRequisiciones::all()->pluck('estatus','id_estatus');

            $detalle_requisicion = DB::select("SELECT rd.*, a.area, substring(cp.descripcion from 1 for 250) as descripcion
                FROM ss_qro_requisicion_detalle as rd
                LEFT JOIN cat_area as a ON a.id_area = rd.id_area
                LEFT JOIN cat_cuadro c ON C.id_cuadro = rd.id_cuadro
                LEFT JOIN cat_cuadro_producto cp ON cp.id_cuadro = c.id_cuadro AND c.id_cliente = 135 AND cp.estatus = '1' AND cp.clave_cliente = rd.clave_cliente
                WHERE rd.id_requisicion = $id");


            $data = $this->entity->findOrFail($id);
            $dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];

            return view(currentRouteName('smart'), $dataview+[
                'data'=>$data,
                'localidades'=>$localidad->pluck('sucursal','id_sucursal'),
                'solicitante'=>$usuario,
                'datos_requisicion'=> $datos_requisicion,
                'detalle_requisicion'=> $detalle_requisicion,
                'estatus' => $estatus
            ]);
        }
        else {
            foreach ($request->datos_requisicion as $dato)
            {
                $cantidad = $dato['cantidad']+$dato['cantidad_surtida'];
                DB::update('UPDATE ss_qro_requisicion_detalle set cantidad_surtida = '.$cantidad .' where id_requisicion_detalle = ?', [$dato['id']]);
            }
            return Redirect::back();
        }
    }


    public function update(Request $request, $company, $id)
    {

    }

    public function destroy(Request $request, $company, $id)
    {

        $isSuccess = $this->entity->where($this->entity->getKeyName(), $id)->update(['id_estatus' => 4]);
        return $this->redirect('store');

    }


    public function getAreas()
    {
        $json = [];

        $areas = Areas::join('cat_area_localidad','cat_area.id_area','=','cat_area_localidad.id_area')
            ->where('cat_area_localidad.id_localidad','=',$_POST['id_localidad'])
            ->pluck('cat_area.area','cat_area_localidad.id_area')
            ->toJson();

        $usuarios =Usuarios::join('adm_usuario_localidad','adm_usuario.id_usuario','=','adm_usuario.id_usuario')
            ->where('adm_usuario.estatus','=',1)
            ->where('adm_usuario.id_tipo','=',21)
            ->where('adm_usuario_localidad.id_localidad','=',$_POST['id_localidad'])
            ->select(DB::raw("CONCAT(adm_usuario.nombre,' ',adm_usuario.paterno,' ',adm_usuario.materno) AS nombre"),'adm_usuario.id_usuario')
            ->pluck('nombre','id_usuario')
            ->toJson();

        $productos = DB::select("SELECT cp.clave_cliente, substring(cp.descripcion from 1 for 250) as descripcion, cf.descripcion as familia, coalesce(cp.cantidad_presentacion,0) cantidad_presentacion, coalesce(SUM(ie.quedan - ie.apartadas),0) disponible,
                tp.id_cuadro_tipo_medicamento as tipo_medicamento, c.id_cuadro, coalesce(lp.tope_receta,0) tope_receta
            FROM cat_cuadro c
            LEFT JOIN cat_cuadro_producto cp ON cp.id_cuadro = c.id_cuadro AND c.id_cliente = 135 AND cp.estatus = '1'
            LEFT JOIN cat_cuadro_tipo_producto tp ON tp.id_cuadro_tipo_medicamento = cp.id_cuadro_tipo_medicamento AND tp.id_cuadro_tipo_medicamento <> 57 AND tp.estatus = '1'
            LEFT JOIN cat_localidad_producto lp ON lp.id_cuadro = c.id_cuadro AND lp.clave_cliente = cp.clave_cliente AND lp.estatus = '1' AND lp.id_localidad = ".$_POST['id_localidad']."
            INNER JOIN cat_familia cf ON cf.id_familia = cp.id_familia
            LEFT JOIN inv_existencia ie ON ie.id_localidad = lp.id_localidad AND (ie.quedan - ie.apartadas > 0) AND ie.caducidad > now()
            LEFT JOIN cat_producto_cliente pc ON pc.codigo_barras = ie.codigo_barras AND pc.id_cuadro = c.id_cuadro AND pc.clave_cliente = cp.clave_cliente AND pc.estatus = '1'
            WHERE c.estatus = '1' AND c.id_tipo_cuadro = '1' AND C.id_cliente = 135
            GROUP BY cp.clave_cliente,cp.descripcion,cf.descripcion,cp.cantidad_presentacion,tp.id_cuadro_tipo_medicamento,c.id_cuadro,lp.tope_receta
            ORDER BY disponible DESC, cp.descripcion;");

        foreach ($productos as $producto){
            $json[$producto->clave_cliente] = $producto->descripcion;
        }

        $json = json_encode($json);
        $areas = ['areas'=>$areas,'usuario'=>$usuarios,'producto'=>$json];

        return json_encode($areas);
    }

*/
}