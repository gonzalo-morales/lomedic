<?php

namespace App\Http\Controllers\SociosNegocio;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\SociosNegocio\TiposSocioNegocio as TiposSocios;
use App\Http\Models\SociosNegocio\TiposContacto;
use App\Http\Models\SociosNegocio\TiposDireccion;
use App\Http\Models\SociosNegocio\TiposEntrega;
use App\Http\Models\SociosNegocio\RamosSocioNegocio as Ramos;
use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Municipios;
use App\Http\Models\Administracion\Sucursales;
#use App\Http\Models\Administracion\Monedas;
use Illuminate\Http\Request;
use App\Http\Models\Administracion\Usuarios;
use Illuminate\Support\Facades\Crypt;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\SociosNegocio\TiposAnexos;

class SociosNegocioController extends ControllerBase
{
	public function __construct(SociosNegocio $entity)
	{
		$this->entity = $entity;
	}
	
	public function getDataView($entity = null)
	{
	    $tipo = TiposSocios::where('activo','1')->where('eliminar','0')->select('para_venta','tipo_socio','id_tipo_socio')->get();
	    
	    return [
	        'ramos'                => Ramos::where('activo','1')->where('eliminar','0')->pluck('ramo','id_ramo')->sortBy('ramo')->prepend('Selecciona una opcion...',''),
	        'ejecutivos'           => Usuarios::where('activo','1')->where('eliminar','0')->pluck('nombre_corto','id_usuario')->sortBy('nombre_corto')->prepend('Selecciona una opcion...',''),
	        'paises'               => Paises::where('activo','1')->where('eliminar','0')->pluck('pais','id_pais')->sortBy('pais')->prepend('Selecciona una opcion...',''),
	        'tipossociosventa'     => $tipo->where('para_venta','1')->pluck('tipo_socio','id_tipo_socio')->sortBy('tipo_socio')->prepend('No es Cliente',''),
	        'tipossocioscompra'    => $tipo->where('para_venta','0')->pluck('tipo_socio','id_tipo_socio')->sortBy('tipo_socio')->prepend('No es Proveedor',''),
	        'tiposanexos'          => TiposAnexos::where('activo','1')->where('eliminar','0')->pluck('tipo_anexo','id_tipo_anexo')->sortBy('tipo_anexo')->prepend('Selecciona una opcion...',''),
	        'empresas'		       => Empresas::select('id_empresa','nombre_comercial')->where('activo',1)->where('empresa',1)->get()->sortBy('nombre_comercial'),
	        'condicionpago'        => CondicionesPago::where('activo','1')->where('eliminar','0')->pluck('condicion_pago','id_condicion_pago')->sortBy('condicion_pago')->prepend('Selecciona una opcion...',''),
	        'formaspago'           => FormasPago::where('activo','1')->where('eliminar','0')->selectRaw("concat(forma_pago,' - ',descripcion) as forma_pago, id_forma_pago")->pluck('forma_pago','id_forma_pago')->sortBy('forma_pago'),
	        'bancos'               => Bancos::where('eliminar','0')->pluck('banco','id_banco')->sortBy('banco')->prepend('Selecciona una opcion...',''),
	        'tiposentrega'	       => TiposEntrega::where('activo','1')->where('eliminar','0')->pluck('tipo_entrega','id_tipo_entrega')->sortBy('tipo_entrega'),
	        'sucursales' 	       => Sucursales::where('activo','1')->where('eliminar','0')->pluck('sucursal','id_sucursal')->sortBy('sucursal')->prepend('Selecciona una opcion...',''),
	        'tiposcontactos'       => TiposContacto::where('activo','1')->where('eliminar','0')->pluck('tipo_contacto','id_tipo_contacto')->sortBy('tipo_contacto')->prepend('Selecciona una opcion...',''),
	        'tiposdireccion'       => TiposDireccion::where('activo','1')->where('eliminar','0')->pluck('tipo_direccion','id_tipo_direccion')->sortBy('tipo_direccion'),
	    ];
	}
	
	public function store(Request $request, $company)
	{
	    #Validamos request, si falla regresamos atras
	    $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);
	    
		$objSocio = $request->input('objectSocio');
		$validArray = [];

		// ***************************************************************
		// ***************************************************************
		if ($objSocio != null) {
			$objSocio = json_decode($objSocio);
			// dd($objSocio);
			if(empty($objSocio->activo)){
				$validArray['activo'] = '';
			}else {
				$validArray['activo'] = $objSocio->activo;
			}
			if(empty($objSocio->activo)){
				$validArray['razon_social'] = '';
			}else {
				$validArray['razon_social'] = $objSocio->razon_social;
			}

			// if (count($objSocio->tipo_socio) > 0) {
			// 	foreach ($objSocio->tipo_socio as $key => $value) {
			// 		// echo $objSocio->tipo_socio[$key];
			// 	}
			// }
			// echo $objSocio->rfc;
			// echo $objSocio->nombre_comercial;
			// echo $objSocio->telefono;
			// echo $objSocio->sitio_web;

			foreach ($objSocio->empresas as $key => $value) {
				if ($objSocio->empresas[$key]->checked == true) {
					// echo $objSocio->empresas[$key]->id;
				}
			}
			// echo $objSocio->condiciones_pago->monto_credito;
			if (count($objSocio->condiciones_pago->cuentas) > 0) {
				foreach ($objSocio->condiciones_pago->cuentas as $key => $value) {
					// echo $objSocio->condiciones_pago->cuentas[$key]->banco;
					// echo $objSocio->condiciones_pago->cuentas[$key]->estatus;
					// echo $objSocio->condiciones_pago->cuentas[$key]->no_cuenta;
					// echo $objSocio->condiciones_pago->cuentas[$key]->indexBanco;
				}
			}

		}
		// return dd($this->entity);
		// if ($this->validate($request->input('objectSocio'), $this->entity->rules) ){
		// 	echo $this->validate($objSocio, $this->entity->rules);
		// }else {
		// 	echo $this->validate($objSocio, $this->entity->rules);
		// }


		// #################################################################>
		// ########## Validar los campos del arreglo "objectSocio" #########>
		// #################################################################>
		$validator = Validator::make(
			json_decode($request->input('objectSocio'),true),
			[
				// 'activo' => 'required',
	            'razon_social' => 'required|min:10',
	            'rfc' => 'required',
	            'nombre_comercial' => 'required',
	            'ejecutivo_venta' => 'required',
				'telefono' => 'required',
				'sitio_web' => 'required',
				'ramo' => 'required',
				'pais_origen' => 'required',
				'tipo_socio' => 'required',
				'moneda' => 'required',
				'empresas' => 'required',
				'condiciones_pago.monto_credito' => 'required',
				'condiciones_pago.dias_credito' => 'required',
				'condiciones_pago.forma_pago' => 'required',
				'condiciones_pago.cuentas' => 'required',
				'info_entrega.tipos_entrega' => 'required',
				'info_entrega.sucursal' => "required_if:info_entrega.tipos_entrega,==,1", // id=> 1 : para "sucursal"
				'info_entrega.monto_minimo_facturacion' => 'required|numeric|min:1',
				'info_entrega.correos' => 'required',
				'info_entrega.correos.*.correo' => 'email',
        	],
			[
	    		'required' 		=> 'Este campo :attribute es requerido.',
	    		'required_if' 	=> 'Este campo :attribute es requerido si selecciona sucursal.',
				'numeric|min:1' => 'Valor numérico requerido con al menos 1',
	    		'email' 		=> 'Este campo :attribute debe ser correo electronico.',
	    		'min' 			=> 'Este campo :attribute debe tener al menos :min caracter(es).',
			]
		);

		if ($validator->passes()) {
			$isSuccess = $this->entity->create([
				'activo'						=> $objSocio->activo,
				'razon_social' 					=> $objSocio->razon_social,
				'rfc' 							=> $objSocio->rfc,
				'ejecutivo_venta'				=> $objSocio->ejecutivo_venta,
				'nombre_comercial'					=> $objSocio->nombre_comercial,
				'telefono'						=> $objSocio->telefono,
				'sitio_web'						=> $objSocio->sitio_web,
				'fk_id_ramo'					=> $objSocio->ramo,
				'fk_id_pais_origen'				=> $objSocio->pais_origen,
				'fk_id_moneda'					=> $objSocio->moneda,
				'monto_minimo_facturacion'		=> $objSocio->info_entrega->monto_minimo_facturacion,
				'fk_id_tipo_entrega'			=> $objSocio->info_entrega->tipos_entrega,
				'dias_credito'					=> $objSocio->condiciones_pago->dias_credito,
				'monto_credito'					=> $objSocio->condiciones_pago->monto_credito,
				'fk_id_forma_pago'				=> $objSocio->condiciones_pago->forma_pago,
				'fecha_modificacion'			=> \Carbon\Carbon::now(),
				'fk_id_usuario_modificacion'	=> Auth::id(),
				// 'fk_id_tipo_entrega'	=> 1,
				// 'fk_id_sucursal_entrega'=> 5,
			]);

			$id = $isSuccess->id_socio_negocio;
			return response()->json(['success'=>'ALL OK','idInserted'=>$id]);
        }
    	return response()->json(['error'=>$validator->errors()->all()]);

		// ***************************************************************
		// ***************************************************************
		// return Response::json($objectSocio);

		// $objectSocio =  json_encode($request->objectSocio);
		// echo $objectSocio['activo'];
		if(Input::hasFile('fsanitaria')){
			$files = Input::file('fsanitaria');
		// 	echo Storage::disk('licencias')->putFile("", $file);
			$arrayFiles = [];
			$i=0; $routeStorageFile = '';
			foreach($files as $file) {
		        $arrayFiles[$i]['name'] = $file->getClientOriginalName();
				$arrayFiles[$i]['extension'] = $file->getClientOriginalExtension();
				$arrayFiles[$i]['size'] = $file->getClientSize();
				$arrayFiles[$i]['type'] = $file->getClientMimeType();
				$arrayFiles[$i]['routeStorageFile'] = $arrayFiles[$i]['pathDisk'] = Storage::disk('licencias')->putFile("", $file);
				$i++;
			}
			// echo json_encode($files,null,2);
			return Response::json($arrayFiles);
		}


		// **********************************************************
		// **********************************************************

		/*
		$file = Input::file('avisoFuncionamiento');
		if(Input::hasFile('avisoFuncionamiento')){
			$sftp = new SFTP('192.168.0.11',22);
			if (!$sftp->login('admin', 'tHL3Vm_o')) {
			    exit('Login Failed');
			}
			$filename = uniqid().".".$request->file('avisoFuncionamiento')->getClientOriginalExtension();
			$sftp->chdir("admin");
			$sftp->put($filename,$file,1);

		}else {
			// return dd($file);
			return "__No has File____________<>";
		}
		$this->authorize('create', $this->entity);

		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules);

		$isSuccess = $this->entity->create([
			'nombre' => $request->nombre,
			'rfc' => $request->rfc,
			'activo' => $request->estatus,
		]);
		// return dd($isSuccess);
		if ($isSuccess) {
			// $this->log('store', $isSuccess->id_proveedor);
			return $this->redirect('store');
		} else {
			// $this->log('error_store');
			return $this->redirect('error_store');
		}
		*/
		// if($created){
		// 	Logs::createLog($this->entity->getTable(),$company,$created->id_metodos_pago,'crear','Registro insertado');
		// }else{
		// 	Logs::createLog($this->entity->getTable(),$company,null,'crear','Error al insertar');
		// }

		# Redirigimos a index
		// return redirect(companyRoute('create'));
	}

	/*
	public function show($company, $id, $attributes =[])
	{
		// Logs::createLog($this->entity->getTable(),$company,$id,'ver',null);

		return view (Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
			'data' => $this->entity->findOrFail($id),
		]);
	}
    */
	
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	// public function edit($company, $id)
	// {
	// 	return view (Route::currentRouteName(), [
	// 		'entity' => $this->entity_name,
	// 		'company' => $company,
	// 		'data' => $this->entity->findOrFail($id),
	// 	]);
	// }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  integer	$id
	 * @return \Illuminate\Http\Response
	 *
	 */
    public function update(Request $request, $company, $id)
    {
        dd($request->all());
        
        $this->validate($request, $this->entity->rules);
        
        if($request->activo == 'on'){
        	$request->activo=true;
        }
        
        $entity = $this->entity->findOrFail($id);
        $entity->fill([
        	'descripcion' 	=> $request->descripcion,
        	'estatus'     	=> $request->estatus,
        	'metodo_pago' 	=> $request->metodo_pago,
        	'activo'  		=> $request->activo,
        ]);
        if($entity->save()){
        	Logs::createLog($this->entity->getTable(),$company,$id,'editar','Registro actualizado');
        }else{
        	Logs::createLog($this->entity->getTable(),$company,$id,'editar','Error al editar');
        }
    
        return redirect(companyRoute('index'));
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  integer 	$id
	 * @return \Illuminate\Http\Response
	 */
	// public function destroy($company, $id)
	// {
	// 	$entity 			= $this->entity->findOrFail($id);
	// 	$entity->eliminar 	= 't';
	// 	if($entity->save()){
	// 		Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Registro eliminado');
	// 	}else{
	// 		Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Error al eliminar');
	// 	}
	//
	// 	return redirect(companyRoute('index'));
	// }


	// public function eliminarContacto($company, $id){
	// 	$contacto = Contactos::find($id)->where('activo','1')->where('id',$id)->first();
	// 	$contacto->eliminar 	= 't';
	// 	if($contacto->save()){
	// 		Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Registro eliminado');
	// 	}else{
	// 		Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Error al eliminar');
	// 	}
	// 	$msg = array("msg" => array("errorCode"=>200,"errorMsg"=>"OK"));
    //     return Response::json($msg);
	//
	// }
	// public function crearContacto($company, Request $request){
	//
	// 	// $this->validate($request, $this->entity->rules);
	// 	// return $request->tipoContacto;
	// 	$contactos = new Contactos();
	//
	// 	$created = Contactos::create([
	// 		'tipo_contacto' 	=> $request->tipoContacto,
	// 		'nombre'			=> $request->nombreContacto,
	// 		'telefono_oficina'	=> $request->telContacto,
	// 		'extension_oficina'	=> $request->extContacto,
	// 		'activo'			=> $request->activo,
	// 	]);
	// 	if($created){
	// 		Logs::createLog($contactos->getTable(),$company,$created->id,'crear','Registro insertado');
	// 		$response = array("code"=>200,"msg"=>"OK");
	// 	}else{
	// 		Logs::createLog($contactos->getTable(),$company,null,'crear','Error al insertar');
	// 		$response = array("code"=>200,"msg"=>"BAD");
	// 	}
	//
    //     return Response::json($response);
	//
	// }

	public function getEstados($company, $id){
			$estados = Estados::select('id_estado','estado')->where('fk_id_pais',$id)->where('activo','1')->where('eliminar','0')->orderBy('estado')->get();
			if (count($estados) == 0) {
				return Response::json(array(
											'msg'=>'No se encontraron estados para el país seleccionado',
											'cantidad'=>0)
											);
			}
        return Response::json($estados->toArray());
	}

	public function getMunicipios($company, $id){
		$municipios = Municipios::select('id_municipio','municipio')->where('fk_id_estado',$id)->where('activo','1')->where('eliminar','0')->orderBy('municipio')->get();
		if (count($municipios) == 0) {
			return Response::json(array(
										'msg'=>'No se encontraron municipios para el estado seleccionado',
										'cantidad'=>0)
										);
		}
        return Response::json($municipios->toArray());
	}

	public function uploadLicencias($company,Request $request){

		// $this->validate($request, [
		// 	'files' => 'required|size:1024|mimes:txt',
		// ]);

		// dd($request);

		// return "OK";
		// print_r($request->all()); exit(0);
		if(Input::hasFile('files')){
			$files=Input::file('files');
			// print_r($files); exit(0);
			$files2 = $request->file('files');
			$arrayFiles = [];
			$i=0; $routeStorageFile = '';
			foreach($files as $file) {
		        $arrayFiles[$i]['name'] = $file->getClientOriginalName();
				$arrayFiles[$i]['extension'] = $file->getClientOriginalExtension();
				$arrayFiles[$i]['size'] = $file->getClientSize();
				$arrayFiles[$i]['type'] = $file->getClientMimeType();
				// $destinationPath = storage_path('app/licencias');
				// $file->move($destinationPath, $arrayFiles[$i]['name']);
				// $arrayFiles[$i]['pathDisk'] = Storage::disk('licencias')->put($arrayFiles[$i]['name'], file_get_contents($file));
				// $arrayFiles[$i]['pathDisk'] = Storage::disk('licencias')->put(md5(microtime()).".".$arrayFiles[$i]['extension'], file_get_contents($file));
				$arrayFiles[$i]['routeStorageFile'] = $arrayFiles[$i]['pathDisk'] = Storage::disk('licencias')->putFile("", $file);
				// $arrayFiles[$i]['pathDisk'] = Storage::disk('licencias')->put('licencias',$file);
				$i++;
			}
			# return back()->with('success','Image Upload successful');
			return Response::json($arrayFiles);
		}

			// return Storage::disk('licencias')->exists("FZGabDmq2MjQF0Ull0eYQ48C9D3RJzam4MYzv8y7.png");
			// $files=Input::file('files');
			// return Storage::disk('licencias')->putFile("",$files[0]);
			// echo Storage::disk('licencias')->getVisibility("FZGabDmq2MjQF0Ull0eYQ48C9D3RJzam4MYzv8y7.png");
			// echo Storage::disk('licencias')->delete("FZGabDmq2MjQF0Ull0eYQ48C9D3RJzam4MYzv8y7.png");

	}
	public function getData(){
		$content=file_get_contents("https://jsonplaceholder.typicode.com/photos");
		$data=json_decode($content);
		$dataSelect2 = [];
		$limit = 0;
		foreach ($data as $key => $value) {
			$dataSelect2[] = ['title'=> $value->id , 'label'=>  $value->title];
			if ($limit == 300) {
				break;
			}
			$limit++;
		}
        return Response::json($dataSelect2);
	}

	/*public function deleteLicencias($company,Request $request){
		if(Input::hasFile('files')){
			$files=Input::file('files');
			$files2 = $request->file('files');
			$arrayFiles = [];
			$i=0; $routeStorageFile = '';
			foreach($files as $file) {
		        $arrayFiles[$i]['name'] = $file->getClientOriginalName();
				$arrayFiles[$i]['extension'] = $file->getClientOriginalExtension();
				$arrayFiles[$i]['size'] = $file->getClientSize();
				$arrayFiles[$i]['type'] = $file->getClientMimeType();
				$arrayFiles[$i]['routeStorageFile'] = $arrayFiles[$i]['pathDisk'] = Storage::disk('licencias')->putFile("", $file);
				$i++;
			}
			return Response::json($arrayFiles);
		}
	}*/

	/* ALMACENAR EN EL SERVIDOR
	 public function store(Request $request, $company)
	 {
		 # Validamos request, si falla regresamos pagina
		 // $this->validate($request, [
		 // 	'avisoFuncionamiento' => 'required|size:10240|mimes:pdf',
		 // 	'razonSocial' => 'required| min:10',
		 // ]);
		 $file = Input::file('avisoFuncionamiento');
		 if(Input::hasFile('avisoFuncionamiento')){
			 $sftp = new SFTP('192.168.0.11',22);
			 if (!$sftp->login('admin', 'tHL3Vm_o')) {
				 exit('Login Failed');
			 }
			 $filename = uniqid().".".$request->file('avisoFuncionamiento')->getClientOriginalExtension();
			 $sftp->chdir("admin");
			 $sftp->put($filename,$file,1);

		 }else {
			 // return dd($file);
			 return "__No has File____________<>";
		 }
		 $this->authorize('create', $this->entity);

		 # Validamos request, si falla regresamos pagina
		 $this->validate($request, $this->entity->rules);

		 $isSuccess = $this->entity->create([
			 'nombre' => $request->nombre,
			 'rfc' => $request->rfc,
			 'activo' => $request->estatus,
		 ]);
		 // return dd($isSuccess);
		 if ($isSuccess) {
			 // $this->log('store', $isSuccess->id_proveedor);
			 return $this->redirect('store');
		 } else {
			 // $this->log('error_store');
			 return $this->redirect('error_store');
		 }
		 // if($created){
		 // 	Logs::createLog($this->entity->getTable(),$company,$created->id_metodos_pago,'crear','Registro insertado');
		 // }else{
		 // 	Logs::createLog($this->entity->getTable(),$company,null,'crear','Error al insertar');
		 // }

		 # Redirigimos a index
		 return redirect(companyRoute('create'));
	 }
	 */
}