@section('content-width', 's12')
@section('header-bottom')
	@parent
	{{ HTML::script(asset('vendor/multiselect/js/bootstrap-multiselect.js')) }}
	{{ HTML::script(asset('js/sociosnegocios/socios.js')) }}
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	<div class="row my-3">
		<!-- Campos generales -->
		<div class="col-sm-12 col-md-9">
			<h5>Información general</h5>
			<div class="row">
				<div class="form-group col-sm-6 col-md-4">
					{{ Form::cText('* Razón social', 'razon_social') }}
				</div>
				<div class="form-group col-sm-6 col-md-4">
					{{ Form::cText('* RFC', 'rfc') }}
				</div>
				<div class="form-group col-sm-6 col-md-4">
					{{ Form::cText('Nombre Comercial', 'nombre_comercial') }}
				</div>
				<div class="form-group col-sm-6 col-md-4">
					{{ Form::cText('Ejecutivo de venta', 'ejecutivo_venta') }}
				</div>
				<div class="form-group col-sm-6 col-md-4">
					{{ Form::cText('Teléfono', 'telefono') }}
				</div>
				<div class="form-group col-sm-6 col-md-4">
					{{ Form::cText('Sitio web', 'sitio_web') }}
				</div>
			</div>
			<div class="row">
				<div class="form-group col-sm-6 col-md-3">
					{{ Form::cSelect('* Ramo(s)', 'fk_id_ramo', $ramos ?? []) }}
				</div>
				<div class="form-group col-sm-6 col-md-3">
					{{ Form::cSelect('País de Origen', 'fk_id_pais', $paises ?? []) }}
				</div>
				<div class="form-group col-sm-6 col-md-3">
					{{ Form::cSelect('Tipo socio para venta', 'fk_id_tipo_socio_venta', $tipossociosventa ?? []) }}
				</div>
				<div class="form-group col-sm-6 col-md-3">
					{{ Form::cSelect('Tipo socio para compra', 'fk_id_tipo_socio_compra', $tipossocioscompra ?? []) }}
				</div>
				<div class="form-group col-sm-12">
					<div class="alert alert-warning" role="alert">
                        Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
                    </div>
                    <div class="col-sm-12 row">
                		<div class="form-group col-sm-12 col-md-4 col-lg-4">
                			{{ Form::cCheckboxBtn('Estatus', 'Activo', 'activo', $data['activo'] ?? null, 'Inactivo') }}
                    	</div>
                    	<div class="form-group col-sm-6 col-md-4 col-lg-4">
                			{{ Form::cText('Desde', 'activo_desde',['readonly'=>true]) }}
                    	</div>
                    	<div class="form-group col-sm-6 col-md-4 col-lg-4">
                			{{ Form::cText('Hasta', 'activo_hasta',['readonly'=>true]) }}
                    	</div>
                	</div>
				</div>
			</div>
		</div> <!-- Fin campos generales -->
		
		<!-- Empresas -->
		@if(isset($empresas))
		<div class="col-sm-12 col-md-3">
			<div class="card z-depth-1-half">
				<div class="card-header">
					<h5>Empresas</h5>
				</div>
				<div class="card-body">
					<ul class="list-group">
						<li class="list-group-item form-group h6 row">
							{{ Form::cCheckbox('Seleccionar todo', 'select_all') }}
						</li>
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
			<h4 class="card-title text-center">Datos adicionales</h4>
			<ul class="nav nav-pills nav-justified">
				<li class="nav-item"><a class="nav-link active"  role="tab" data-toggle="tab"  href="#con_pago">Condiciones de pago</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#info_entrega">Información de entrega</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#contacts">Contactos</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#directions">Direcciones</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#licencias">Licencias</a></li>
				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#prod_art">Productos/Artículos</a></li>
			</ul>
		</div>
		<div class="card-body tab-content">
			<div  class="tab-pane active" id="con_pago" role="tabpanel">
				<div class="row">
					<div class="col-md-12">
						<div class="row mb-4">
							<div class="col-md-4 col-xs-12 form-group">
								{{ Form::cNumber('Monto de crédito', 'monto_credito') }}
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								{{ Form::cNumber('Días de crédito', 'dias_credito') }}
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								{{ Form::cSelect('Forma de pago', 'fk_id_forma_pago', $formaspago ?? []) }}
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="form-group col-sm-6">
										{{ Form::cNumber('Cuenta bancaria', 'no_cuenta') }}
									</div>
									<div class="form-group col-sm-6">
										{{ Form::cSelect('Banco', 'fk_id_banco', $bancos ?? []) }}
									</div>
								</div>
								<div class="col-md-12 my-3">
									<div class="sep sepBtn">
                						<button id="agregarCuenta" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                					</div>
								</div>
							</div>
							<div class="card-body">
								<table class="table responsive-table highlight" id="tableCuentas">
									<thead>
										<tr>
											<th>Banco</th>
											<th>Cuenta bancaria</th>
											<th>Acción</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div><!--/aquí termina el contenido de un tab-->
			
			<div id="info_entrega" class="tab-pane" role="tabpanel">
				<div class="row">
    				<div class="form-group col-sm-2">
    					{{ Form::cRadio('Tipo de Entrega','tipos_entrega',$tiposentrega ?? []) }}
    				</div>
    				<div class="form-group col-sm-5">
    					{{ Form::cSelect('Sucursal', 'fk_id_sucursal', $sucursales ?? []) }}
    				</div>
    				<div class="form-group col-sm-5">
						{{ Form::cNumber('Pago de Paquetería', 'pago_paqueteria') }}
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-5">
						{{ Form::cNumber('Monto mínimo de facturación', 'monto_minimo_facturacion') }}
					</div>
					<div class="form-group col-sm-5">
						{{ Form::cNumber('Tiempo de Entrega', 'tiempo_entrega') }}
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						<label data-error="Campo obligatorio">*Correos para envío de orden de compra:</label>
						<select class="form-control" multiple="multiple" id="correos" name="correos[]"></select>
					</div>
				</div>
			</div>

			<div id="contacts" class="tab-pane" role="tabpanel">
				<div class="row card">
					<div class="card-header">
						<div class="row">
							<div class="form-group col-sm-6 col-md-3">
								{{ Form::cSelect('Tipo de contacto', 'tipo_contacto', $tiposcontactos ?? []) }}
							</div>
							<div class="form-group col-sm-6- col-md-3">
								{{ Form::cText('Nombre', 'nombre_contacto') }}
							</div>
							<div class="form-group col-sm-6 col-md-3">
								{{ Form::cText('Puesto', 'puesto') }}
							</div>
							<div class="form-group col-sm-6 col-md-3">
								{{ Form::cNumber('Teléfono celular', 'celular') }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-6- col-md-3">
								{{ Form::cNumber('Teléfono', 'telefono_oficina') }}
							</div>
							<div class="form-group col-sm-4 col-md-3">
								{{ Form::cNumber('Extencion', 'extension_oficina') }}
							</div>
							<div class="form-group col-sm-10 col-md-6">
								<label>Correo(s) electrónico(s):</label>
								<select class="form-control" multiple="multiple" id="correos_contacto" name="correos_contacto[]"></select>
							</div>
						</div>
						<div class="form-group col-md-12 my-3">
							<div class="sep sepBtn">
        						<button id="agregarContacto" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
        					</div>
						</div>
					</div>
					<div class="card-body">
						<table class="table responsive-table highlight" id="tableContactos">
							<thead>
								<tr>
									<th>Tipo de contacto</th>
									<th>Nombre</th>
									<th>Puesto</th>
									<th>Celular</th>
									<th>Teléfono oficina - Ext</th>
									<th>Acción</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div><!--/Here ends card-->
    		</div><!--/aquí termina el contenido de un tab-->
		
    		<div id="directions" class="tab-pane" role="tabpanel">
    			<div class="card">
    				<div class="card-header">
    					<div class="row">
    						<div class="form-group col-sm-2">
    							{{ Form::cRadio('Tipo de dirección','tipo_direccion',$tiposdireccion ?? []) }}
    						</div>
							<div class="form-group col-sm-12 col-md-4">
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
						</div>
						<div class="row">
							<div class="form-group col-sm-6 col-md-3">
								{{ Form::cSelect('* País', 'pais', $paises ?? [], ['data-url'=>companyAction('SociosNegocio\SociosNegocioController@getEstados',['id'=>'?id'])]) }}
							</div>
							<div class="form-group col-sm6 col-md-3">
								{{ Form::cSelect('* Estado', 'estado', [], ['data-url'=>companyAction('SociosNegocio\SociosNegocioController@getMunicipios',['id'=>'?id'])]) }}
							</div>
							<div class="form-group col-sm-6 col-md-3">
								{{ Form::cSelect('* Municipio', 'municipio', []) }}
							</div>
							<div class="form-group col-sm-6 col-md-3">
								{{ Form::cText('* Colonia', 'colonia') }}
							</div>
						</div>
						<div class="form-group col-sm-12 my-3">
							<div class="sep sepBtn">
        						<button id="agregarContacto" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
        					</div>
						</div>
					</div>
    				<div class="card-body">
    					<table class="table responsive-table highlight" id="tableDirecciones" name="tableDirecciones">
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
    						</tbody>
    					</table>
    				</div>
        		</div><!--/Here ends card-->
        	</div><!--/aquí termina el contenido de un tab-->
	
        	<div id="licencias" class="tab-pane" role="tabpanel">
        		<div class="row">
            		<div class="col-sm-12 col-md-4 mt-3">
                		<div class="card z-depth-1-half">
                			<div class="card-header">
        						<div class="row">
        							<div class="form-group col-sm-12">
        								{{ Form::cFile('Licencia Sanitaria', 'filesSanitarias') }}
        							</div>
        							<div class="form-group col-sm-12 my-3">
            							<div class="sep sepBtn">
                    						<button id="addLicenciaSanitaria" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                    					</div>
            						</div>
        						</div>
        					</div>
        					<div class="card-body">
        						<table class="table responsive-table highlight" id="tableSanitaria" name="tableSanitaria">
        							<thead>
        								<tr>
        									<th>Licencia(s)</th>
        									<th>Acción</th>
        								</tr>
        							</thead>
        							<tbody>
        							</tbody>
        						</table>
        					</div>
        				</div><!--/Here ends card-->
        			</div>
            		
            		<div class="col-sm-12 col-md-4 mt-3">
        				<div class="card z-depth-1-half">
        					<div class="card-header">
        						<div class="row">
        							<div class="form-group col-sm-12">
        								{{ Form::cFile('Aviso de Funcionamiento', 'filesAvisoFuncionamiento') }}
        							</div>
        							<div class="form-group col-sm-12 my-3">
            							<div class="sep sepBtn">
                    						<button id="addAvisoFuncionamiento" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                    					</div>
            						</div>
        						</div>
            				</div>
        					<div class="card-body">
        						<table class="table responsive-table highlight" id="tableAvisosFuncionamiento">
        							<thead>
        								<tr>
        									<th>Aviso(s)</th>
        									<th>Acción</th>
        								</tr>
        							</thead>
        							<tbody>
        							</tbody>
        						</table>
        					</div>
        				</div><!--/Here ends card-->
        			</div>
        			
        			<div class="col-sm-12 col-md-4 mt-3">
        				<div class="card z-depth-1-half">
        					<div class="card-header">
        						<div class="row">
        							<div class="form-group col-sm-12">
        								{{ Form::cFile('Aviso de Responsable Sanitario', 'filesAvisoResponsable') }}
        							</div>
        							<div class="form-group col-sm-12 my-3">
            							<div class="sep sepBtn">
                    						<button id="addAvisoResponsable" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                    					</div>
            						</div>
        						</div>
        					</div>
        					<div class="card-body">
        						<table class="table responsive-table highlight" id="tableAvisosFuncionamiento">
        							<thead>
        								<tr>
        									<th>Aviso(s)</th>
        									<th>Acción</th>
        								</tr>
        							</thead>
        							<tbody>
        							</tbody>
        						</table>
        					</div>
        				</div><!--/Here ends card-->
        			</div>
				</div>
			</div><!--/aquí termina el contenido de un tab-->
			
			<div id="prod_art" class="tab-pane" role="tabpanel">
				Productos y Articulos
			</div><!--/aquí termina el tab content-->
		</div>
	</div><!--/aquí termina el card content-->
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@include('layouts.smart.show')
@endif