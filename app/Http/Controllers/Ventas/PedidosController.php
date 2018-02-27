<?php
namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ClasificacionesProyectos;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\SociosNegocio;
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
use App\Http\Models\Ventas\Pedidos;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Ventas\PedidosAnexos;
use App\Http\Models\Administracion\TiposEventos;
use App\Http\Models\Administracion\Dependencias;
use App\Http\Models\Administracion\Subdependencias;

class PedidosController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Pedidos;
    }
    
    public function getDataView($entity = null)
    {
        $empresa_actual = Empresas::where('activo',1)->where('conexion',request()->company)->first();
        return [
            'localidades' => Localidades::where('activo',1)->pluck('localidad','id_localidad'),
            'clientes' => SociosNegocio::select('nombre_comercial','id_socio_negocio')->where('activo',1)->whereNotNull('fk_id_tipo_socio_venta')
                ->whereHas('empresas', function($q) use($empresa_actual) {
                    $q->where('id_empresa','=',$empresa_actual->id_empresa);
                })->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
            'proyectos' => empty($entity) ? [] : Proyectos::select('proyecto','id_proyecto')->where('fk_id_estatus',1)->where('fk_id_cliente', $entity->fk_id_socio_negocio)->pluck('proyecto','id_proyecto'),
            'js_proyectos' => Crypt::encryptString('"select": ["proyecto", "id_proyecto"], "conditions": [{"where": ["fk_id_estatus",1]}, {"where": ["fk_id_cliente","$fk_id_cliente"]}], "sortBy":["proyecto"]'),
            'sucursales' => empty($entity) ? [] : Sucursales::select('sucursal','id_sucursal')->where('activo',1)->where('fk_id_cliente', $entity->fk_id_socio_negocio)->pluck('sucursal','id_sucursal'),
            'js_sucursales' => Crypt::encryptString('"select": ["sucursal", "id_sucursal"], "conditions": [{"where": ["activo",1]}, {"where": ["fk_id_cliente","$fk_id_cliente"]}, {"where": ["fk_id_localidad","$fk_id_localidad"]}], "orderBy": [["sucursal", "ASC"]]'),
            'contratos' => empty($entity) ? [] : ContratosProyectos::select('num_contrato','id_contrato')->where('fk_id_proyecto', $entity->fk_id_proyecto)->pluck('num_contrato','id_contrato'),
            'js_contratos' => Crypt::encryptString('"select":["id_proyecto"], "conditions":[{"where":["id_proyecto","$id_proyecto"]}], "with":["contratos:id_contrato,num_contrato,fk_id_proyecto"]'),
            'ejecutivos' => Empleados::selectRaw("CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno)nombre_empleado, id_empleado")->where('activo',1)->where('fk_id_departamento',19)->orderBy('nombre_empleado')->pluck('nombre_empleado','id_empleado'),
            'estatus' => empty($entity) ? EstatusDocumentos::select('estatus','id_estatus')->where('id_estatus',1)->pluck('estatus','id_estatus') : EstatusDocumentos::select('estatus','id_estatus')->where('id_estatus',$entity->fk_id_estatus)->pluck('estatus','id_estatus'),
            'productos' => empty($entity) ? [] : ClaveClienteProductos::select('id_clave_cliente_producto as id','clave_producto_cliente as text','descripcion as descripcionClave','fk_id_sku')->where('fk_id_cliente',$entity->fk_id_socio_negocio)->pluck('descripcion','id_clave_cliente_producto'),
            'monedas' => Monedas::where('activo',1)->pluck('descripcion','id_moneda'),
            'clasificaciones' => ClasificacionesProyectos::where('activo',1)->pluck('clasificacion','id_clasificacion_proyecto'),
            'monedas' => Monedas::selectRaw("CONCAT(descripcion,' (',moneda,')') as moneda, id_moneda")->where('activo',1)->orderBy('moneda')->pluck('moneda','id_moneda'),
            'tiposeventos' => TiposEventos::where('activo',1)->orderBy('tipo_evento')->pluck('tipo_evento','id_tipo_evento'),
            'dependencias' => Dependencias::where('activo',1)->orderBy('dependencia')->pluck('dependencia','id_dependencia'),
            'subdependencias' => Subdependencias::where('activo',1)->orderBy('subdependencia')->pluck('subdependencia','id_subdependencia'),
        ];
    }
    
    public function store(Request $request, $company, $compact = false)
    {
        #Guardamos los archivos de los anexos en la ruta especificada
        if(isset($request->relations->has->anexos)){
            $detalles = $request->relations->has->anexos;
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('pedidos_anexos')->put($company.'/'.$id.'/'.$filename, file_get_contents($myfile->getRealPath()));
                    
                    if($file_save)
                        $request->relations->has->anexos->{$row}->set('archivo',$filename);
                }
            }
        }
        
        #dd($request->all());
        
        return parent::store($request, $company, $compact);
    }
    
    public function update(Request $request, $company, $id, $compact = false)
    {
        #Guardamos los archivos de los anexos en la ruta especificada
        if(isset($request->relations->has->anexos)){
            $detalles = $request->relations->has->anexos;
            foreach ($detalles as $row=>$detalle)
            {
                if(isset($detalle['file'])) {
                    $myfile = $detalle['file'];
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                    $file_save = Storage::disk('pedidos_anexos')->put($company.'/'.$id.'/'.$filename, file_get_contents($myfile->getRealPath()));
                    
                    if($file_save)
                        $request->relations->has->anexos->{$row}->set('archivo',$filename);
                }
            }
        }
        
        return parent::update($request, $company, $id, $compact);
    }
    
    public function layoutProductos()
    {
        Excel::create('producto_layout', function($excel){
            $excel->sheet(currentEntityBaseName(), function($sheet){
                $sheet->fromArray(['* Cantidad','* Clave cliente producto','UPC']);
            });
        })->download('xlsx');
    }

    public function ImportarProductos($company,Request $request)
    {
        $respuesta = [];
        $data_xlsx = Excel::load($request->file('file')->getRealPath(), function($reader) { })->get();

        $data_xlsx = $data_xlsx->toArray();
        $data = [];
        $errores = [];
        
        
        foreach ($data_xlsx as $num=>$row)
        {
            
            if($row['cantidad'] <= 0)
                $errores[$num]['cantidad'] = $row['cantidad'];
                
            $ClaveCliente = ClaveClienteProductos::where('fk_id_cliente',$request->fk_id_cliente)->where('clave_producto_cliente','=',$row['clave_cliente_producto'])->first();
            
            if(empty($ClaveCliente)){
                $errores[$num]['clave_producto'] = $row['clave_cliente_producto'];
            }else{
                $row['id_clave_cliente_producto'] = $ClaveCliente->id_clave_cliente_producto;
                $row['descripcion_clave'] = $ClaveCliente->descripcion;
            }

            if(!empty($row['upc']) && !empty($ClaveCliente)){
                $sku = $ClaveCliente->sku()->first();
                if(!empty($sku)){
                    $row['fk_id_sku'] = $sku->id_sku;
                }
                $upc = $ClaveCliente->sku()->first()->upcs()->where('upc',$row['upc'])->first();
                if(empty($upc)){
                    $errores[$num]['upc'] = $row['upc'];
                }else{
                    $row['fk_id_upc'] = $upc->id_upc;
                    $row['descripcion_upc'] = $upc->descripcion;
                }
            }
            
            if(!isset($errores[$num])) {
                $data[] = $row;
            }
        }
        
        
        $respuesta['data']  = $data;//Filas que sí se encontraron al final
        $respuesta['error'] = $errores;//Filas con error

        return Response::json($respuesta);
        
    }
    
    public function descargaranexo($company, $id)
    {
        $archivo = PedidosAnexos::where('id_anexo',$id)->first();
        $file = Storage::disk('pedidos_anexos')->getDriver()->getAdapter()->getPathPrefix().$company.'/'.$archivo->fk_id_proyecto.'/'.$archivo->archivo;

        if (File::exists($file))
        {
            Logs::createLog($archivo->getTable(), $company, $archivo->id_anexo, 'descargar', 'Archivo anexo de pedido');
            return Response::download($file);
        }
        else
            App::abort(404,'No se encontro el archivo o recurso que se solicito.');
    }
}