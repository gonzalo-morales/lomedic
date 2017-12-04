<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\RegimenFiscal;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Logs;
use App;
use DB;
use File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Models\Administracion\Certificados;

class EmpresasController extends ControllerBase
{
    public function __construct(Empresas $entity)
	{
		$this->entity = $entity;
	}
	
	
	public function getDataView($entity = null)
	{
	    return [
	        'regimens'         => RegimenFiscal::where('activo','1')->where('eliminar','0')->pluck('regimen_fiscal','id_regimen_fiscal')->sortBy('regimen_fiscal')->prepend('Selecciona una opcion...',''),
	        'paises'           => Paises::where('activo','1')->where('eliminar','0')->pluck('pais','id_pais')->sortBy('pais')->prepend('Selecciona una opcion...',''),
	        'js_estados'       => Crypt::encryptString('"select": ["estado", "id_estado"], "conditions": [{"where": ["fk_id_pais","$fk_id_pais"]}], "orderBy": [["estado", "ASC"]]'),
	        'js_municipios'    => Crypt::encryptString('"select": ["municipio", "id_municipio"], "conditions": [{"where": ["fk_id_estado","$fk_id_estado"]}], "orderBy": [["municipio", "ASC"]]'),
	    ];
	}
	
	/*
	public function store(Request $request, $company)
	{
	    # ¿Usuario tiene permiso para crear?
	    #$this->authorize('create', $this->entity);
	    
	    # Validamos request, si falla regresamos pagina
	    $this->validate($request, $this->entity->rules);
	    
	    DB::beginTransaction();
	    $entity = $this->entity->create($request->all());
	    
	    if ($entity)
	    {
	        $id = $entity->id_socio_negocio;
	        
	        # Guardamos el detalle de empresas en la que estara disponible
	        if(isset($request->empresas)) {
	            $sync = [];
	            foreach ($request->empresas as $id_empresa=>$active) {
	                if($active) {
	                    $sync[] = $id_empresa;
	                }
	            }
     	            $entity->empresas()->sync($sync);
	        }
	        
	        # Guardamos el detalle de formas de pago para el socio de negocio
	        if(isset($request->formaspago)) {
	            $sync = [];
	            foreach ($request->formaspago as $id_forma=>$active) {
	                if($active) {
	                    $sync[] = $id_forma;
	                }
	            }
	            $entity->formaspago()->sync($sync);
	        }
	        
	        # Guardamos los contactos de los socios de negocio
	        if(isset($request->contactos)){
	            $contactos = collect($request->contactos);
	            
	            #Insertar o Actualizar la informacion de los contactos
	            foreach ($contactos as $contacto)
	            {
	                array_unshift($contacto, ['fk_id_socio_negocio'=> $id]);
	                $entity->contactos()->updateOrCreate(['id_contacto' => ($contacto['id_contacto'] ?? null)], $contacto);
	            }
	        }
	        
	        # Guardamos el detalle de las direcciones de los socios de negocio
	        if(isset($request->direcciones)){
	            $direcciones = collect($request->direcciones);

	            #Inserta o Actualiza la informacion del direcciones
	            foreach ($direcciones as $direccion)
	            {
	                array_unshift($direccion, ['fk_id_socio_negocio'=> $id]);
	                $entity->direcciones()->updateOrCreate(['id_direccion' => ($direccion['id_direccion'] ?? null)], $direccion);
	            }
	        }
	        
	        # Guardamos el detalle de las cuentas bancarias de los socios de negocio
	        if(isset($request->cuentas)){
	            $cuentas = collect($request->cuentas);
	            
	            #Inserta o Actualiza la informacion del cuentas
	            foreach ($cuentas as $cuenta)
	            {
	                array_unshift($cuenta, ['fk_id_socio_negocio'=> $id]);
	                $entity->cuentas()->updateOrCreate(['id_cuenta' => ($cuenta['id_cuenta'] ?? null)], $cuenta);
	            }
	        }
	        
	        # Guardamos el detalle de los anexos de los socios de negocio
	        if(isset($request->anexos)){
	            $anexos = $request->anexos;
	            
	            #Inserta o Actualiza la informacion del anexo
	            foreach ($anexos as $anexo)
	            {
	                if(isset($anexo['archivo'])) {
	                    $myfile = $anexo['archivo'];
	                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
	                    $file_save = Storage::disk('socios_anexos')->put($filename, file_get_contents($myfile->getRealPath()));
	                
	                    if($file_save) {
	                        array_unshift($anexo, ['fk_id_socio_negocio'=> $id]);
	                        $anexo['archivo'] = $filename;
	                        $entity->anexos()->updateOrCreate(['id_anexo' => null], $anexo);
	                    }
	                }
	            }
	        }
	        
	        # Guardamos el detalle de los productos de los socios de negocio
	        if(isset($request->productos)){
	            $productos = collect($request->productos);
	            
	            #Inserta o Actualiza la informacion del productos
	            foreach ($productos as $producto)
	            {
	                array_unshift($producto, ['fk_id_socio_negocio'=> $id]);
	                $entity->productos()->updateOrCreate(['id_producto' => ($producto['id_producto'] ?? null)], $producto);
	            }
	        }
	        
	        DB::commit();
	        
	        # Eliminamos cache
	        Cache::tags(getCacheTag('index'))->flush();
	        
	        
	        $this->log('store', $id);
	        return $this->redirect('store');
	    } else {
	        DB::rollBack();
	        $this->log('error_store');
	        return $this->redirect('error_store');
	    }
	}
	*/
	
	public function update(Request $request, $company, $id)
	{
	    # ¿Usuario tiene permiso para actualizar?
	    #$this->authorize('update', $this->entity);
	    
	    # Validamos request, si falla regresamos atras
	    $this->validate($request, $this->entity->rules);
	    
	    $icono = Input::file('f-icono');
	    if(!empty($icono)) {
	        $ext = substr($icono->getClientOriginalName(),(strrpos($icono->getClientOriginalName(),'.')));
	        $ico_name = strtolower(str_replace([':',' '],['-','_'],$request->nombre_comercial.'-ico'.$ext));
	        Storage::disk('logotipos')->put($ico_name, file_get_contents($icono->getRealPath()));
	        
	        $request->request->set('icono',$ico_name);
	    }
	    
	    $logotipo = Input::file('f-logotipo');
	    if(!empty($logotipo)) {
	        $ext = substr($logotipo->getClientOriginalName(),(strrpos($logotipo->getClientOriginalName(),'.')));
	        $logo_name = strtolower(str_replace([':',' '],['-','_'],$request->nombre_comercial.$ext));
	        Storage::disk('logotipos')->put($logo_name, file_get_contents($logotipo->getRealPath()));
	        
	        $request->request->set('logotipo',$logo_name);
	    }
	    
	    #DB::beginTransaction();
	    $entity = $this->entity->findOrFail($id);
	    $entity->fill($request->all());
	    
	    if ($entity->save())
	    {
	        # Guardamos el detalle de los certificados de las empresas
	        if(isset($request->certificados)){
	            $certificados = $request->certificados;
	            
	            #Elimina los contactos que existian y que no se encuentran en el arreglo de datos
	            $ids_certificados = collect($certificados)->pluck('id_certificado');
	            $entity->certificados()->whereNotIn('id_certificado', $ids_certificados)->update(['eliminar' => 1]);
	            
	            #Inserta o Actualiza la informacion del contacto
	            foreach ($certificados as $certificado)
	            {
	                if(isset($certificado['archivo'])) {
	                    $myfile = $certificado['archivo'];
	                    $filename = $myfile->getClientOriginalName();
	                    $file_save = Storage::disk('certificados')->put($request->conexion.'/'.$filename, file_get_contents($myfile->getRealPath()));

    	                if($file_save) {
    	                    array_unshift($certificado, ['fk_id_empresa'=> $id]);
    	                    $certificado['archivo'] = $filename;
    	                    $entity->certificados()->updateOrCreate(['id_certificado' => null], $certificado);
    	                }
	                }
	            }
	        }
	        else {
	            $entity->certificados()->update(['eliminar' => 1]);
	        }
	        
	        # Guardamos el detalle de las series de las empresas
	        /*
	        if(isset($request->productos)){
	            $productos = collect($request->productos);
	            
	            #Elimina los contactos que existian y que no se encuentran en el arreglo de datos
	            $ids_productos = $productos->pluck('id_producto');
	            $entity->productos()->update(['eliminar' => 1]);
	            
	            #Inserta o Actualiza la informacion del contacto
	            foreach ($productos as $producto)
	            {
	                array_unshift($producto, ['fk_id_socio_negocio'=> $id]);
	                $entity->productos()->updateOrCreate(['id_producto' => ($producto['id_producto'] ?? null)], $producto);
	            }
	        }
	        else {
	            $entity->productos()->where('fk_id_socio_negocio', $id)->update(['eliminar' => 1]);
	        }
	        */
	        
	        #DB::commit();
	        
	        # Eliminamos cache
	        Cache::tags(getCacheTag('index'))->flush();
	        
	        $this->log('update', $id);
	        return $this->redirect('update');
	    } else {
	        #DB::rollBack();
	        $this->log('error_update', $id);
	        return $this->redirect('error_update');
	    }
	}
	
	public function descargar($company, $id)
	{
	    $archivo = Certificados::where('id_certificado',$id)->first();
	    $file = Storage::disk('certificados')->getDriver()->getAdapter()->getPathPrefix().$archivo->empresa->conexion.'/'.$archivo->archivo;
	    
	    if (File::exists($file))
	    {
	        Logs::createLog($archivo->getTable(), $company, $archivo->id_certificado, 'descargar', 'Archivo Certificado');
	        return Response::download($file);
	    }
	    else {
	        App::abort(404,'No se encontro el archivo o recurso que se solicito.');
	    }
	}
}