<?php


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


    public function __construct()
    {
        $this->entity = new Vales;
    }

    public function getDataView($entity = null)
    {
        if(!empty($entity)){

            $receta = Recetas::where('eliminar',false)->where('id_receta',$entity->fk_id_receta)->first();

            $entity['titular'] = !empty($receta->nombre_paciente_no_afiliado) ? '' : $receta->titular($receta->fk_id_afiliado);
            $entity['paciente'] = !empty($receta->nombre_paciente_no_afiliado) ? $receta->nombre_paciente_no_afiliado  : $receta->NombreCompletoPaciente;
            $entity['medico'] =  $receta->medico->NombreCompleto;
            $entity['diagnostico'] = $receta->diagnostico->diagnostico;
            $entity['edad'] = !empty($receta->nombre_paciente_no_afiliado) ? '' : self::edad($receta->dependiente($receta->fk_id_afiliado)->fecha_nacimiento);
            $entity['genero'] = !empty($receta->nombre_paciente_no_afiliado) ? '' : $receta->afiliacion->genero;
            $entity['parentesco'] = !empty($receta->nombre_paciente_no_afiliado) ?  '' : $receta->dependiente($receta->fk_id_afiliado)->parentesco->nombre;

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
        $recetas = Recetas::whereIn('fk_id_estatus_receta',[19,20])
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

        $receta = Recetas::find($request->fk_id_receta);
        
        if(!empty($receta))
        {
            if($receta->fk_id_parentesco == 1) {
                $titular = $receta->afiliacion->FullName;
                $paciente = $receta->afiliacion->FullName;
                $edad = self::edad($receta->afiliacion->fecha_nacimiento);
                $genero = $receta->afiliacion->genero;
            }
            else
            {
//                $titular = '';
                $titular = !empty($receta->nombre_paciente_no_afiliado) ? '' : $receta->titular($receta->fk_id_afiliado) ;
                $paciente = !empty($receta->nombre_paciente_no_afiliado) ? $receta->nombre_paciente_no_afiliado : $receta->NombreCompletoPaciente;
                $edad = !empty($receta->nombre_paciente_no_afiliado) ? '' : self::edad($receta->dependiente($receta->fk_id_afiliado)->fecha_nacimiento);
                $genero = !empty($receta->nombre_paciente_no_afiliado) ?  '' : $receta->afiliacion->genero ;

            }
        }


        $json_receta = [

            'id_receta' => $receta->id_receta,
            'folio' => $receta->folio,
            'titular' => $titular,
            'paciente' => $paciente,
            'fk_id_dependiente' => $receta->fk_id_dependiente,
            'parentesco' => !empty($receta->nombre_paciente_no_afiliado) ?  '' : $receta->dependiente($receta->fk_id_afiliado)->parentesco->nombre,
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
                'cantidad_disponible' => $detalle->claveClienteProducto->stock($detalle->claveClienteProducto['fk_id_sku'],$detalle->claveClienteProducto['fk_id_upc']),
                'precio_unitario' => $detalle->claveClienteProducto['precio'],
                'eliminar' => $detalle->eliminar,
                'clave_cliente_producto' => $detalle->claveClienteProducto['clave_producto_cliente'],
                'descripcion' => $detalle->claveClienteProducto->sku['descripcion'],
                'sku' => $detalle->claveClienteProducto->sku['sku'],

            ];
        }

        return $json =[
            'receta' => $json_receta,
            'detalle' => $json_detalle,
        ];
    }

    public function edad($fecha){
        list($Y,$m,$d) = explode("-",$fecha);
        return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
    }


    public function impress($company,$id)
    {

        $vale = '';
        $receta = '';

        $vale = Vales::where('id_vale',$id)->first();
        $receta = Recetas::where('id_receta',$vale->fk_id_receta)->first();


        if($receta->fk_id_parentesco == 1) {
            $titular = $receta->afiliacion->FullName;
            $paciente = $receta->afiliacion->FullName;
            $edad = self::edad($receta->afiliacion->fecha_nacimiento);
            $genero = $receta->afiliacion->genero;
            $parentesco = $receta->dependiente($receta->fk_id_afiliado)->parentesco->nombre;
        }
        else
        {
            $titular = !empty($receta->nombre_paciente_no_afiliado) ? '' : $receta->titular($receta->fk_id_afiliado) ;
            $paciente = !empty($receta->nombre_paciente_no_afiliado) ? $receta->nombre_paciente_no_afiliado : $receta->NombreCompletoPaciente;
            $edad = !empty($receta->nombre_paciente_no_afiliado) ? '' : self::edad($receta->dependiente($receta->fk_id_afiliado)->fecha_nacimiento);
            $genero = !empty($receta->nombre_paciente_no_afiliado) ?  '' : $receta->afiliacion->genero ;
            $parentesco = !empty($receta->nombre_paciente_no_afiliado) ?  '' : $receta->dependiente($receta->fk_id_afiliado)->parentesco->nombre;
        }




        $pdf = PDF::loadView(currentRouteName('servicios.vales.imprimir'),[
            'vale' => $vale ,
            'receta' => $receta ,
            'titular' => $titular ,
            'paciente' => $paciente ,
            'parentesco' => $parentesco,
            'genero' => $genero,
            'edad' => $edad,
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

