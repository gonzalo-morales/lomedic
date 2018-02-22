<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 15/1/2018
 * Time: 11:21
 */


namespace App\Http\Controllers\Servicios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Afiliaciones;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Inventarios\SurtidoReceta;
use App\Http\Models\Servicios\Recetas;
use App\Http\Models\Servicios\RecetasDetalle;
use App\Http\Models\Servicios\Vales;
use App\Http\Models\Administracion\Areas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Programas;
use App\Http\Models\Servicios\RequisicionesHospitalariasDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use DB;

class ValesController extends ControllerBase
{


    public function __construct(Vales $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {

        if(!empty($entity)){
            $receta = Recetas::where('eliminar',false)->where('id_receta',$entity->fk_id_receta)->first();
            $entity['titular'] = empty($entity) ? [] : $receta->dependiente($receta->fk_id_afiliacion,1)->nombre;
            $entity['paciente'] = empty($entity) ? [] : $receta->dependiente($receta->fk_id_afiliacion,$receta->fk_id_dependiente)->nombre;
            $entity['medico'] = empty($entity) ? [] : $receta->medico->NombreCompleto;
            $entity['diagnostico'] = empty($entity) ? [] : $receta->diagnostico->diagnostico;
//            $entity['edad'] = self::edad($receta->dependiente($receta->fk_id_afiliacion,$receta->fk_id_dependiente)->fecha_nacimiento);
            $entity['edad'] = self::edad($receta->dependiente($receta->fk_id_afiliacion,$receta->fk_id_dependiente)->fecha_nacimiento);
            $entity['patente'] = $receta->pantente;
            $entity['genero'] = empty($entity) ? [] : $receta->dependiente($receta->fk_id_afiliacion,$receta->fk_id_dependiente)->genero;
            $entity['parentesco'] = empty($entity) ? [] : $receta->parentesco['nombre'];
        }

        return [
            'sucursales' => Sucursales::select(['sucursal', 'id_sucursal'])->where('activo', 1)->pluck('sucursal', 'id_sucursal')->prepend('Selecciona una opcion...', ''),
            'recetas' => empty($entity) ? [] : Recetas::where('eliminar',false)->pluck('folio','id_receta')->prepend('Selecciona una opcion...', ''),
            'solicitante' => Usuarios::select(['id_usuario','nombre_corto'])->where('activo',1)->pluck('nombre_corto','id_usuario')->prepend('Selecciona una opcion...', ''),
            'fk_id_usuario_captura' =>  Auth::id(),
        ];

    }

    public function getReceta($company,Request $request)
    {
        $recetas = Recetas::whereIn('fk_id_estatus_receta',[1,3])
            ->where('fk_id_sucursal',$request->fk_id_sucursal)
            ->orderBy('folio', 'desc')
            ->pluck('folio','id_receta')
            ->prepend('Selecciona una opcion...','')
            ->toJson();
        return $recetas;
    }

    public function getRecetaDetalle($company,Request $request)
    {
        $json = [];
        $json_receta = [];
        $json_detalle = [];

        $receta = Recetas::where('id_receta',$request->fk_id_receta)->first();

        if($receta->fk_id_parentesco == 1) {
            $titular = $receta->afiliacion->FullName;
            $paciente = $receta->afiliacion->FullName;
            $edad = self::edad($receta->afiliacion->fecha_nacimiento);
            $genero = $receta->afiliacion->genero;
        }
        else
        {
            $titular = $receta->afiliacion->FullName;
            $paciente = Afiliaciones::where('id_afiliacion',$receta->fk_id_afiliacion)
                ->where('id_dependiente',$receta->fk_id_dependiente)
                ->first()->FullName;
            $edad = self::edad(Afiliaciones::where('id_afiliacion',$receta->fk_id_afiliacion)
                ->where('id_dependiente',$receta->fk_id_dependiente)
                ->first()->fecha_nacimiento);
            $genero = $receta->afiliacion->genero;
        }



        $json_receta = [
            'id_receta' => $receta->id_receta,
            'folio' => $receta->folio,
            'titular' => $titular,
            'paciente' => $paciente,
            'fk_id_dependiente' => $receta->fk_id_dependiente,
            'parentesco' => $receta->parentesco->nombre,
            'medico' => $receta->medico->NombreCompleto,
            'diagnostico' => $receta->diagnostico->diagnostico,
            'edad' => $edad,
            'pantente' => '',
            'genero' => $genero,
        ];

        $detalle_receta = RecetasDetalle::where('fk_id_receta',$request->fk_id_receta)
            ->where('eliminar',false)
            ->get();

        foreach ($detalle_receta as $row => $detalle)
        {
            $json_detalle[$row] = [
                'id_receta_detalle' => $detalle->id_receta_detalle,
                'fk_id_receta' => $detalle->fk_id_receta,
                'fk_id_clave_cliente_producto' => $detalle->fk_id_clave_cliente_producto,
                'cantidad_solicitada' => $detalle->cantidad_pedida,
                'cantidad_surtida' => $detalle->cantidad_surtida,
                'cantidad_disponible' => $detalle->claveClienteProducto->stock($detalle->claveClienteProducto->fk_id_sku,$detalle->claveClienteProducto->fk_id_upc),
                'precio_unitario' => $detalle->claveClienteProducto->precio,
                'eliminar' => $detalle->eliminar,
                'clave_cliente_producto' => $detalle->claveClienteProducto['clave_producto_cliente'],
                'descripcion' => $detalle->claveClienteProducto->sku['descripcion'],

            ];
        }

        return $json =[
            'receta' => $json_receta,
            'detalle' => $json_detalle,
        ];;
    }

    public function edad($fecha_nac){
        $dia=date("j");
        $mes=date("n");
        $anno=date("Y");
        //descomponer fecha de nacimiento
        $anno_nac=substr($fecha_nac, 0, 4);
        $mes_nac=substr($fecha_nac, 5, 2);
        $dia_nac=substr($fecha_nac, 8, 2);
        //
        if($mes_nac>$mes){
            $calc_edad= $anno-$anno_nac-1;
        }else{
            if($mes==$mes_nac AND $dia_nac>$dia){
                $calc_edad= $anno-$anno_nac-1;
            }else{
                $calc_edad= $anno-$anno_nac;
            }
        }
        return $calc_edad;
    }


    public function impress($company,$id)
    {

        $vale = Vales::where('id_vale',$id)->first();
        $receta = Recetas::where('id_receta',$vale->fk_id_receta)->first();

        $pdf = PDF::loadView(currentRouteName('servicios.vales.imprimir'),[
            'vale' => $vale ,
            'receta' => $receta ,
            'edad' => self::edad($receta->dependiente($receta->fk_id_afiliacion,$receta->fk_id_dependiente)->fecha_nacimiento),
        ]);

        $pdf->setPaper('letter','landscape');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(38,580,"Página {PAGE_NUM} de {PAGE_COUNT}",null,8,array(0,0,0));
        $canvas->text(665,580,'PSAI-PN06-F01 Rev. 01',null,8);
//        $canvas->image('data:image/png;charset=binary;base64,'.$barcode,355,580,100,16);

        return $pdf->stream('solicitud')->header('Content-Type',"application/pdf");
//        return view(currentRouteName('imprimir'));
    }

}

