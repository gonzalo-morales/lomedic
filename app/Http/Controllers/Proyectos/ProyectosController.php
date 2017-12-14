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
use App\Http\Models\Logs;
use App;
use DB;
use File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProyectosController extends ControllerBase
{
    public function __construct(Proyectos $entity)
    {
        $this->entity = $entity;
    }
    
    public function getDataView($entity = null)
    {
        return [
            'clientes' => SociosNegocio::where('activo', 1)->where('eliminar', 0)->whereNotNull('fk_id_tipo_socio_venta')->pluck('nombre_comercial','id_socio_negocio'),
            'localidades' => Localidades::where('activo',1)->where('eliminar',0)->pluck('localidad','id_localidad'),
            'estatus' => EstatusDocumentos::select('estatus','id_estatus')->pluck('estatus','id_estatus'),
            'clasificaciones' => ClasificacionesProyectos::where('activo',1)->pluck('clasificacion','id_clasificacion_proyecto'),
            'monedas' => Monedas::selectRaw("CONCAT(descripcion,' (',moneda,')') as moneda, id_moneda")->where('activo','1')->where('eliminar','0')->orderBy('moneda')->pluck('moneda','id_moneda')->prepend('Selecciona una opcion...',''),
        ];
    }
    
    public function store(Request $request, $company)
    {
        #Guardamos los archivos de los anexos en la ruta especificada
        if(isset($request->relations->has->anexos)){
            $detalles = $request->relations->has->anexos;
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('proyectos_anexos')->put($company.'/'.$id.'/'.$filename, file_get_contents($myfile->getRealPath()));
                    
                    if($file_save)
                        $request->relations->has->anexos->{$row}->set('archivo',$filename);
                }
            }
        }
        
        #Guardamos los archivos de los contratos en la ruta especificada
        if(isset($request->relations->has->contratos)){
            $detalles = $request->relations->has->contratos;
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('proyectos_contratos')->put($company.'/'.$id.'/'.$filename, file_get_contents($myfile->getRealPath()));
                    
                    if($file_save)
                        $request->relations->has->contratos->{$row}->set('archivo',$filename);
                }
            }
        }
        return parent::store($request, $company);
    }
    
    public function update(Request $request, $company, $id)
    {
        #Guardamos los archivos de los anexos en la ruta especificada
        if(isset($request->relations->has->anexos)){
            $detalles = $request->relations->has->anexos;
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('proyectos_anexos')->put($company.'/'.$id.'/'.$filename, file_get_contents($myfile->getRealPath()));
                    
                    if($file_save)
                        $request->relations->has->anexos->{$row}->set('archivo',$filename);
                }
            }
        }
        
        #Guardamos los archivos de los contratos en la ruta especificada
        if(isset($request->relations->has->contratos)){
            $detalles = $request->relations->has->contratos;
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('proyectos_contratos')->put($company.'/'.$id.'/'.$filename, file_get_contents($myfile->getRealPath()));
                    
                    if($file_save)
                        $request->relations->has->contratos->{$row}->set('archivo',$filename);
                }
            }
        }
        return parent::update($request, $company, $id);
    }
    
    public function obtenerProyectos()
    {
        $proyectos = Proyectos::select('id_proyecto as id','proyecto as text')->where('eliminar',0)->get();
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
        $file = Storage::disk('proyectos_anexos')->getDriver()->getAdapter()->getPathPrefix().$company.'/'.$archivo->fk_id_proyecto.'/'.$archivo->archivo;

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
        $file = Storage::disk('proyectos_contratos')->getDriver()->getAdapter()->getPathPrefix().$company.'/'.$archivo->fk_id_proyecto.'/'.$archivo->archivo;
        
        if (File::exists($file))
        {
            Logs::createLog($archivo->getTable(), $company, $archivo->id_contrato, 'descargar', 'Archivo contrato de proyecto');
            return Response::download($file);
        }
        else
            App::abort(404,'No se encontro el archivo o recurso que se solicito.');
    }
}