<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Proyectos\ClasificacionesProyectos;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\Proyectos\ProyectosProductos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Proyectos\AnexosProyectos;
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

class ProyectosController extends ControllerBase
{
    public function __construct(Proyectos $entity)
    {
        $this->entity = $entity;
    }

    public function create($company, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
            'clientes' => SociosNegocio::where('activo', 1)->where('eliminar', 0)->whereNotNull('fk_id_tipo_socio_venta')->pluck('nombre_comercial','id_socio_negocio'),
            'clasificaciones' => ClasificacionesProyectos::where('activo',1)->pluck('clasificacion','id_clasificacion_proyecto'),
            ]];

        return parent::create($company, $attributes);
    }

    public function obtenerProyectos()
    {
        $proyectos = Proyectos::all()->where('activo','1');
        foreach ($proyectos as $proyecto)
        {
            $proyecto_data['id'] = (int)$proyecto->id_proyecto;
            $proyecto_data['text'] = $proyecto->proyecto;
            $proyectos_set[] = $proyecto_data;
        }
        return Response::json($proyectos_set);
    }

    public function obtenerProyectosCliente($company,$id)
    {
        return Response::json($this->entity->where('fk_id_cliente',$id)->select('proyecto as text','id_proyecto as id')->get());
    }

    public function store(Request $request, $company)
    {
        # ¿Usuario tiene permiso para crear?
//		$this->authorize('create', $this->entity);

        $request->request->set('activo',!empty($request->request->get('activo')));

        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);

        $isSuccess = $this->entity->create($request->all());
        if ($isSuccess) {
            $id = $isSuccess->id_proyecto;

            # Guardamos el detalle de los productos del proyecto
            if(isset($request->_productoProyecto)){
                foreach ($request->_productoProyecto as $proyecto_producto) {
                    if(empty($proyecto_producto['fk_id_upc'])){
                        $proyecto_producto['fk_id_upc'] = null;
                    }
                    if(!isset($proyecto_producto['activo'])){
                        $proyecto_producto['activo'] = '0';
                    }
                    $proyecto_producto['fk_id_proyecto'] = $isSuccess->id_proyecto;
                    $ProyectosProductos = new ProyectosProductos();
                    $ProyectosProductos->create($proyecto_producto);
                }
            }
            
            # Guardamos el detalle de los anexos del proyecto
            if(isset($request->anexos)){
                $anexos = $request->anexos;
                
                #Inserta o Actualiza la informacion del anexo
                foreach ($anexos as $anexo)
                {
                    if(isset($anexo['archivo'])) {
                        $myfile = $anexo['archivo'];
                        $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                        $file_save = Storage::disk('proyectos_anexos')->put($company.'/'.$id.'/'.$filename, file_get_contents($myfile->getRealPath()));
                        
                        if($file_save) {
                            array_unshift($anexo, ['fk_id_proyecto'=> $id]);
                            $anexo['archivo'] = $filename;
                            $isSuccess->anexos()->updateOrCreate(['id_anexo' => null], $anexo);
                        }
                    }
                }
            }

            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();

            $this->log('store', $isSuccess->id_banco);
            return $this->redirect('store');
        } else {
            $this->log('error_store');
            return $this->redirect('error_store');
        }
    }

    public function show($company, $id, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
            'clientes' => SociosNegocio::where('activo', 1)->where('eliminar', 0)->whereNotNull('fk_id_tipo_socio_venta')->pluck('nombre_comercial','id_socio_negocio'),
            'clasificaciones' => ClasificacionesProyectos::where('activo',1)->pluck('clasificacion','id_clasificacion_proyecto'),
        ]];

        return parent::show($company, $id, $attributes); // TODO: Change the autogenerated stub
    }

    public function edit($company, $id, $attributes = [])
    {
        $attributes = $attributes + ['dataview'=>[
            'clientes' => SociosNegocio::where('activo', 1)->where('eliminar', 0)->whereNotNull('fk_id_tipo_socio_venta')->pluck('nombre_comercial','id_socio_negocio'),
            'clasificaciones' => ClasificacionesProyectos::where('activo',1)->pluck('clasificacion','id_clasificacion_proyecto'),
        ]];

        return parent::edit($company, $id, $attributes); // TODO: Change the autogenerated stub
    }

    public function update(Request $request, $company, $id)
    {
        $request->request->set('activo',!empty($request->request->get('activo')));
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);
        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        if ($entity->save()) {
            # Si tienes relaciones
            if (isset($request->productoProyecto)) {
                foreach ($request->productoProyecto as $detalle) {
                    $productoProyecto = $entity
                        ->findOrFail($id)
                        ->ProyectosProductos()
                        ->where('id_proyecto_producto',$detalle['id_proyecto_producto'])
                        ->first();
                    $productoProyecto->fill($detalle);
                    $productoProyecto->save();
                }
            }
            if(isset($request->_productoProyecto)){
                foreach ($request->_productoProyecto as $detalle){
                    if(empty($detalle['fk_id_upc'])){
                        $detalle['fk_id_upc'] = null;
                    }
                    if(!isset($detalle['activo'])){
                        $detalle['activo'] = '0';
                    }
                    $detalle['fk_id_proyecto'] = $id;
                    $ProyectosProductos = new ProyectosProductos();
                    $ProyectosProductos->create($detalle);
                }
            }
            
            # Guardamos el detalle de los anexos del proyecto
            if(isset($request->anexos)){
                $anexos = $request->anexos;
                
                #Elimina los contactos que existian y que no se encuentran en el arreglo de datos
                $ids_anexos = collect($anexos)->pluck('id_anexo');
                $entity->anexos()->whereNotIn('id_anexo', $ids_anexos)->update(['eliminar' => 1]);
                
                #Inserta o Actualiza la informacion del contacto
                foreach ($anexos as $anexo)
                {
                    if(isset($anexo['archivo'])) {
                        $myfile = $anexo['archivo'];
                        $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
                        $file_save = Storage::disk('proyectos_anexos')->put($company.'/'.$id.'/'.$filename, file_get_contents($myfile->getRealPath()));
                        
                        if($file_save) {
                            array_unshift($anexo, ['fk_id_proyecto'=> $id]);
                            $anexo['archivo'] = $filename;
                            $entity->anexos()->updateOrCreate(['id_anexo' => null], $anexo);
                        }
                    }
                }
            }
            else {
                $entity->anexos()->update(['eliminar' => 1]);
            }
            
            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();

            $this->log('update', $id);
            return $this->redirect('update');
        } else {
            $this->log('error_update', $id);
            return $this->redirect('error_update');
        }
    }

    public function layoutProductosProyecto()
    {
        Excel::create('producto_proyecto_layout', function($excel){
            $excel->sheet(currentEntityBaseName(), function($sheet){
                $sheet->fromArray(['*Clave cliente producto','UPC','*Prioridad','*Cantidad','*Precio sugerido',
                    '*Máximo','*Mínimo','*Número reorden']);
            });
        })->download('xlsx');
    }

    public function loadLayoutProductosProyectos($company,Request $request)
    {
        $respuesta = [];
        $data_xlsx = Excel::load($request->file('file')->getRealPath(), function($reader) {
//            return $reader->takeRows(10);
        })->get();

        $data_xlsx = $data_xlsx->toArray();
        $data = [];
        $errores_clave = [];
        $errores_upc = [];
        $index = 1;
        foreach ($data_xlsx as $row){
            $index++;
            $success_clave = false;
            $success_upc = false;
            $proyecto = ClaveClienteProductos::where('fk_id_cliente',$request->fk_id_cliente)
                ->where('clave_producto_cliente','LIKE',$row['clave_cliente_producto'])->first();
            if(empty($proyecto)){
                $errores_clave[$index] = $row['clave_cliente_producto'];
            }else{
                $row['id_clave_cliente_producto'] = $proyecto->id_clave_cliente_producto;
                $row['descripcion_clave'] = $proyecto->descripcion;
                $success_clave = true;
            }

            if(!empty($row['upc']) && $success_clave){
                $upc = ClaveClienteProductos::where('fk_id_cliente',$request->fk_id_cliente)
                    ->where('clave_producto_cliente','LIKE',$row['clave_cliente_producto'])->first()->sku()->first()->upcs()->where('upc',$row['upc'])->first();
//                dump($upc);
//                $row['fk_id_upc'] = ->id_upc;
//                $row['descripcion_upc'] = ClaveClienteProductos::where('fk_id_cliente',$request->fk_id_cliente)
//                    ->where('clave_producto_cliente','LIKE',$row['clave_cliente_producto'])->first()->sku()->first()->upcs()->where('upc',$row['upc'])->first()->descripcion;
                if(empty($upc)){
                    $errores_upc[$index] = $row['upc'];
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
            }}
            $respuesta[0] = $data;//Filas que sí se encontraron al final
            $respuesta[1] = $errores_clave;//Filas con error en la clave
            $respuesta[2] = $errores_upc;//Filas con error en el UPC
        return Response::json($respuesta);
    }
    
    public function descargar($company, $id)
    {
        $archivo = AnexosProyectos::where('id_anexo',$id)->first();
        $file = Storage::disk('proyectos_anexos')->getDriver()->getAdapter()->getPathPrefix().$company.'/'.$archivo->fk_id_proyecto.'/'.$archivo->archivo;

        if (File::exists($file))
        {
            
            Logs::createLog($archivo->getTable(), $company, $archivo->id_anexo, 'descargar', 'Archivo anexo de proyecto');
            return Response::download($file);
        }
        else {
            App::abort(404,'No se encontro el archivo o recurso que se solicito.');
        }
    }
}