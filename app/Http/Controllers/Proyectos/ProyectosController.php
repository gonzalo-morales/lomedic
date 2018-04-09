<?php
namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ClasificacionesProyectos;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Proyectos\AnexosProyectos;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\Localidades;
use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Proyectos\ContratosProyectos;
use App\Http\Models\Administracion\TiposEventos;
use App\Http\Models\Administracion\Dependencias;
use App\Http\Models\Administracion\Subdependencias;
use App\Http\Models\Logs;
use App;
use DB;
use File;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\CaracterEventos;
use App\Http\Models\Administracion\FormasAdjudicacion;
use App\Http\Models\Administracion\ModalidadesEntrega;

class ProyectosController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Proyectos;
    }
    
    public function getDataView($entity = null)
    {
        $sucursales = null;
        if(!empty($entity)){
            $sucursales = Sucursales::where('fk_id_cliente',$entity->fk_id_cliente)->where('fk_id_localidad',$entity->fk_id_localidad)->whereHas("empresas",function ($q){$q->where('id_empresa',dataCompany()->id_empresa);})->pluck('sucursal','id_sucursal');
        }
        return [
            'clientes' => SociosNegocio::where('activo',1)->whereNotNull('fk_id_tipo_socio_venta')->whereHas('empresas',function ($empresa){
                $empresa->where('id_empresa',dataCompany()->id_empresa)->where('eliminar','f');
            })->pluck('nombre_comercial','id_socio_negocio'),
            'localidades' => Localidades::where('activo',1)->pluck('localidad','id_localidad'),
            'estatus' => EstatusDocumentos::select('estatus','id_estatus')->pluck('estatus','id_estatus'),
            'clasificaciones' => ClasificacionesProyectos::where('activo',1)->pluck('clasificacion','id_clasificacion_proyecto'),
            'monedas' => Monedas::where('activo',1)->selectRaw("CONCAT(descripcion,' (',moneda,')') as moneda, id_moneda")->orderBy('moneda')->pluck('moneda','id_moneda'),
            'tiposeventos' => TiposEventos::where('activo',1)->orderBy('tipo_evento')->pluck('tipo_evento','id_tipo_evento'),
            'dependencias' => Dependencias::where('activo',1)->orderBy('dependencia')->pluck('dependencia','id_dependencia'),
            'subdependencias' => Subdependencias::where('activo',1)->orderBy('subdependencia')->pluck('subdependencia','id_subdependencia'),
            'caracterevento' => CaracterEventos::where('activo',1)->orderBy('caracter_evento')->pluck('caracter_evento','id_caracter_evento'),
            'formaadjudicacion' => FormasAdjudicacion::where('activo',1)->orderBy('forma_adjudicacion')->pluck('forma_adjudicacion','id_forma_adjudicacion'),
            'modalidadesentrega' => ModalidadesEntrega::where('activo',1)->orderBy('modalidad_entrega')->pluck('modalidad_entrega','id_modalidad_entrega'),
            'sucursales' => $sucursales,
            'js_licitacion' => Crypt::encryptString('"select":["tipo_evento","dependencia","subdependencia","unidad","modalidad_entrega","caracter_evento","forma_adjudicacion","pena_convencional","tope_pena_convencional"],"conditions":[{"where":["no_oficial","$num_evento"]}]'),
            'js_sucursales' => Crypt::encryptString('"select":["id_sucursal as id","sucursal as text"],"conditions":[{"where":["fk_id_cliente",$fk_id_cliente]},{"where":["fk_id_localidad",$fk_id_localidad]},{"where":["activo","1"]}],"whereHas":[{"empresas":{"where":["id_empresa","'.dataCompany()->id_empresa.'"]}}]'),
            'js_contratos' => Crypt::encryptString('"select":["representante_legal_cliente","no_contrato","vigencia_fecha_inicio","vigencia_fecha_fin"],"conditions":[{"where":["no_oficial","$num_contrato"]}]'),
            'js_partidas' => Crypt::encryptString('"select":["clave","descripcion","cantidad_maxima","cantidad_minima","codigo_barras","costo"],"conditions":[{"where":["no_oficial","$num_contrato"]}]'),
            'js_subdependencias'=>Crypt::encryptString('"select":["id_subdependencia as id","subdependencia as text"],"conditions":[{"where":["fk_id_dependencia",$fk_id_dependencia]},{"where":["activo",1]}]')
        ];
    }
    
    public function store(Request $request, $company, $compact = false)
    {
        #Guardamos los archivos de los anexos en la ruta especificada
        if(isset($request->relations['has']['anexos'])){
            $detalles = $request->relations['has']['anexos'];
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('proyectos_anexos')->put($company.'/'.$request->proyecto.'/'.$filename, file_get_contents($myfile->getRealPath()));
                    if($file_save){
                    $arreglo = $request->relations;
                    $arreglo['has']['anexos'][$row]['archivo'] = $filename;
                        $request->merge(["relations"=>$arreglo]);
                    }
                }
            }
        }

        #Guardamos los archivos de los contratos en la ruta especificada
        if(isset($request->relations['has']['contratos'])){
            $detalles = $request->relations['has']['contratos'];
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('proyectos_contratos')->put($company.'/'.$request->proyecto.'/'.$filename, file_get_contents($myfile->getRealPath()));

                    if($file_save){
                        $arreglo = $request->relations;
                        $arreglo['has']['contratos'][$row]['archivo'] = $filename;
                        $request->merge(["relations"=>$arreglo]);
                    }
                }
            }
        }
        $arreglo = $request->relations;
        unset($arreglo['has']['productos']['$row_id']);
        $request->merge(["relations"=>$arreglo]);
        
        $return = parent::store($request, $company, true);
        
        /*
        if(!empty($return['entity']))
        {
            $email = 'juan.franco@lomedic.com';
            $options = [
                'asunto' => 'Nuevo Proyecto',
                'saludo'=>'Se a creado un nuevo proyecto "'.$return['entity']->proyecto.'"',
                'toplinea' => 'Se genero un nuevo proyecto para '.$return['entity']->cliente->nombre_comercial,
                'link' => 'Ver Proyecto',
                'href' => companyRoute('show', ['id' => $return['entity']->id_proyecto])
            ];
            
            $return['entity']->sendNotification($email,$options);
        }
        */
        
        return $return['redirect'];
    }
    
    public function update(Request $request, $company, $id, $compact = false)
    {
        #Guardamos los archivos de los anexos en la ruta especificada
        if(isset($request->relations['has']['anexos'])){
            $detalles = $request->relations['has']['anexos'];
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('proyectos_anexos')->put($company.'/'.$request->proyecto.'/'.$filename, file_get_contents($myfile->getRealPath()));

                    if($file_save){
                        $arreglo = $request->relations;
                        $arreglo['has']['anexos'][$row]['archivo'] = $filename;
                        $request->merge(["relations"=>$arreglo]);
                    }
                }
            }
        }
        #Guardamos los archivos de los contratos en la ruta especificada
        if(isset($request->relations['has']['contratos'])){
            $detalles = $request->relations['has']['contratos'];
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('proyectos_contratos')->put($company.'/'.$request->proyecto.'/'.$filename, file_get_contents($myfile->getRealPath()));

                    if($file_save){
                        $arreglo = $request->relations;
                        $arreglo['has']['contratos'][$row]['archivo'] = $filename;
                        $request->merge(["relations"=>$arreglo]);
                    }

                }
            }
        }
        $arreglo = $request->relations;
        unset($arreglo['has']['productos']['$row_id']);
        $request->merge(["relations"=>$arreglo]);
        
        return parent::update($request, $company, $id, $compact);
    }
    
    public function obtenerProyectos()
    {
        $proyectos = Proyectos::select('id_proyecto as id','proyecto as text')->where('fk_id_estatus',1)->get();
        return $proyectos->toJson();
    }
    
    public function obtenerProyectosCliente($company,$id)
    {
        return Response::json($this->entity->where('fk_id_cliente',$id)->select('proyecto as text','id_proyecto as id')->get());
    }

    public function layoutProductosProyecto()
    {
        Excel::create('producto_proyecto_layout', function($excel){
            $excel->sheet(currentEntityBaseName(), function($sheet){
                $sheet->fromArray(['*Clave cliente producto','UPC','*Prioridad','*Cantidad','*Precio sugerido','*Máximo','*Mínimo','*Número reorden']);
            });
        })->download('xlsx');
    }

    public function loadLayoutProductosProyectos($company,Request $request)
    {
        $respuesta = [];
        $data_xlsx = Excel::load($request->file('file')->getRealPath(), function($reader) { })->get();

        $data_xlsx = $data_xlsx->toArray();
        $data = [];
        $errores_clave = [];
        $errores_upc = [];
        foreach ($data_xlsx as $num=>$row) {
            $success_clave = false;
            $success_upc = false;
            $proyecto = ClaveClienteProductos::where('fk_id_cliente',$request->fk_id_cliente)->where('clave_producto_cliente','LIKE',$row['clave_cliente_producto'])->first();
            if(empty($proyecto)){
                $errores_clave[$num] = $row['clave_cliente_producto'];
            }else{
                $row['id_clave_cliente_producto'] = $proyecto->id_clave_cliente_producto;
                $row['descripcion_clave'] = $proyecto->descripcion;
                $success_clave = true;
            }

            if(!empty($row['upc']) && $success_clave){
                $upc = ClaveClienteProductos::where('fk_id_cliente',$request->fk_id_cliente)->where('clave_producto_cliente','LIKE',$row['clave_cliente_producto'])->first()->sku()->first()->upcs()->where('upc',$row['upc'])->first();
                if(empty($upc)){
                    $errores_upc[$num] = $row['upc'];
                }else{
                    $row['fk_id_upc'] = $upc->id_upc;
                    $row['descripcion_upc'] = $upc->descripcion;
                    $success_upc = true;
                }
            }else{
                $success_upc = true;
            }
            if(($success_clave && $success_upc)){
                $data[] = $row;
            }
        }
        $respuesta[0] = $data;//Filas que sí se encontraron al final
        $respuesta[1] = $errores_clave;//Filas con error en la clave
        $respuesta[2] = $errores_upc;//Filas con error en el UPC
        return Response::json($respuesta);
    }
    
    public function descargaranexo($company, $id)
    {
        $archivo = AnexosProyectos::where('id_anexo',$id)->first();
        $file = Storage::disk('proyectos_anexos')->getDriver()->getAdapter()->getPathPrefix().$company.'/'.$archivo->proyecto->proyecto.'/'.$archivo->archivo;

        if (File::exists($file))
        {
            Logs::createLog($archivo->getTable(), $company, $archivo->id_anexo, 'descargar', 'Archivo anexo de proyecto');
            return Response::download($file);
        }
        else
            App::abort(404,'No se encontro el archivo o recurso que se solicito.');
    }
    
    public function descargarcontrato($company, $id)
    {

        $archivo = ContratosProyectos::where('id_contrato',$id)->first();
        $file = Storage::disk('proyectos_contratos')->getDriver()->getAdapter()->getPathPrefix().$company.'/'.$archivo->proyecto->proyecto.'/'.$archivo->archivo;

        if (File::exists($file))
        {
            Logs::createLog($archivo->getTable(), $company, $archivo->id_contrato, 'descargar', 'Archivo contrato de proyecto');
            return Response::download($file);
        }
        else
            App::abort(404,'No se encontro el archivo o recurso que se solicito.');
    }

    public function getClavesClientesProductos($company, Request $request)
    {
        $data = [];
        $errores_clave = [];
        $errores_upc = [];
        foreach($request->productos as $key => $producto_liciplus){
            $success_clave = false;
            $success_upc = false;
            $producto = ClaveClienteProductos::where('fk_id_cliente',$request->fk_id_cliente)->where('clave_producto_cliente','LIKE',$producto_liciplus['clave'])->first();
            if(empty($producto)){
                $errores_clave[$key] = $producto_liciplus['clave'];
            }else{
                $producto_liciplus['id_clave_cliente_producto'] = $producto->id_clave_cliente_producto;
                $producto_liciplus['descripcion_clave'] = $producto->descripcion;
                $success_clave = true;
            }

            if(!empty($producto_liciplus['codigo_barras']) && $success_clave){
                $upc = ClaveClienteProductos::where('fk_id_cliente',$request->fk_id_cliente)->where('clave_producto_cliente','LIKE',$producto_liciplus['clave'])->first()->sku()->first()->upcs()->where('upc',$producto_liciplus['codigo_barras'])->first();
                if(empty($upc)){
                    $errores_upc[$key] = $producto_liciplus['codigo_barras'];
                }else{
                    $producto_liciplus['fk_id_upc'] = $upc->id_upc;
                    $producto_liciplus['descripcion'] = $upc->descripcion;
                    $success_upc = true;
                }
            }else{
                $success_upc = true;
            }
            if($success_clave && $success_upc){
                $data[] = $producto_liciplus;
            }
        }
        $respuesta[0] = $data;//Filas que sí se encontraron al final
        $respuesta[1] = $errores_clave;//Filas con error en la clave
        $respuesta[2] = $errores_upc;//Filas con error en el código de barras
        return Response::json($respuesta);
    }
}