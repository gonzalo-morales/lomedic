<?php

namespace App\Http\Controllers\SociosNegocio;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\SociosNegocio\TiposSocioNegocio as TiposSocios;
use App\Http\Models\SociosNegocio\TiposContacto;
use App\Http\Models\SociosNegocio\TiposDireccion;
use App\Http\Models\SociosNegocio\RamosSocioNegocio as Ramos;
use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Finanzas\CuentasContables;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\SociosNegocio\TiposAnexos;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\SociosNegocio\TiposProveedores;
use App\Http\Models\SociosNegocio\AnexosSociosNegocio;
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
use App\Http\Models\RecursosHumanos\Empleados;
#use App\Http\Models\Administracion\Monedas;

class SociosNegocioController extends ControllerBase
{
	public function __construct(SociosNegocio $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
	{
	    $tipo = TiposSocios::where('activo',1)->select('para_venta','tipo_socio','id_tipo_socio')->get();
	    $ejecutivos = Empleados::where('activo',1)->whereIn('fk_id_departamento',[7,19])->selectRaw("id_empleado, CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) as empleado, fk_id_departamento")->get();
	    return [
	        'ramos'                => Ramos::where('activo',1)->pluck('ramo','id_ramo')->sortBy('ramo')->prepend('...',''),
	        'ejecutivos_venta'     => $ejecutivos->where('fk_id_departamento',19)->pluck('empleado','id_empleado')->sortBy('empleado')->prepend('...',''),
	        'ejecutivos_compra'    => $ejecutivos->where('fk_id_departamento',7)->pluck('empleado','id_empleado')->sortBy('empleado')->prepend('...',''),
	        'paises'               => Paises::where('activo',1)->pluck('pais','id_pais')->sortBy('pais')->prepend('...',''),
	        'tiposproveedores'     => TiposProveedores::where('activo',1)->pluck('tipo_proveedor','id_tipo_proveedor')->sortBy('tipo_proveedor')->prepend('...',''),
	        'tipossociosventa'     => $tipo->where('para_venta',1)->pluck('tipo_socio','id_tipo_socio')->sortBy('tipo_socio')->prepend('No es Cliente',''),
	        'tipossocioscompra'    => $tipo->where('para_venta',0)->pluck('tipo_socio','id_tipo_socio')->sortBy('tipo_socio')->prepend('No es Proveedor',''),
	        'tiposanexos'          => TiposAnexos::where('activo',1)->pluck('tipo_anexo','id_tipo_anexo')->sortBy('tipo_anexo')->prepend('...',''),
	        'empresas'		       => Empresas::select('id_empresa','nombre_comercial')->where('activo',1)->where('empresa',1)->get()->sortBy('nombre_comercial'),
	        'condicionpago'        => CondicionesPago::where('activo',1)->pluck('condicion_pago','id_condicion_pago')->sortBy('condicion_pago')->prepend('...',''),
	        'formaspago'           => FormasPago::where('activo',1)->selectRaw("concat(forma_pago,' - ',descripcion) as forma_pago, id_forma_pago")->pluck('forma_pago','id_forma_pago')->sortBy('forma_pago'),
	        'bancos'               => Bancos::where('activo',1)->pluck('banco','id_banco')->sortBy('banco')->prepend('...',''),
	        'sucursales' 	       => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal')->sortBy('sucursal')->prepend('...',''),
	        'tiposcontactos'       => TiposContacto::where('activo',1)->pluck('tipo_contacto','id_tipo_contacto')->sortBy('tipo_contacto')->prepend('...',''),
	        'tiposdireccion'       => TiposDireccion::where('activo',1)->pluck('tipo_direccion','id_tipo_direccion')->sortBy('tipo_direccion'),
	        'skus'                 => Productos::where('activo',1)->pluck('sku','id_sku')->sortBy('sku')->prepend('...',''),
            'cuentasproveedores'   => CuentasContables::where('activo',1)->where('eliminar',0)->whereRaw("tipo = 'B' or tipo = 'D' or tipo = 'F' or tipo = 'H' or tipo = 'J' or tipo = 'L'")->selectRaw("CONCAT(nombre,' ',cuenta) as cuenta, id_cuenta_contable")->pluck("cuenta",'id_cuenta_contable')->prepend('...',''),
            'cuentasclientes'      => CuentasContables::where('activo',1)->where('eliminar',0)->whereRaw("tipo = 'A' or tipo = 'C' or tipo = 'E' or tipo = 'G' or tipo = 'I' or tipo = 'K'")->selectRaw("CONCAT(nombre,' ',cuenta) as cuenta, id_cuenta_contable")->pluck("cuenta",'id_cuenta_contable')->prepend('...',''),
	        'js_estados'           => Crypt::encryptString('"select": ["estado", "id_estado"], "conditions": [{"where": ["fk_id_pais","$fk_id_pais"]}], "orderBy": [["estado", "ASC"]]'),
	        'js_municipios'        => Crypt::encryptString('"select": ["municipio", "id_municipio"], "conditions": [{"where": ["fk_id_estado","$fk_id_estado"]}], "orderBy": [["municipio", "ASC"]]'),
	        'js_upcs'              => Crypt::encryptString('"select": ["upc", "id_upc"], "conditions": [{"where": ["activo","1"]}], "whereHas": [{"skus":{"where":["fk_id_sku", "$fk_id_sku"]}}], "orderBy": [["upc", "ASC"]]'),
	        'js_sku'               => Crypt::encryptString('"select": ["descripcion_corta as descripcion", "id_sku"], "conditions": [{"where": ["id_sku","$id_sku"]}], "limit": 1'),
	        'js_upc'               => Crypt::encryptString('"select": ["descripcion", "id_upc"], "conditions": [{"where": ["id_upc","$id_upc"]}], "limit": 1'),
	    ];
	}

	public function store(Request $request, $company, $compact = false)
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
	                    $file_save = Storage::disk('socios_anexos')->put($id.'/'.$filename, file_get_contents($myfile->getRealPath()));

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
	        #$this->log('store', $id);
	        return $this->redirect('store');
	    } else {
	        DB::rollBack();
	        #$this->log('error_store');
	        return $this->redirect('error_store');
	    }
	}

	public function update(Request $request, $company, $id, $compact = false)
	{
	    # ¿Usuario tiene permiso para actualizar?
	    #$this->authorize('update', $this->entity);

	    # Validamos request, si falla regresamos atras
	    $this->validate($request, $this->entity->rules);

	    #DB::beginTransaction();
	    $entity = $this->entity->findOrFail($id);
	    $entity->fill($request->all());

	    if ($entity->save())
	    {
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

	            #Elimina los contactos que existian y que no se encuentran en el arreglo de datos
	            $ids_contactos = $contactos->pluck('id_contacto');
	            $entity->contactos()->whereNotIn('id_contacto', $ids_contactos)->update(['eliminar' => 1]);

	            #Insertar o Actualizar la informacion de los contactos
	            foreach ($contactos as $contacto)
	            {
	                array_unshift($contacto, ['fk_id_socio_negocio'=> $id]);
	                $entity->contactos()->updateOrCreate(['id_contacto' => ($contacto['id_contacto'] ?? null)], $contacto);
	            }
	        }
	        else {
	            $entity->contactos()->where('fk_id_socio_negocio', $id)->update(['eliminar' => 1]);
	        }

	        # Guardamos el detalle de las direcciones de los socios de negocio
	        if(isset($request->direcciones)){
	            $direcciones = collect($request->direcciones);

	            #Elimina los contactos que existian y que no se encuentran en el arreglo de datos
	            $ids_direcciones = $direcciones->pluck('id_direccion');
	            $entity->direcciones()->whereNotIn('id_direccion', $ids_direcciones)->update(['eliminar' => 1]);

	            #Inserta o Actualiza la informacion del contacto
	            foreach ($direcciones as $direccion)
	            {
	                array_unshift($direccion, ['fk_id_socio_negocio'=> $id]);
	                $entity->direcciones()->updateOrCreate(['id_direccion' => ($direccion['id_direccion'] ?? null)], $direccion);
	            }
	        }
	        else {
	            $entity->direcciones()->where('fk_id_socio_negocio', $id)->update(['eliminar' => 1]);
	        }

	        # Guardamos el detalle de las cuentas bancarias de los socios de negocio
	        if(isset($request->cuentas)){
	            $cuentas = collect($request->cuentas);

	            #Elimina los contactos que existian y que no se encuentran en el arreglo de datos
	            $ids_cuentas = $cuentas->pluck('id_cuenta');
	            $entity->cuentas()->whereNotIn('id_cuenta', $ids_cuentas)->update(['eliminar' => 1]);

	            #Inserta o Actualiza la informacion del contacto
	            foreach ($cuentas as $cuenta)
	            {
	                array_unshift($cuenta, ['fk_id_socio_negocio'=> $id]);
	                $entity->cuentas()->updateOrCreate(['id_cuenta' => ($cuenta['id_cuenta'] ?? null)], $cuenta);
	            }
	        }
	        else {
	            $entity->cuentas()->where('fk_id_socio_negocio', $id)->update(['eliminar' => 1]);
	        }

	        # Guardamos el detalle de los anexos de los socios de negocio
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
	                    $file_save = Storage::disk('socios_anexos')->put($id.'/'.$filename, file_get_contents($myfile->getRealPath()));

    	                if($file_save) {
        	                array_unshift($anexo, ['fk_id_socio_negocio'=> $id]);
        	                $anexo['archivo'] = $filename;
        	                $entity->anexos()->updateOrCreate(['id_anexo' => null], $anexo);
    	                }
	                }
	            }
	        }
	        else {
	            $entity->anexos()->update(['eliminar' => 1]);
	        }
	        # Guardamos el detalle de los productos de los socios de negocio
	        if(isset($request->productos) && isset($request->productos['fk_id_sku'])){
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

	        #DB::commit();

	        # Eliminamos cache
	        Cache::tags(getCacheTag('index'))->flush();
	        #$this->log('update', $id);
	        return $this->redirect('update');
	    } else {
	        #DB::rollBack();
	        #$this->log('error_update', $id);
	        return $this->redirect('error_update');
	    }
	}

	public function descargar($company, $id)
	{
	    $archivo = AnexosSociosNegocio::where('id_anexo',$id)->first();
	    $file = Storage::disk('socios_anexos')->getDriver()->getAdapter()->getPathPrefix().$archivo->fk_id_socio_negocio.'/'.$archivo->archivo;
	    if (File::exists($file))
	    {
	        Logs::createLog($archivo->getTable(), $company, $archivo->id_anexo, 'descargar', 'Archivo anexo de socio negocio');
	        return Response::download($file);
	    }
	    else {
	        App::abort(404,'No se encontro el archivo o recurso que se solicito.');
	    }
	}
}
