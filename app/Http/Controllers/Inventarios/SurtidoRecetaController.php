<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 28/12/2017
 * Time: 12:41
 */

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Administracion\Medicos;
use App\Http\Models\Administracion\Afiliaciones;
use App\Http\Models\Administracion\Parentescos;
use App\Http\Models\Administracion\Diagnosticos;
use App\Http\Models\Inventarios\SurtidoReceta;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\Servicios\Recetas;
use App\Http\Models\Servicios\RecetasDetalle;
use App\Http\Models\Servicios\RequisicionesHospitalarias;
use App\Http\Models\Administracion\Areas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\Administracion\Programas;
use App\Http\Models\Servicios\RequisicionesHospitalariasDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DB;
use Spatie\ArrayToXml\ArrayToXml;
use App\ipejal;

class SurtidoRecetaController extends ControllerBase
{


    public function __construct()
    {
        $this->entity = new SurtidoReceta;
    }

    public function getDataView($entity = null)
    {
        return [
            'sucursales' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo',1)->pluck('sucursal', 'id_sucursal')->prepend('...', ''),
            'recetas' => empty($entity) ? [] : Recetas::select('folio','id_receta')->pluck('folio','id_receta')->prepend('...', ''),
//            'solicitante' => Usuarios::select(['id_usuario','nombre_corto'])->where('activo',1)->pluck('nombre_corto','id_usuario')->prepend('...', ''),
//            'areas' => PagosController::all()->pluck('area', 'id_area')->prepend('...', ''),
//            'programas' => Programas::get()->pluck('nombre_programa', 'id_programa')->prepend('Sin programa', ''),
            'fk_id_usuario_captura' =>  Auth::id(),
        ];

    }

    // public function store(Request $request, $company, $compact = true)
    // {
    //     $parent = parent::store($request, $company, $compact);
    //     if($parent)
    //     {

    //         // <root> 
    //         //     <row>
    //         //         <clave>010-218-01</clave>
    //         //         <nombre>FLUOXETINA CAPSULAS 20 MG. 14</nombre>
    //         //         <pedida>5</pedida>
    //         //         <surtida>2</surtida>
    //         //         <surtida_ipejal>2</surtida_ipejal>
    //         //         <tipo_negacion>0</tipo_negacion>
    //         //         <vale />
    //         //     </row>
    //         // </root>

    //         $id_sucursal = $request->fk_id_sucursal;
    //         $id_receta = $request->fk_id_receta;
    //         $receta = Recetas::where('id_receta', $id_receta)->first();
    //         $sucursal = Sucursales::where('id_sucursal', $id_sucursal)->first();

    //         $datos_detalle_receta = [];
    //         foreach ($request->relations['has']['detalles'] as $key => $detalle) {
    //             if($detalle)
    //             {
    //                 $clave_cliente_producto = ClaveClienteProductos::where('id_clave_cliente_producto', $detalle['fk_id_clave_cliente_producto'])->first();
    //                 $datos_detalle_receta['row'.$key]=
    //                 [
    //                     'clave' => $clave_cliente_producto->clave_producto_cliente,
    //                     'nombre' => $clave_cliente_producto->descripcion.''.$clave_cliente_producto->presentacion,
    //                     'pedida' => $detalle['cantidad_solicitada'],
    //                     'surtida' => $detalle['cantidad_surtida'],
    //                     'surtida_ipejal' => $detalle['cantidad_surtida'],
    //                     'tipo_negacion' => 0
    //                 ];
    //             }
    //         }
    //         $xml = ArrayToXml::convert($datos_detalle_receta);

    //         $Accion = 'Surtido';
    //         $FolioReceta = $receta->serie;
    //         $Sufijo = $request->sufijo ?? 'A';
    //         $Farmacia = $sucursal->sucursal;
    //         $FechaSurtido = Carbon::now();
    //         $FolioSalida = $receta->serie;
    //         $xmlClaveReceta = $xml;

    //         $surtido_clave_IPEJAL = surtido_clave($Accion, $FolioReceta, $Sufijo, $Farmacia, $FechaSurtido, $FolioSalida, $xmlClaveReceta);
    //         dd($surtido_clave_IPEJAL);
    //         return $this->redirect('store');
    //     }
    // }

    public function getReceta($company,Request $request)
    {
        $recetas = Recetas::whereIn('fk_id_estatus_receta',[1,3])
            ->where('fk_id_sucursal',$request->fk_id_sucursal)
            ->orderBy('folio', 'desc')
            ->pluck('serie','id_receta')
            ->prepend('Seleccione el número de receta','')
            ->toJson();
        return $recetas;
    }

    public function getRecetaDetalle($company,Request $request)
    {

        $detalle_receta = RecetasDetalle::where('fk_id_receta',$request->fk_id_receta)->get();

        $json = [];
        foreach ($detalle_receta as $row => $detalle)
        {
            $json[$row] = [
                'id_receta_detalle' => $detalle->id_detalle_receta,
                'fk_id_receta' => $detalle->fk_id_receta,
//                'fk_id_area' => $detalle->fk_id_area,
                'fk_id_clave_cliente_producto' => $detalle->fk_id_clave_cliente_producto,
                'cantidad_solicitada' => $detalle->cantidad_pedida,
                'cantidad_surtida' => $detalle->cantidad_surtida,
                'cantidad_disponible' => $detalle->claveClienteProducto->stock($detalle->claveClienteProducto->fk_id_sku,$detalle->claveClienteProducto->fk_id_upc),
                'precio_unitario' => $detalle->claveClienteProducto->precio,
                'eliminar' => $detalle->eliminar,
                'clave_cliente_producto' => $detalle->claveClienteProducto['clave_producto_cliente'],
                'descripcion' => $detalle->claveClienteProducto->sku['descripcion'],
                'sku' => $detalle->claveClienteProducto->sku['sku'],

            ];
        }
//        return $detalle_receta->toJson();
        return $json;
    }

    public function consultaFolio()
    {
        $id_sucursal = request()->id_sucursal;
        $folio = request()->folio;
        $sufijo = request()->sufijo;

        $sucursal_with_cliente = Sucursales::with('cliente')->where('id_sucursal',$id_sucursal)->first();
        $farmacia = $sucursal_with_cliente->sucursal;
        $proyecto = Proyectos::whereHas('cliente',function($q) use ($sucursal_with_cliente){
            $q->where('fk_id_cliente', $sucursal_with_cliente->cliente->id_socio_negocio);
        })->first();
        $receta_ipejal = consulta_folio($farmacia,$folio,$sufijo);

        if(isset($receta_ipejal->consulta_folioResult) && $receta_ipejal->consulta_folioResult->Estatus->Estado->NumeroEstatus == 1)
        {
            $json_datos_receta = $receta_ipejal->consulta_folioResult->Recetas->Receta;

            #DATOS MÉDICO
            if(isset($json_datos_receta->Medicos->Medico))
            {
                $medico = [
                    'cedula'  =>  $json_datos_receta->Cedula,
                    'nombre'  =>  $json_datos_receta->Medicos->Medico->Nombre,
                    'paterno' =>  $json_datos_receta->Medicos->Medico->ApellidoPaterno,
                    'materno' =>  $json_datos_receta->Medicos->Medico->ApellidoMaterno,
                    'fk_id_cliente' => $sucursal_with_cliente->cliente->id_socio_negocio,
                ];
    
                $DB_medico = Medicos::where('nombre','ILIKE','%'.$medico['nombre'].'%')
                                    ->where('paterno','ILIKE','%'.$medico['paterno'].'%')
                                    ->where('materno','ILIKE','%'.$medico['materno'].'%')
                                    ->where('cedula','ILIKE','%'.$medico['cedula'].'%')
                                    ->first();
                                 
                if(!$DB_medico)
                {
                    $medico_nuevo = Medicos::create($medico);
                }
            }

            #DATOS AFILIADO
            if(isset($json_datos_receta->Afiliados->Afiliado))
            {
                $fecha_nacimiento = new Carbon($json_datos_receta->Afiliados->Afiliado->FechaNacimiento);
                $ahora = Carbon::now();
                $edad = ($fecha_nacimiento->diff($ahora)->y);
    
                $DB_parentesco = Parentescos::where('nombre','ILIKE','%'.$json_datos_receta->Pacientes->Paciente->Parentesco.'%')->where('activo',1)->select('id_parentesco')->first();
                if(!$DB_parentesco)
                {
                    $parentesco_nuevo = Parentescos::create(['nombre' => $json_datos_receta->Pacientes->Paciente->Parentesco]);
                }
    
                $afiliado = [
                    'id_dependiente'    => $DB_parentesco->id_parentesco ?? $parentesco_nuevo->id_parentesco,
                    'nombre'            => $json_datos_receta->Afiliados->Afiliado->Nombre,
                    'paterno'           => $json_datos_receta->Afiliados->Afiliado->ApellidoPaterno,
                    'materno'           => $json_datos_receta->Afiliados->Afiliado->ApellidoMaterno,
                    'fecha_nacimiento'  => $json_datos_receta->Afiliados->Afiliado->FechaNacimiento,
                    'genero'            => ($json_datos_receta->Afiliados->Afiliado->Sexo == 0) ? "F" : "M",
                    'edad_tiempo'       => $edad,
                    'fk_id_parentesco'  => $DB_parentesco->id_parentesco ?? $parentesco_nuevo->id_parentesco,
                    'id_afiliacion'     => $json_datos_receta->Patente,
                    'fk_id_cliente'     => $sucursal_with_cliente->cliente->id_socio_negocio,
                ];
    
                $DB_afiliado = Afiliaciones::where('nombre','ILIKE','%'.$afiliado['nombre'].'%')
                                            ->where('paterno','ILIKE','%'.$afiliado['paterno'].'%')
                                            ->where('materno','ILIKE','%'.$afiliado['materno'].'%')
                                            ->where('fecha_nacimiento','=',$afiliado['fecha_nacimiento'])
                                            ->where('id_afiliacion',$afiliado['id_afiliacion'])
                                            ->first();
                                 
                if(!$DB_afiliado)
                {
                    $afiliado_nuevo = Afiliaciones::create($afiliado);
                }
            }

            #DATOS DIAGNOSTICO
            if(isset($json_datos_receta->Diagnosticos->Diagnostico))
            {
                $diagnostico = [
                    'clave_diagnostico' => $json_datos_receta->Diagnosticos->Diagnostico->Clave,
                    'diagnostico'       => $json_datos_receta->Diagnosticos->Diagnostico->Descripcion,
                    'fk_id_cliente'     => $sucursal_with_cliente->cliente->id_socio_negocio,
                ];
    
                $DB_diagnostico = Diagnosticos::where('diagnostico','ILIKE','%'.$diagnostico['diagnostico'].'%')
                                              ->where('clave_diagnostico','ILIKE','%'.$diagnostico['clave_diagnostico'].'%')
                                              ->where('fk_id_cliente',$sucursal_with_cliente->cliente->id_socio_negocio)
                                              ->first();
    
                if(!$DB_diagnostico)
                {
                    $diagnostico_nuevo = Diagnosticos::create($diagnostico);
                }
            }
            else
            {
                $no_diagnostico = 0;
            }

            #DATOS RECETA
            $datos_receta =[
                'serie'                 => intval($folio),
                'fk_id_sucursal'        => $id_sucursal,
                'fecha'                 => $json_datos_receta->FechaReceta,
                'fk_id_afiliacion'      => $DB_afiliado->id_afiliacion ?? $afiliado_nuevo->id_afiliacion,
                'fk_id_dependiente'     => $DB_afiliado->id_dependiente ?? $afiliado_nuevo->id_dependiente,
                'fk_id_medico'          => $DB_medico->id_medico ?? $medico_nuevo->id_medico,
                'fk_id_diagnostico'     => isset($json_datos_receta->Diagnosticos->Diagnostico) ? $DB_diagnostico->id_diagnostico ?? $diagnostico_nuevo->id_diagnostico : $no_diagnostico,
                'fk_id_programa'        => 1,
                'fk_id_estatus_receta'  => $json_datos_receta->EstatusReceta,
                'fk_id_area'            => 172,
                'fk_id_parentesco'      => $DB_parentesco->id_parentesco ?? $parentesco_nuevo->id_parentesco,
                'fk_id_proyecto'        => $proyecto->id_proyecto,
                'fk_id_afiliado'        => $DB_afiliado->id_afiliado ?? $afiliado_nuevo->id_afiliado
            ];

            $DB_receta = Recetas::where('serie',$datos_receta['serie'])
                                ->where('fk_id_sucursal',$id_sucursal)
                                ->where('fk_id_afiliado', $DB_afiliado->id_afiliado ?? $afiliado_nuevo->id_afiliado)
                                ->where('fk_id_medico', $DB_medico->id_medico ?? $medico_nuevo->id_medico)
                                ->first();

            if(!$DB_receta)
            {
                $receta_opr_nueva = Recetas::create($datos_receta);

                #DATOS_RECETA_DETALLE            
                if(isset($json_datos_receta->ClavesReceta->ClaveReceta))
                {
                    $datos_clave_cliente_producto = [];
                    $datos_receta_detalle = [];
                    foreach ($json_datos_receta->ClavesReceta->ClaveReceta as $detalle)
                    {
                        if($detalle)
                        {
                            $clave_cliente_producto = ClaveClienteProductos::where('clave_producto_cliente', $detalle->ClaveMedicamento)->first();
                            $str_detalle_descripcion = explode(',',$detalle->Descripcion);
                            $datos_receta_detalle[]= 
                            [
                                'fk_id_receta'                 => $DB_receta->id_receta ?? $receta_opr_nueva->id_receta,
                                'fk_id_proyecto'               => $proyecto->id_proyecto,
                                'cantidad_pedida'              => $detalle->CantidadPedida,
                                'cantidad_surtida'             => $detalle->CantidadSurtida,
                                'fk_id_clave_cliente_producto' => $clave_cliente_producto->id_clave_cliente_producto,
                            ];
                        }
                    }
                    $receta_det_nueva = isset($DB_receta) ? $DB_receta->detalles()->insert($datos_receta_detalle) : $receta_opr_nueva->detalles()->insert($datos_receta_detalle);
                }
                return $receta_opr_nueva;
            }
            return $DB_receta;
        }
        else
        {
            return json_encode(['id_receta'=>null,'estatus'=>$receta_ipejal->mensaje]);
        }

    }

}