@section('content-width', 's12')
@section('header-bottom')
	@parent
	@if(!Route::currentRouteNamed(currentRouteName('index')))
    	{{ HTML::script(asset('vendor/multiselect/js/bootstrap-multiselect.js')) }}
    	{{ HTML::script(asset('vendor/vanilla-datatables/vanilla-dataTables.js')) }}
    	<script type="text/javascript">
        	var estados_js = '{{ $js_estados ?? '' }}';
        	var municipios_js = '{{ $js_municipios ?? '' }}';
        	var upcs_js = '{{ $js_upcs ?? '' }}';
        	var upc_js = '{{ $js_upc ?? '' }}';
        	var sku_js = '{{ $js_sku ?? '' }}';
        </script>
    	{{ HTML::script(asset('js/sociosnegocios/socios.js')) }}
    @endif
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	<div class="row my-3">
		<!-- Campos generales -->
		<div class="col-sm-12 col-md-7 col-lg-9">
			<div class="row">
				<div class="form-group col-md-6 col-lg-4">
					{{ Form::cSelect('Tipo socio para venta', 'fk_id_tipo_socio_venta', $tipossociosventa ?? []) }}
				</div>
				<div class="form-group col-md-6 col-lg-4">
					{{ Form::cSelect('Tipo socio para compra', 'fk_id_tipo_socio_compra', $tipossocioscompra ?? []) }}
				</div>
				<div class="form-group col-md-6 col-lg-4">
					{{ Form::cSelect('Tipo Proveedor', 'fk_id_tipo_proveedor', $tiposproveedores ?? []) }}
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12 col-lg-8">
					{{ Form::cText('* Razón social', 'razon_social') }}
				</div>
				<div class="form-group col-md-12 col-lg-4">
					{{ Form::cText('* RFC', 'rfc') }}
				</div>
				<div class="form-group col-md-12 col-lg-8">
					{{ Form::cText('Nombre Comercial', 'nombre_comercial') }}
				</div>
				<div class="form-group col-md-12 col-lg-4">
					{{ Form::cSelect('Ramo', 'fk_id_ramo', $ramos ?? []) }}
				</div>
			</div>
		</div> <!-- Fin campos generales -->
		
		<!-- Empresas -->
		@if(isset($empresas))
		<div class="col-sm-12 col-md-5 col-lg-3">
			<div class="card z-depth-1-half">
				<div class="card-header">
					<h5>Empresas</h5>
				</div>
				<div class="card-body">
					<ul class="list-group">
                        @foreach ($empresas as $row)
                        <li class="list-group-item form-group row">
                        	{{ Form::cCheckbox($row->nombre_comercial, 'empresas['.$row->id_empresa.']',['class'=>'socio-empresa']) }}
                        </li>
                        @endforeach
                    </ul>
				</div>
			</div>
		</div>
		@endif <!-- Fin Empresas -->
	</div>

	<!-- Card -->
	<div class="row mb-3 card z-depth-1-half">
		<div class="card-header mb-0 pb-0">
			<ul class="nav nav-pills nav-justified">
				<li class="nav-item"><a class="nav-link active"  role="tab" data-toggle="tab"  href="#general">General</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#contactos">Personas contacto</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#direcciones">Direcciones</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#condicionpago">Condiciones pago</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#cuentas">Cuentas</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#anexos">Anexos</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#productos">Productos/Artículos</a></li>
			</ul>
		</div>
		<div class="card-body tab-content">
			<div  class="tab-pane active" id="general" role="tabpanel">
				<div class="row">
    				<div class="form-group col-md-6 col-lg-4">
    					{{ Form::cText('Teléfono', 'telefono') }}
    				</div>
    				<div class="form-group col-md-6 col-lg-4">
    					{{ Form::cText('Sitio web', 'sitio_web') }}
    				</div>
    				<div class="form-group col-md-6 col-lg-4">
    					{{ Form::cSelect('País de Origen', 'fk_id_pais_origen', $paises ?? []) }}
    				</div>
    				
    				<div class="form-group col-md-6 col-lg-4">
						{{ Form::cSelect('Ejecutivo de venta', 'fk_id_ejecutivo_venta', $ejecutivos ?? []) }}
    				</div>
    				
    				<div class="form-group col-md-6 col-lg-4">
    					{{ Form::cSelect('Ejecutivo de compra', 'fk_id_ejecutivo_compra', $ejecutivos ?? []) }}
    				</div>
    			
    				<div class="row col-md-12 col-lg-8 col-xl-4">
                		<div class="form-group col-sm-12 col-md-4 col-lg-3">
                			{{ Form::cCheckboxBtn('Estatus', 'Activo', 'activo', $data['activo'] ?? null, 'Inactivo') }}
                    	</div>
                    	<div class="form-group col-sm-6 col-md-4 col-lg-5">
                			{{ Form::cText('Desde', 'activo_desde',['readonly'=>true]) }}
                    	</div>
                    	<div class="form-group col-sm-6 col-md-4 col-lg-4">
                			{{ Form::cText('Hasta', 'activo_hasta',['readonly'=>true]) }}
                    	</div>
    				</div>
    			</div>
			</div><!--/aquí termina el contenido de un tab-->
			
			<div id="contactos" class="tab-pane" role="tabpanel">
				<div class="row card">
					<div class="card-header">
						<div class="row">
							<div class="form-group col-sm-6 col-md-4">
								{{ Form::cSelect('* Tipo de contacto', 'tipo_contacto', $tiposcontactos ?? []) }}
							</div>
							<div class="form-group col-sm-6- col-md-4">
								{{ Form::cText('* Nombre', 'nombre_contacto') }}
							</div>
							<div class="form-group col-sm-6 col-md-4">
								{{ Form::cText('* Puesto/Departamento', 'puesto') }}
							</div>
							<div class="form-group col-sm-6 col-md-4">
								{{ Form::cText('* Correo', 'correo') }}
							</div>
							<div class="form-group col-sm-4 col-md-3">
								{{ Form::cNumber('Celular', 'celular') }}
							</div>
							<div class="form-group col-sm-4- col-md-3">
								{{ Form::cNumber('Teléfono', 'telefono_oficina') }}
							</div>
							<div class="form-group col-sm-4 col-md-2">
								{{ Form::cNumber('Extencion', 'extension_oficina') }}
							</div>
						</div>
						<div class="form-group col-md-12 my-3">
							<div class="sep sepBtn">
        						<button id="agregar-contacto" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
        					</div>
						</div>
					</div>
					<div class="card-body">
						<table class="table responsive-table highlight" id="tContactos">
							<thead>
								<tr>
									<th>Tipo contacto</th>
									<th>Nombre</th>
									<th>Puesto</th>
									<th>Correo</th>
									<th>Celular</th>
									<th>Teléfono oficina - Ext</th>
									<th>Acción</th>
								</tr>
							</thead>
							<tbody>
							@if(isset($data->contactos)) 
    							@foreach($data->contactos as $key=>$detalle)
								<tr>
									<td>
										{!! Form::hidden('contactos['.$key.'][id_contacto]',$detalle->id_contacto,['class'=>'id_contacto']) !!}
										{{$detalle->tipocontacto->tipo_contacto}}
										{!! Form::hidden('contactos['.$key.'][fk_id_tipo_contacto]',$detalle->fk_id_tipo_contacto,['class'=>'fk_id_tipo_contacto']) !!}
									</td>
									<td>
										{{$detalle->nombre}}
										{!! Form::hidden('contactos['.$key.'][nombre]',$detalle->nombre) !!} 
									</td>
									<td>
										{{$detalle->puesto}}
										{!! Form::hidden('contactos['.$key.'][puesto]',$detalle->puesto) !!} 
									</td>
									<td>
										{{$detalle->correo}}
										{!! Form::hidden('contactos['.$key.'][correo]',$detalle->correo) !!} 
									</td>
									<td>
										{{$detalle->celular}}
										{!! Form::hidden('contactos['.$key.'][celular]',$detalle->celular) !!} 
									</td>
									<td>
										{{$detalle->telefono_oficina.' '.$detalle->extension_oficina}}
										{!! Form::hidden('contactos['.$key.'][telefono_oficina]',$detalle->telefono_oficina) !!}
										{!! Form::hidden('contactos['.$key.'][extension_oficina]',$detalle->extension_oficina) !!}
									</td>
									<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarContactos(this)"> <i class="material-icons">delete</i></button></td>
								</tr>
    							@endforeach
    						@endif
							</tbody>
						</table>
					</div>
				</div><!--/Here ends card-->
    		</div><!--/aquí termina el contenido de un tab-->
    		
    		<div id="direcciones" class="tab-pane" role="tabpanel">
    			<div class="card">
    				<div class="card-header">
    					<div class="row">
    						<div class="form-group col-sm-3">
    							{{ Form::cSelect('Tipo de dirección','tipo_direccion',$tiposdireccion ?? []) }}
    						</div>
							<div class="form-group col-sm-12 col-md-3">
								{{ Form::cText('* Calle', 'calle') }}
							</div>
							<div class="form-group col-sm-2 ">
								{{ Form::cText('No. Exterior', 'num_exterior') }}
							</div>
							<div class="form-group col-sm-2 ">
								{{ Form::cText('No. Interior', 'num_interior') }}
							</div>
							<div class="form-group col-sm-2 ">
								{{ Form::cNumber('* C.P.', 'cp') }}
							</div>
							<div class="form-group col-sm-6 col-md-3">
								{{ Form::cSelect('* País', 'pais', $paises ?? []) }}
							</div>
							<div class="form-group col-sm6 col-md-3">
								{{ Form::cSelect('* Estado', 'estado', [], ['data-url'=>companyAction('HomeController@index').'/administracion.estados/api']) }}
							</div>
							<div class="form-group col-sm-6 col-md-3">
								{{ Form::cSelect('* Municipio', 'municipio', [], ['data-url'=>companyAction('HomeController@index').'/administracion.municipios/api']) }}
							</div>
							<div class="form-group col-sm-6 col-md-3">
								{{ Form::cText('* Colonia', 'colonia') }}
							</div>
						</div>
						<div class="form-group col-sm-12 my-3">
							<div class="sep sepBtn">
        						<button id="agregar-direccion" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
        					</div>
						</div>
					</div>
    				<div class="card-body">
    					<table class="table responsive-table highlight" id="tDirecciones">
    						<thead>
    							<tr>
    								<th>Tipo</th>
    								<th>Domicilio</th>
    								<th>Cp</th>
    								<th>Colonia</th>
    								<th>Municipio</th>
    								<th>Estado</th>
    								<th>Pais</th>
    								<th>Acción</th>
    							</tr>
    						</thead>
    						<tbody>
    						@if(isset($data->direcciones)) 
    							@foreach($data->direcciones as $key=>$detalle)
								<tr>
									<td>
										{!! Form::hidden('direcciones['.$key.'][id_direccion]',$detalle->id_direccion,['class'=>'id_direccion']) !!}
										{{$detalle->tipoDireccion->tipo_direccion}}
										{!! Form::hidden('direcciones['.$key.'][fk_id_tipo_direccion]',$detalle->fk_id_tipo_direccion,['class'=>'fk_id_tipo_direccion']) !!}
									</td>
									<td>
										{{$detalle->calle.' '.$detalle->num_exterior.' '.$detalle->num_interior}}
										{!! Form::hidden('direcciones['.$key.'][calle]',$detalle->calle) !!} 
										{!! Form::hidden('direcciones['.$key.'][num_exterior]',$detalle->num_exterior) !!} 
										{!! Form::hidden('direcciones['.$key.'][num_interior]',$detalle->num_interior) !!}
									</td>
									<td>
										{{$detalle->codigo_postal}}
										{!! Form::hidden('direcciones['.$key.'][codigo_postal]',$detalle->codigo_postal) !!} 
									</td>
									<td>
										{{$detalle->colonia}}
										{!! Form::hidden('direcciones['.$key.'][colonia]',$detalle->colonia) !!} 
									</td>
									<td>
										{{$detalle->municipio->municipio}}
										{!! Form::hidden('direcciones['.$key.'][fk_id_municipio]',$detalle->fk_id_municipio) !!} 
									</td>
									<td>
										{{$detalle->estado->estado}}
										{!! Form::hidden('direcciones['.$key.'][fk_id_estado]',$detalle->fk_id_estado) !!}
									</td>
									<td>
										{{$detalle->pais->pais}}
										{!! Form::hidden('direcciones['.$key.'][fk_id_pais]',$detalle->fk_id_pais) !!}
									</td>
									<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarDireccion(this)"> <i class="material-icons">delete</i></button></td>
								</tr>
    							@endforeach
    						@endif
    						</tbody>
    					</table>
    				</div>
        		</div><!--/Here ends card-->
        	</div><!--/aquí termina el contenido de un tab-->
			
			<div class="tab-pane" id="condicionpago" role="tabpanel">
				<div class="row">
					<div class="col-sm-12 col-md-6 col-lg-5">
        				<div class="card z-depth-1-half mb-3">
            				<div class="card-header text-center">
            					<h5>Condicion de Pago</h5>
            				</div>
            				<div class="card-body row">
            					<div class="col-sm-12 form-group">
            						{{ Form::cSelect('Condicion de pago', 'fk_id_condicion_pago', $condicionpago ?? []) }}
            					</div>
            					<div class="col-sm-12 form-group">
            						{{ Form::cNumber('Monto de crédito', 'monto_credito') }}
            					</div>
            					<div class="col-sm-12 form-group">
            						{{ Form::cNumber('Días de crédito', 'dias_credito') }}
            					</div>
            					<div class="col-sm-12 form-group">
            						{{ Form::cNumber('Interes por retraso (%)', 'interes_retraso') }}
            					</div>
            				</div>
        				</div>
    				</div>
    		
					<!-- Formas de pago -->
            		@if(isset($formaspago))
            		<div class="col-sm-12 col-md-6 col-lg-7">
            			<div class="card z-depth-1-half">
            				<div class="card-header text-center">
            					<h5>Formas de Pago</h5>
            					<p>Seleccione las formas de pago que utiliza este socio de negocio.</p>
            				</div>
            				<div class="card-body">
            					<ul class="row">
                                    @foreach ($formaspago as $key=>$value)
                                    <li class="list-group-item form-group col-lg-12 col-xl-6 p-2">
                                    	{{ Form::cCheckbox($value, 'formaspago['.$key.']') }}
                                    </li>
                                    @endforeach
                                </ul>
            				</div>
            			</div>
            		</div>
            		@endif <!-- Fin Formas de pago -->
				</div>
			</div><!--/aquí termina el contenido de un tab-->
			
			<div id="cuentas" class="tab-pane" role="tabpanel">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="form-group col-sm-12 col-md-6 col-lg-3">
								{{ Form::cSelect('* Banco', 'fk_id_banco', $bancos ?? [], ['class'=>'select2','data-url'=>companyAction('HomeController@index').'/administracion.bancos/api']) }}
							</div>
							<div class="form-group col-sm-12 col-md-6 col-lg-3">
								{{ Form::cNumber('* Cuenta bancaria', 'no_cuenta') }}
							</div>
							<div class="form-group col-sm-12 col-md-6 col-lg-3">
								{{ Form::cNumber('Sucursal', 'sucursal') }}
							</div>
							<div class="form-group col-sm-12 col-md-6 col-lg-3">
								{{ Form::cNumber('Clave interbancaria', 'clave_interbancaria') }}
							</div>
						</div>
						<div class="col-md-12 my-3">
							<div class="sep sepBtn">
        						<button id="agregar-cuenta" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
        					</div>
						</div>
					</div>
					<div class="card-body">
						<table class="table responsive-table highlight" id="tCuentas">
							<thead>
								<tr>
									<th>Banco</th>
									<th>Cuenta bancaria</th>
									<th>Sucursal</th>
									<th>Clave Interbancaria</th>
									<th>Acción</th>
								</tr>
							</thead>
							<tbody>
							@if(isset($data->cuentas)) 
    							@foreach($data->cuentas as $key=>$detalle)
								<tr>
									<td>
										{{$detalle->banco->banco}}
										{!! Form::hidden('cuentas['.$key.'][id_cuenta]',$detalle->id_cuenta,['class'=>'id_cuenta']) !!}
										{!! Form::hidden('cuentas['.$key.'][fk_id_banco]',$detalle->fk_id_banco,['class'=>'fk_id_banco']) !!}
										{!! Form::hidden('cuentas['.$key.'][fk_id_socio_negocio]',$detalle->fk_id_socio_negocio,['class'=>'fk_id_socio_negocio']) !!}
										
										{!! Form::hidden('cuentas['.$key.'][uniquekey]',$detalle->fk_id_banco.'-'.$detalle->no_cuenta,['class'=>'uniquekey']) !!} 
									</td>
									<td>
										{{$detalle->no_cuenta}}
										{!! Form::hidden('cuentas['.$key.'][no_cuenta]',$detalle->no_cuenta) !!} 
									</td>
									<td>
										{{$detalle->no_sucursal}}
										{!! Form::hidden('cuentas['.$key.'][no_sucursal]',$detalle->no_sucursal) !!} 
									</td>
									<td>
										{{$detalle->clave_interbancaria}}
										{!! Form::hidden('cuentas['.$key.'][clave_interbancaria]',$detalle->clave_interbancaria) !!} 
									</td>
									<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarCuenta(this)"> <i class="material-icons">delete</i></button></td>
								</tr>
    							@endforeach
    						@endif
							</tbody>
						</table>
					</div>
				</div>
			</div><!--/aquí termina el contenido de un tab-->	
			
        	<div id="anexos" class="tab-pane" role="tabpanel">
        		<div class="col-sm-12">
            		<div class="card z-depth-1-half">
            			<div class="card-header">
    						<div class="row">
    							<div class="form-group col-md-4">
    								{{ Form::cSelect('* Tipo Anexo', 'tipo_anexo', $tiposanexos ?? []) }}
    							</div>
    							<div class="form-group col-md-4">
    								{{ Form::cText('* Nombre', 'nombre_archivo') }}
    							</div>
    							<div class="form-group col-md-4">
    								{{ Form::cFile('* Archivo', 'archivo',['accept'=>'.xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf']) }}
    							</div>
    							<div class="form-group col-sm-12 my-3">
        							<div class="sep sepBtn">
                						<button id="agregar-anexo" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                					</div>
        						</div>
    						</div>
    					</div>
    					<div class="card-body">
    						<table class="table responsive-table highlight" id="tAnexos">
    							<thead>
    								<tr>
    									<th>Tipo Anexo</th>
    									<th>Nombre</th>
    									<th>Archivo</th>
    									<th>Acción</th>
    								</tr>
    							</thead>
    							<tbody>
    							</tbody>
    						</table>
    					</div>
    				</div><!--/Here ends card-->
    			</div>
			</div><!--/aquí termina el contenido de un tab-->
			
			<div id="productos" class="tab-pane" role="tabpanel">
				<fieldset id="fieldProductos"class="col-sm-12">
            		<div class="card z-depth-1-half">
            			<div class="card-header">
    						<div class="row">
    							<div class="form-group col-md-4">
    								{{ Form::cSelect('* Sku', 'sku', $skus ?? [],['class'=>'select2','data-url'=>companyAction('HomeController@index').'/inventarios.productos/api']) }}
    							</div>
    							<div class="form-group col-md-4">
    								{{ Form::cSelect('Upc', 'upc', $upcs ?? [],['class'=>'select2','data-url'=>companyAction('HomeController@index').'/inventarios.upcs/api']) }}
    							</div>
    							<div class="form-group col-md-4">
    								{{ Form::cNumber('* Tiempo Entrega (dias)', 'tiempo_entrega') }}
    							</div>
    							<div class="form-group col-md-4">
    								{{ Form::cNumber('* Precio', 'precio') }}
    							</div>
    							<div class="form-group col-md-4">
    								{{ Form::cText('* Precio Valido De', 'precio_de') }}
    							</div>
    							<div class="form-group col-md-4">
    								{{ Form::cText('* Precio Valido Hasta', 'precio_hasta') }}
    							</div>
    							<div class="form-group col-sm-12 my-3">
        							<div class="sep sepBtn">
                						<button id="agregar-producto" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                					</div>
        						</div>
    						</div>
    					</div>
    					<div class="card-body">
    						<table class="table responsive-table highlight" id="tProductos">
    							<thead>
    								<tr>
    									<th>Sku</th>
    									<th>Upc</th>
    									<th>Descripcion</th>
    									<th>Tiempo entrega</th>
    									<th>Precio</th>
    									<th>Precio Valido De</th>
    									<th>Precio Valido Hasta</th>
    									<th>Acción</th>
    								</tr>
    							</thead>
    							<tbody>
    							</tbody>
    						</table>
    					</div>
    				</div><!--/Here ends card-->
    			</fieldset>
			</div><!--/aquí termina el tab content-->
			
		</div>
	</div><!--/aquí termina el card content-->
@endsection

{{-- DONT DELETE --}}
@if(Route::currentRouteNamed(currentRouteName('index')))
	@include('layouts.smart.index')
@endif

@if(Route::currentRouteNamed(currentRouteName('create')))
	@include('layouts.smart.create')
@endif

@if(Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if(Route::currentRouteNamed(currentRouteName('show')))
	@include('layouts.smart.show')
@endif