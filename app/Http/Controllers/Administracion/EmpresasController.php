<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\RegimenFiscal;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Administracion\Certificados;
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

class EmpresasController extends ControllerBase
{
    public function __construct(Empresas $entity)
	{
		$this->entity = $entity;
	}
	
	public function getDataView($entity = null)
	{
	    $cer = 'C:\Users\jfranco\Desktop\CSD_AIS091015H50_20160801150531\CSD_Abisa_2016_AIS091015H50_20160801_150445s.cer';
	    //print_r($this->readCer($cer));
	    return [
	        'regimens'         => RegimenFiscal::where('activo','1')->where('eliminar','0')->pluck('regimen_fiscal','id_regimen_fiscal')->sortBy('regimen_fiscal')->prepend('Selecciona una opcion...',''),
	        'paises'           => Paises::where('activo','1')->where('eliminar','0')->pluck('pais','id_pais')->sortBy('pais')->prepend('Selecciona una opcion...',''),
	        'js_estados'       => Crypt::encryptString('"select": ["estado", "id_estado"], "conditions": [{"where": ["fk_id_pais","$fk_id_pais"]}], "orderBy": [["estado", "ASC"]]'),
	        'js_municipios'    => Crypt::encryptString('"select": ["municipio", "id_municipio"], "conditions": [{"where": ["fk_id_estado","$fk_id_estado"]}], "orderBy": [["municipio", "ASC"]]'),
	    ];
	}
	
	
	public function store(Request $request, $company, $compact = false)
	{
	    # ¿Usuario tiene permiso para crear?
	    #$this->authorize('create', $this->entity);
	    
	    # Validamos request, si falla regresamos pagina
	    $this->validate($request, $this->entity->rules);
	    
	    #DB::beginTransaction();
	    $entity = $this->entity->create($request->all());
	    
	    if ($entity) {
	        $id = $entity->id_empresa;
	        
	        # Guardamos el detalle de los certificados de las empresas
	        if(isset($request->certificados)){
	            $certificados = $request->certificados;
	            
	            foreach ($certificados as $certificado) {
	                $save_key = false;
	                $save_cer = false;
	                if(isset($certificado['key-file'])) {
	                    $mykey = $certificado['key-file'];
	                    $namekey = $mykey->getClientOriginalName();
	                    $save_key = Storage::disk('certificados')->put($request->conexion.'/'.$namekey, file_get_contents($mykey->getRealPath()));
	                }
	                if(isset($certificado['cer-file'])) {
	                    $mycer = $certificado['cer-file'];
	                    $namecer = $mycer->getClientOriginalName();
	                    $save_cer = Storage::disk('certificados')->put($request->conexion.'/'.$namecer, file_get_contents($mycer->getRealPath()));
	                }
	                
	                if($save_key && $save_cer) {
	                    array_unshift($certificado, ['fk_id_empresa'=> $id]);
	                    $certificado['key'] = $namekey;
	                    $certificado['certificado'] = $namecer;
	                    $certificado['password'] = encrypt($certificado['password']);
	                    $entity->certificados()->updateOrCreate(['id_certificado' => null], $certificado);
	                }
	            }
	        }
	        
	        #DB::commit();
	        
	        # Eliminamos cache
	        Cache::tags(getCacheTag('index'))->flush();
	        
	        
	        $this->log('store', $id);
	        return $this->redirect('store');
	    }
	    else {
	        #DB::rollBack();
	        $this->log('error_store');
	        return $this->redirect('error_store');
	    }
	}
	
	
	public function update(Request $request, $company, $id, $compact = false)
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
	            foreach ($certificados as $certificado) {
	                $save_key = false;
	                $save_cer = false;
	                if(isset($certificado['key-file'])) {
	                    $mykey = $certificado['key-file'];
	                    $namekey = 'key-'.$mykey->getClientOriginalName();
	                    $save_key = Storage::disk('certificados')->put($request->conexion.'/'.$namekey, file_get_contents($mykey->getRealPath()));
	                }
	                if(isset($certificado['cer-file'])) {
	                    $mycer = $certificado['cer-file'];
	                    $namecer = 'cer-'.$mycer->getClientOriginalName();
	                    $save_cer = Storage::disk('certificados')->put($request->conexion.'/'.$namecer, file_get_contents($mycer->getRealPath()));
	                }

	                if($save_key && $save_cer) {
	                    array_unshift($certificado, ['fk_id_empresa'=> $id]);
	                    $certificado['key'] = $namekey;
	                    $certificado['certificado'] = $namecer;
	                    $certificado['password'] = encrypt($certificado['password']);
	                    $entity->certificados()->updateOrCreate(['id_certificado' => null], $certificado);
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
	    }
	    else {
	        #DB::rollBack();
	        $this->log('error_update', $id);
	        return $this->redirect('error_update');
	    }
	}
	
	public function descargar($company, $id, $archivo)
	{
	    $certificado = Certificados::where('id_certificado',$id)->first();
	    $file = Storage::disk('certificados')->getDriver()->getAdapter()->getPathPrefix().$certificado->empresa->conexion.'/'.$certificado->{$archivo};
	    
	    if (File::exists($file))
	    {
	        Logs::createLog($certificado->getTable(), $company, $certificado->id_certificado, 'descargar', "Archivo $archivo");
	        return Response::download($file);
	    }
	    else {
	        App::abort(404,'No se encontro el archivo o recurso que se solicito.');
	    }
	}
	
	public function getDatoscer(Request $request)
	{
	    $req = $request;
	    $pathCer = $req->file('cer')->getRealPath();
	    $pathKey = $req->file('key')->getRealPath();
	    
	    $data = $this->readCer($pathCer) ?? [];
	    $data['cadena_cer'] = $this->FileToPem($pathCer,'cer') ?? '';
	    $data['cadena_key'] = $this->FileToPem($pathKey,'key',$req->pass) ?? '';
	    
	    return $data;
	}
	
	protected function readCer($pathFile = '')
	{
	    if (File::exists($pathFile))
	    {
	        $CerContent =  $this->FileToPem($pathFile);
	        
	        if(!empty($CerContent))
	            $data = openssl_x509_parse($CerContent);
	        else
	            $data = null;
    	    
	        return $data;
	    }
	    else
	        return null;
	}
	
	protected function FileToPem($pathFile = '',$type='cer',$pass='')
	{
	    if (File::exists($pathFile))
	    {
            $PemContent = null;
	        $FileContent = file_get_contents($pathFile);
	        if($type == 'cer')
	           $PemContent =  '-----BEGIN CERTIFICATE-----'.PHP_EOL.chunk_split(base64_encode($FileContent), 64, PHP_EOL).'-----END CERTIFICATE-----'.PHP_EOL;
	        elseif($type == 'key') {
	            $comand = 'openssl pkcs8 -inform DER -in '.$pathFile.' -passin pass:'.$pass.' -out '.$pathFile.'.pem';
	            @exec($comand, $output);
	            
	            if(file_exists($pathFile.'.pem')){
	                $PemContent = file_get_contents($pathFile.'.pem');
	                unlink($pathFile.'.pem');
	            }
	        }
	        return $PemContent;
	    }
	    else
	        return null;
	}
}