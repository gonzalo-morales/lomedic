@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.date.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
@endsection

@section('header-bottom')
	<script type="text/javascript" src="{{ asset('js/sociosnegocios/socios.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/select2.full.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/pickadate/picker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/pickadate/picker.date.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/pickadate/translations/es_Es.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/toaster.js') }}"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
@endsection

@section('content')
	{{-- <style media="screen">
	.select2-selection__rendered {
		 line-height: 24px !important;
		}
	</style> --}}

	<form action="{{ companyRoute("store",['company' => $company]) }}" method="post" class="col s12" enctype="multipart/form-data" id="form-socios">
		{{ csrf_field() }}
		{{ method_field('POST') }}

		<div class="row">
			<div class="right">
				<button class="btn btn-success" type="submit" name="action" id="guardarSocio">Guardar {{ trans_choice('messages.'.$entity, 0) }}</button>
				<button class="btn btn-primary waves-effect waves-light" name="action">Imprimir</button>
				<button class="btn btn-warning waves-effect waves-teal btn-flat teal-text">Cancelar</button>
			</div>
		</div><!--/row buttons-->

		<div class="row">
			<div class="col s12 m8">
				<h5>Información general</h5>
				<label class="custom-control custom-checkbox">
					<input class="custom-control-input" type="checkbox" id="activo" name="activo" />
					<span class="custom-control-indicator"></span>
					<span class="custom-control-description">Activo</span>
				</label>
				<div class="row">
					<div class="input-field col s6 m4">
						<label for="razon_social" data-error="Campo obligatorio">*Razón social:</label>
						<input id="razon_social" name="razon_social" type="text" class="form-control active">
						{{-- <p class="help-block deep-orange-text">Este es un error</p> --}}
						{{-- TODO: averiguar como mostrar los errores desde el ajax response --}}
					</div>
					<div class="input-field col s6 m4">
						<label for="rfc" data-error="Campo obligatorio">*RFC:</label>
						<input id="rfc" name="rfc" type="text" class="form-control">
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6 m4">
						<label for="nombre_corto">Nombre Comercial:</label>
						<input id="nombre_corto" name="nombre_corto" type="text" class="form-control">
					</div>
					<div class="input-field col s6 m4">
						<label for="ejecutivo_venta">Ejecutivo de venta:</label>
						<input id="ejecutivo_venta" name="ejecutivo_venta" type="text" class="form-control">
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6 m4">
						<label for="telefono">Teléfono:</label>
						<input id="telefono" name="telefono" type="tel" class="form-control">
					</div>
					<div class="input-field col s6 m4">
						<label for="sitio_web">Sitio web:</label>
						<input id="sitio_web" name="sitio_web" type="text" class="form-control">
					</div>
				</div>
				<div class="row">

				<div class="col-sm-6 col-md-3">
					<label>*Ramo(s):</label>
					<select id="ramo" name="ramo" class="custom-select form-control">
						<option disabled selected>Selecciona...</option>
						@foreach ($ramos as $ramo)
							<option value="{{$ramo->id_ramo}}" >{{$ramo->ramo}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-sm-6 col-md-3">
					<label>País de Orígen:</label>
					<select id="pais_origen" name="pais_origen" class="custom-select form-control">
						<option value="" disabled selected>Selecciona...</option>
						@foreach ($paises as $pais)
							<option value="{{$pais->id_pais}}" >{{$pais->pais}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-sm-6 col-md-3">
					<label data-error="Campo obligatorio">*Tipo de socio:</label>
					<select id="tipo_socio" name="tipo_socio[]" multiple="multiple" class="form-control">
						{{-- <option value="" disabled selected>Selecciona...</option> --}}
						@foreach ($tiposSocios as $tipoSocio)
							<option value="{{$tipoSocio->id_tipo_socio}}">{{$tipoSocio->tipo_socio}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-sm-6 col-md-3">
					<label data-error="Campo obligatorio">*Moneda:</label>
					<select id="moneda" name="moneda" class="custom-select form-control">
						<option value="" disabled selected>Selecciona...</option>
						@foreach ($monedas as $moneda)
							<option value="{{$moneda->id_moneda}}">{{$moneda->moneda}}</option>
						@endforeach
					</select>
				</div>
			</div>
			</div>
			<div class="col-sm-12 col-md-4">
				<h5>Empresas</h5>
				<div class="internSectionTable">
					<table class="responsive-table highlight" id="empresas">
						<thead>
							<tr>
								<th>
									<label class="custom-control custom-checkbox">
										<input class="custom-control-input" type="checkbox" id="select_all"/>
										<span class="custom-control-indicator"></span>
										<span class="custom-control-description">Seleccionar todo</span>
									</label>
								</th>
								{{-- <th>Empresas:</th> --}}
							</tr>
						</thead>
						<tbody>
							@foreach ($empresas as $empresa)
								<tr>
									<td>
										<label class="custom-control custom-checkbox">
											<input class="custom-control-input" type="checkbox" id="{{$empresa->id_empresa}}" data-name="{{$empresa->nombre_comercial}}"/>
											<span class="custom-control-indicator"></span>
											<span class="custom-control-description">{{$empresa->nombre_comercial}}</span>
										</label>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<h5>Datos adicionales</h5>
				<div class="card teal">
					<div class="card-content white-text">
						<p>Ingresa la información de acuerdo a las categorías:</p>
					</div>
					<div >
						<ul class="nav nav-tabs">
							<li class="nav-item"><a class="nav-link active"  role="tab" data-toggle="tab"  href="#con_pago">Condiciones de pago</a></li>
							<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#info_entrega">Información de entrega</a></li>
							<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#contacts">Contactos</a></li>
							<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#directions">Direcciones</a></li>
							<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#licencias">Licencias</a></li>
							<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#prod_art">Productos/Artículos</a></li>
						</ul>
					</div>
					<div class="tab-content">
						<div  class="tab-pane active" id="con_pago" role="tabpanel">
							<div class="row">
								<div class="col-md-12">
									<div class="card">
										<div class="card-header">
											<div class="row">
												<div class="col-md-4 col-xs-12 form-group">
													<label for="monto_credito">Monto de crédito:</label>
													<input id="monto_credito" name="monto_credito" type="number" min="0" class="form-control">
												</div>
												<div class="col-md-4 col-xs-12 form-group">
													<label for="dias_credito">Días de crédito:</label>
													<input id="dias_credito" name="dias_credito" type="number" min="0" class="form-control">
												</div>
												<div class="col-md-4 col-xs-12 form-group">
													<label>Forma de pago:</label>
													<select id="forma_pago" name="forma_pago" class="custom-select">
														<option value="" disabled selected>Selecciona...</option>
														@foreach ($formasPago as $formaPago)
															<option value="{{$formaPago->id_forma_pago}}">{{$formaPago->descripcion}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="card-image">
												{{-- <form> --}}
												<div class="row">
													<div class="form-group col-sm-6">
														<label for="no_cuenta">Cuenta bancaria:</label>
														<input id="no_cuenta" name="no_cuenta" type="number" min="0" class="form-control">
													</div>
													<div class="form-group col-sm-6">
														<label>Banco:</label>
														<select id="banco" name="banco" class="custom-select form-control">
															<option value="" disabled selected>Selecciona...</option>
															@foreach ($bancos as $banco)
																<option value="{{$banco->id_banco}}">{{$banco->banco}}</option>
															@endforeach
														</select>
													</div>
												</div>

												<div class="col-md-12">
													<div class="sep">
														<div class="sepBtn">
															<button class="btn btn-primary btn-large tooltipped" style="width: 4em; height:4em; border-radius:50%;"
															data-position="bottom" data-delay="50" data-tooltip="Agregar" type="submit" id="agregarCuenta" data-action="add"><i
															class="material-icons">add</i></button>
														</div>
													</div>
												</div>

												{{-- </form><!--/Here ends de form--> --}}
											</div><!--/Here ends the up section-->
										</div>
										<div class="divider"></div>
										<div class="row">
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
														{{-- Here going to be the new rows --}}
													</tbody>
												</table>
											</div><!--/here ends de down section-->
										</div>
									</div>
								</div>
							</div>
						</div><!--/aquí termina el contenido de un tab-->
						<div id="info_entrega" class="tab-pane" role="tabpanel">
							{{-- <div class="container"> --}}
							<div class="col-md-12">
								{{-- <form action="#" class="col s12"> --}}
								<label data-error="Campo obligatorio">*Tipo de Entrega:</label>
								@foreach ($tiposEntrega as $tipoEntrega)
									<div class="row col-sm-12">
									<label class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="tipos_entrega" id="{{ strtolower($tipoEntrega->tipo_entrega) ."_". $tipoEntrega->id_tipo_entrega }}" data-idtipoentrega="{{ $tipoEntrega->id_tipo_entrega }}">
										<span class="custom-control-indicator"></span>
										<span class="custom-control-description">{{ $tipoEntrega->tipo_entrega }}</span>
									</label>
									{{-- <p>
										<input class="form-control" name="tipos_entrega" type="radio" id="{{ strtolower($tipoEntrega->tipo_entrega) ."_". $tipoEntrega->id_tipo_entrega }}" data-idtipoentrega="{{ $tipoEntrega->id_tipo_entrega }}" />
										<label for="{{ strtolower($tipoEntrega->tipo_entrega) ."_".$tipoEntrega->id_tipo_entrega }}">{{ $tipoEntrega->tipo_entrega }}</label>
									</p> --}}
									</div>
								@endforeach
								{{-- </form> --}}
								{{-- // TODO: cambiar sucursalNombre por multiselect de los estados donde hay sucursales --}}
								<div class="row">
									{{-- <div class="form-group col-sm-4" id="sucursalBlock" hidden="true" >
										<label for="">Sucursal:</label>
										<input type="text" class="form-control" name="sucursalNombre" id="sucursalNombre" value="">
									</div> --}}
									<div class="form-group col-sm-4" id="sucursalBlock" hidden="true" >
										<label for="">Sucursal:</label>
										<select name="sucursalNombre" id="sucursalNombre" multiple="multiple"
											{{-- data-url="{{companyAction('SociosNegocio\SociosNegocioController@getEstados',['id'=>42])}}" --}} >
											@foreach ($estados as $estado)
												<option value="{{$estado->id_estado}}">{{$estado->estado}}</option>
											@endforeach
										</select>
									</div>


									<div class="form-group col-sm-4" id="paqueteriaBlock" hidden="true" >
										<label for="">Pago de Paquetería:</label>
										<input type="text" class="form-control" name="pagoPaqueteria" id="pagoPaqueteria" value="">
									</div>
									<div class="form-group col-sm-4">
										<label for="monto_minimo_facturacion">Monto mínimo de facturación:</label>
										<input id="monto_minimo_facturacion" name="monto_minimo_facturacion" type="number" class="form-control">
									</div>
									<div class="form-group col-sm-4">
										<label for="tiempo_entrega">Tiempo de Entrega:</label>
										<input id="tiempo_entrega" name="tiempo_entrega" type="number" class="form-control">
									</div>
								</div>
								{{-- <div class="col-sm-4">
									<label data-error="Campo obligatorio">*Correos para envío de orden de compra:</label>
									<div id="correos" name="correos[]" class="chips chips-initial" ></div>
								</div> --}}
								<div class="row">
									<div class="col-sm-12 col-md-12">
										<label data-error="Campo obligatorio">*Correos para envío de orden de compra:</label>
										<select class="form-control" multiple="multiple" id="correos" name="correos[]"></select>
									</div>
								</div>
							</div>
							{{-- </div> --}}
							{{-- <button type="button" id="yy">Y</button> --}}
						</div><!--/aquí termina el contenido de un tab-->
			<div id="contacts" class="tab-pane" role="tabpanel">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							{{-- <form> --}}
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6 col-md-3">
										<div class="form-group">
											<label>Tipo de contacto:</label>
											<select id="tipo_contacto" name="tipo_contacto" class="custom-select form-control">
												<option value="" disabled selected>Selecciona...</option>
												@foreach ($tiposContactos as $tipoContacto)
													<option value="{{$tipoContacto->id_tipo_contacto}}">{{$tipoContacto->tipo_contacto}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group col-sm-6- col-md-3">
										<label for="nombre_contacto">Nombre:</label>
										<input id="nombre_contacto" name="nombre_contacto" type="text" class="form-control">
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<label for="puesto">Puesto:</label>
										<input id="puesto" name="puesto" type="text" class="form-control">
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<label for="celular">Teléfono celular:</label>
										<input id="celular" name="celular" type="tel" class="form-control">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-6- col-md-3">
										<label for="telefono_oficina">Teléfono:</label>
										<input id="telefono_oficina" name="telefono_oficina" type="text" class="form-control">
									</div>
									<div class="form-group col-sm-4 col-md-2">
										<label for="extension_oficina">Ext</label>
										<input id="extension_oficina" name="extension_oficina" type="text" class="form-control">
									</div>
									<div class="col-sm-10 col-md-6">
										<label>Correo(s) electrónico(s):</label>
										<select class="form-control" multiple="multiple" id="correos_contacto" name="correos_contacto[]"></select>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="sep">
									<div class="sepBtn">
										<button class="btn btn-primary btn-large tooltipped" style="width: 4em; height:4em; border-radius:50%;"
										data-position="bottom" data-delay="50" data-tooltip="Agregar" id="agregarContacto" type="submit" data-action="add"><i
										class="material-icons">add</i></button>
									</div>
								</div>
							</div>
							{{-- </form><!--/Here ends de form--> --}}
						</div><!--/Here ends the up section-->
						<div class="divider"></div>
						<div class="card-body">
							<table class="table responsive-table highlight" id="tableContactos">
								<thead>
									<tr>
										<th>Tipo de contacto</th>
										<th>Nombre</th>
										<th>Teléfono oficina + Ext</th>
										<th>Acción</th>
									</tr>
								</thead>
								<tbody></tbody>
						</table>
					</div><!--/here ends de down section-->
				</div>
			</div>
		</div><!--/aquí termina el contenido de un tab-->
		<div id="directions" class="tab-pane" role="tabpanel">
			<div class="card z-depth-0">
				<div class="card-image">
					{{-- <form> --}}
					<div class="col-md-12">

						<div class="col-sm-12">
							{{-- <form action="#"> --}}
							<label>*Tipo de dirección:</label>
							@foreach ($tiposDireccion as $tdireccion)
								<div class="row col-sm-12">
								<label class="custom-control custom-radio">
									<input type="radio" class="custom-control-input" name="tipo_direccion" id="{{ 'dom_'.$tdireccion->id_tipo_direccion }}">
									<span class="custom-control-indicator"></span>
									<span class="custom-control-description">{{ $tdireccion->tipo_direccion }}</span>
								</label>
								{{-- <p>
									<input name="tipo_direccion" class="form-control" type="radio" id="{{ 'dom_'.$tdireccion->id_tipo_direccion }}" />
									<label for="{{ 'dom_'.$tdireccion->id_tipo_direccion }}">{{ $tdireccion->tipo_direccion }}</label>
								</p> --}}
								</div>
							@endforeach
							{{-- </form> --}}
						</div>
						{{-- <div class="col-md-12 text-center mt-4" id="alert-domicilio">
							<div class="alert alert-warning" role="alert">
								Ingresar al menos <b>1 domicilio fiscal</b> (debido a que seleccionó cliente en tipo de socio).
							</div>
						</div> --}}
						<div class="row">
							<div class="form-group col-sm-12 col-md-6">
								<label for="calle" data-error="Campo obligatorio">*Calle:</label>
								<input id="calle" name="calle" type="text" class="form-control">
							</div>
						{{-- <div class="col-sm-12 col-md-6"> --}}
							<div class="col-sm-2 ">
								<label for="num_exterior">Número Ext.:</label>
								<input id="num_exterior" name="num_exterior" type="number" min="0" class="form-control">
							</div>
							<div class="col-sm-2 ">
								<label for="num_interior">Interior:</label>
								<input id="num_interior" name="num_interior" type="number" min="0" class="form-control">
							</div>
							<div class="col-sm-2 ">
								<label for="cp" data-error="Campo obligatorio">*C.P.:</label>
								<input id="cp" name="cp" type="number" min="0" class="form-control">
							</div>
						{{-- </div> --}}
						</div>
						<div class="row">
							<div class="form-group col-sm-6 col-md-3">
								<label data-error="Campo obligatorio">*País:</label>
								<select id="pais" name="pais" class="form-control custom-select">
									<option value="" disabled selected>Selecciona...</option>
									@foreach ($paises as $pais)
										<option value="{{$pais->id_pais}}" data-url="{{companyAction('SociosNegocio\SociosNegocioController@getEstados',['id'=>$pais->id_pais])}}">{{$pais->pais}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm6 col-md-3">
								<label data-error="Campo obligatorio">*Estado:</label>
								<select id="estado" name="estado" class="form-control" data-url="{{companyAction('SociosNegocio\SociosNegocioController@getMunicipios',['id'=>'?id'])}}">
									<option value="" disabled="disabled" selected="selected">Selecciona...</option>
								</select>
							</div>
							<div class="col-sm-6 col-md-3">
								<label data-error="Campo obligatorio">*Municipio:</label>
								<select id="municipio" name="municipio" class="form-control">
									<option value="" disabled >Selecciona...</option>
								</select>
							</div>
							<div class="col-sm-6 col-md-3">
								<label for="colonia">*Colonia:</label>
								<input type="text" id="colonia" class="form-control">
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="sep">
							<div class="sepBtn">
								<button class="btn btn-primary btn-large tooltipped" style="width: 4em; height:4em; border-radius:50%;"
								data-position="bottom" data-delay="50" data-tooltip="Agregar" type="submit" id="agregarDireccion" data-action="add"><i
								class="material-icons">add</i></button>
							</div>
						</div>
					</div>

					{{-- </form><!--/Here ends de form--> --}}
				</div><!--/Here ends the up section-->
				<div class="divider"></div>
				<div class="card-body">
					<table class="table responsive-table highlight" id="tableDirecciones" name="tableDirecciones">
						<thead>
							<tr>
								<th>Tipo</th>
								<th>Calle y número</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody></tbody>
				</table>
			</div><!--/here ends de down section-->
		</div>
	</div><!--/aquí termina el contenido de un tab-->
	<div id="licencias" class="tab-pane" role="tabpanel">
		<div class="col-md-12">
			<p>Agrega o elimina los documentos correspondientes de acuerdo al Proovedor</p>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-4">
				<div class="card z-depth-0">
					<div class="card-image">
						{{-- <form> --}}
							{{-- <div class="row"> --}}
								<div class="col-sm-12">
									<label>Licencia Sanitaria</label>
									<label class="custom-file">
									  <input type="file" multiple id="filesSanitarias" class="custom-file-input" >
									  <span class="custom-file-control"></span>
									</label>
								</div>
							<div class="col-md-12">
								<div class="sep">
									<div class="sepBtn">
										<button class="btn btn-primary btn-large tooltipped" style="width: 4em; height:4em; border-radius:50%;"
										data-position="bottom" data-delay="50" data-tooltip="Agregar" type="submit" id="addLicenciaSanitaria" data-action="add"><i
										class="material-icons">add</i></button>
									</div>
								</div>
							</div>
						{{-- </form><!--/Here ends de form--> --}}
					</div><!--/Here ends the up section-->
					<div class="divider"></div>
					<div class="card-content">
						<table class="table responsive-table highlight" id="tableSanitaria" name="tableSanitaria">
							<thead>
								<tr>
									<th>Licencia(s)</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div><!--/here ends de down section-->
				</div>
			</div><!--/Aquí termina el componente para añadir-->
			<div class="col-sm-12 col-md-4">
				<div class="card z-depth-0">
					<div class="card-image">
						{{-- <form> --}}
									<div class="col-sm-12">
										<label>Aviso de Funcionamiento</label>
										<label class="custom-file">
										  <input type="file" multiple id="filesAvisoFuncionamiento" class="custom-file-input" >
										  <span class="custom-file-control"></span>
										</label>
									</div>
							<div class="col-md-12">
								<div class="sep">
									<div class="sepBtn">
										<button class="btn btn-primary btn-large tooltipped" style="width: 4em; height:4em; border-radius:50%;"
										data-position="bottom" data-delay="50" data-tooltip="Agregar" type="submit" id="addAvisoFuncionamiento" data-action="add"><i
										class="material-icons">add</i></button>
									</div>
								</div>
							</div>
						{{-- </form><!--/Here ends de form--> --}}
					</div><!--/Here ends the up section-->
					<div class="divider"></div>
					<div class="card-content">
						<table class="table responsive-table highlight" id="tableAvisosFuncionamiento">
							<thead>
								<tr>
									<th>Aviso(s)</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div><!--/here ends de down section-->
				</div>
			</div><!--/Aquí termina el componente para añadir-->
			<div class="col-sm-12 col-md-4">
				<div class="card z-depth-0">
					<div class="card-image">
						{{-- <form> --}}
								<div class="col-sm-12">
									<label>Aviso de Responsable Sanitario</label>
									<label class="custom-file">
									  <input type="file" multiple id="filesAvisoResponsable" class="custom-file-input" >
									  <span class="custom-file-control"></span>
									</label>
								</div>
							<div class="col-md-12">
								<div class="sep">
									<div class="sepBtn">
										<button class="btn btn-primary btn-large tooltipped" style="width: 4em; height:4em; border-radius:50%;"
										data-position="bottom" data-delay="50" data-tooltip="Agregar" type="submit" id="addAvisoResponsable" data-action="add"><i
										class="material-icons">add</i></button>
									</div>
								</div>
							</div>
						{{-- </form><!--/Here ends de form--> --}}
					</div><!--/Here ends the up section-->
					<div class="divider"></div>
					<div class="card-content">
						<table class="table responsive-table highlight" id="tableAvisosResponsable">
							<thead>
								<tr>
									<th>Aviso(s)</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div><!--/here ends de down section-->
				</div>
			</div><!--/Aquí termina el componente para añadir-->
		</div>
	</div><!--/aquí termina el contenido de un tab-->

</div>
</div>
</div>
</div><!--/Aquí terminan las tabs (row)-->
</form>

@endsection
