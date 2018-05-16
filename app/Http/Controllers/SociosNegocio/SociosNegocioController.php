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
use App\Http\Models\Administracion\Monedas;
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

class SociosNegocioController extends ControllerBase
{
	public function __construct()
	{
	    $this->entity = new SociosNegocio;
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
			'monedas'              => Monedas::where('activo',1)->selectRaw("Concat(moneda,'-',descripcion) as text, id_moneda as id")->pluck('text', 'id')->prepend('...',''),
            'cuentasproveedores'   => CuentasContables::where('activo',1)->where('eliminar',0)->whereRaw("tipo = 'B' or tipo = 'D' or tipo = 'F' or tipo = 'H' or tipo = 'J' or tipo = 'L'")->selectRaw("CONCAT(nombre,' ',cuenta) as cuenta, id_cuenta_contable")->pluck("cuenta",'id_cuenta_contable')->prepend('...',''),
            'cuentasclientes'      => CuentasContables::where('activo',1)->where('eliminar',0)->whereRaw("tipo = 'A' or tipo = 'C' or tipo = 'E' or tipo = 'G' or tipo = 'I' or tipo = 'K'")->selectRaw("CONCAT(nombre,' ',cuenta) as cuenta, id_cuenta_contable")->pluck("cuenta",'id_cuenta_contable')->prepend('...',''),
	        'js_estados'           => Crypt::encryptString('"select": ["estado", "id_estado"], "conditions": [{"where": ["fk_id_pais","$fk_id_pais"]}], "orderBy": [["estado", "ASC"]]'),
	        'js_municipios'        => Crypt::encryptString('"select": ["municipio", "id_municipio"], "conditions": [{"where": ["fk_id_estado","$fk_id_estado"]}], "orderBy": [["municipio", "ASC"]]'),
	    ];
	}
	
	public function store(Request $request, $company, $compact = false)
	{
		$entity = $this->entity->create($request->all());
        $return = parent::store($request, $company,true);
        if(is_array($return) && $entity)
        {
			$id = $entity->id_socio_negocio;
            if($request->empresas_)
            {
                foreach ($request->empresas_ as $empre){
					if($empre['fk_id_empresa'] > 0)
                    	$sync[]=[
							'fk_id_socio_negocio' => $id,
							'fk_id_empresa'=>$empre['fk_id_empresa']
						];
				}
                $empresas_done = $entity->empresas()->sync($sync);
			} 
            if($request->formaspago)
            {
                $sync = [];
                foreach ($request->formaspago as $formapago){
					if($formapago['fk_id_forma_pago'] > 0)
						$sync[]=[
							'fk_id_socio_negocio' => $id,
							'fk_id_forma_pago'=>$formapago['fk_id_forma_pago'],
						];
                }
                $formaspago_done = $entity->formaspago()->sync($sync);
            } 
			if(isset($request->anexos))
			{
	            $anexos = $request->anexos;

	            #Elimina los contactos que existian y que no se encuentran en el arreglo de datos
	            $ids_anexos = collect($anexos)->pluck('id_anexo');
	            $entity->anexos()->whereNotIn('id_anexo', $ids_anexos)->update(['eliminar' => 1]);

	            #Inserta o Actualiza la informacion del contacto
	            foreach ($anexos as $anexo)
	            {
					if(isset($anexo['archivo']))
					{
	                    $myfile = $anexo['archivo'];
	                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
	                    $file_save = Storage::disk('socios_anexos')->put($id.'/'.$filename, file_get_contents($myfile->getRealPath()));

						if($file_save)
						{
        	                array_unshift($anexo, ['fk_id_socio_negocio'=> $id]);
        	                $anexo['archivo'] = $filename;
        	                $entity->anexos()->updateOrCreate(['id_anexo' => null], $anexo);
    	                }
	                }
	            }
			}
			// else
			// {
			// 	$entity->anexos()->update(['eliminar' => 1]);
			// }
			if($empresas_done || $formaspago_done)
			{
				return $return['redirect'];
			}
            
        }
        else
        {
            return $this->redirect('error_store');
        }
	}

    public function update(Request $request, $company, $id, $compact = false)
    {
		$return = parent::update($request, $company, $id, true);
        if(is_array($return))
        {
			$entity = $this->entity->findOrFail($id);
            if($request->empresas_)
            {
                foreach ($request->empresas_ as $empre){
					if($empre['fk_id_empresa'] > 0)
                    	$sync[]=[
							'fk_id_socio_negocio' => $id,
							'fk_id_empresa'=>$empre['fk_id_empresa']
						];
				}
                $empresas_done = $entity->empresas()->sync($sync);
			} 
            if($request->formaspago)
            {
                $sync = [];
                foreach ($request->formaspago as $formapago){
					if($formapago['fk_id_forma_pago'] > 0)
						$sync[]=[
							'fk_id_socio_negocio' => $id,
							'fk_id_forma_pago'=>$formapago['fk_id_forma_pago'],
						];
                }
                $formaspago_done = $entity->formaspago()->sync($sync);
            } 
			if(isset($request->anexos))
			{
	            $anexos = $request->anexos;

	            #Elimina los contactos que existian y que no se encuentran en el arreglo de datos
	            $ids_anexos = collect($anexos)->pluck('id_anexo');
	            $entity->anexos()->whereNotIn('id_anexo', $ids_anexos)->update(['eliminar' => 1]);

	            #Inserta o Actualiza la informacion del contacto
	            foreach ($anexos as $anexo)
	            {
					if(isset($anexo['archivo']))
					{
	                    $myfile = $anexo['archivo'];
	                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
	                    $file_save = Storage::disk('socios_anexos')->put($id.'/'.$filename, file_get_contents($myfile->getRealPath()));

						if($file_save)
						{
        	                array_unshift($anexo, ['fk_id_socio_negocio'=> $id]);
        	                $anexo['archivo'] = $filename;
        	                $entity->anexos()->updateOrCreate(['id_anexo' => null], $anexo);
    	                }
	                }
	            }
			}
			// else
			// {
			// 	$entity->anexos()->update(['eliminar' => 1]);
			// }
			if($empresas_done || $formaspago_done)
			{
				return $return['redirect'];
			}
            
        }
        else
        {
            return $this->redirect('error_store');
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
