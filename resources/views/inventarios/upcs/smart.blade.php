@extends(smart())

@section('header-bottom')
	@parent
	@notroute(['index'])
		<script src="{{ asset('js/inventarios/upc.js') }}"></script>
		<script>
			const subgrupo_js = '{{ $js_subgrupo ?? '' }}';
		</script>
	@endif
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
    <div class="row">
    	<div class="col-sm-6 col-md-4">
    		<div class="form-group">
    			{{ Form::cText('* Upc', 'upc',[
					'data-url'=>companyAction('Inventarios\ProductosController@getUpcs'),
				]) }}
    		</div>
    	</div>
    	<div class="col-sm-6 col-md-4">
    		<div class="form-group">
    			{{ Form::cText('* Registro Sanitario', 'registro_sanitario') }}
    		</div>
    	</div>
    	<div class="col-sm-12 col-md-4">
    		<div class="form-group">
    			{{ Form::cText('* Nombre Comercial', 'nombre_comercial') }}
    		</div>
    	</div>
    	<div class="form-group col-sm-12">
			{{ Form::cTextArea('* Descripcion', 'descripcion',['maxlength' => '200', 'rows' => '1']) }}
		</div>
	</div>
    <!--/Inicio Tabs-->
    <div id="detallesku" class="w-100 container-fluid z-depth-1-half mt-2 px-0">
		<div class="card" style="min-height: 100px">
			<div class="card-header text-center py-2">
			<h4>Información del producto</h4>
			<div class="divider my-2"></div>
			
			<ul id="clothing-nav" class="nav nav-pills nav-justified" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" role="tab" data-toggle="tab" href="#tab-general" id="general-tab" aria-controls="general" aria-expanded="true">General</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" role="tab" data-toggle="tab" href="#tab-upcs" id="upcs-tab" aria-controls="upcs" aria-expanded="true">Propiedades</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" role="tab" data-toggle="tab" href="#tab-compra" id="compra-tab" aria-controls="compra" aria-expanded="true">Información Técnica</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" role="tab" data-toggle="tab" href="#tab-inventario" id="inventario-tab" aria-controls="inventario" aria-expanded="true">Compra</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" role="tab" data-toggle="tab" href="#tab-clientes" id="clientes-tab" aria-controls="clientes" aria-expanded="true">Clientes <span id="current_clients" class="badge badge-light">0</span></a>
				</li>
			</ul>
			</div>
			
		<!-- Content Panel -->
		<div class="card-body">
			<div id="clothing-nav-content" class="tab-content">
				<div role="tabpanel" class="tab-pane fade show active" id="tab-general" aria-labelledby="general-tab">
					<div class="row">
						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								{{ Form::cText('* Marca', 'marca') }}
							</div>
						</div>
						<div class="col-sm-6 col-md-2">
							<div class="form-group">
								{{ Form::cText('Peso', 'peso',['placeholder'=>'Ejm: 10cm']) }}
							</div>
						</div>
						<div class="col-sm-6 col-md-2">
							<div class="form-group">
								{{ Form::cText('Longitud', 'longitud',['placeholder'=>'Ejm: 10cm']) }}
							</div>
						</div>
						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								{{ Form::cSelect('* Pais Origen', 'fk_id_pais_origen', $paises ?? [],[
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
								]) }}
							</div>
						</div>
						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								{{ Form::cSelect('* Laboratorio', 'fk_id_laboratorio', $laboratorios ?? [],[
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
								]) }}
							</div>
						</div>
						<div class="col-sm-6 col-md-2">
							<div class="form-group">
								{{ Form::cText('Ancho', 'ancho',['placeholder'=>'Ejm: 10cm']) }}
							</div>
						</div>
						<div class="col-sm-6 col-md-2">
							<div class="form-group">
								{{ Form::cText('Altura', 'altura',['placeholder'=>'Ejm: 10cm']) }}
							</div>
						</div>
					</div>
					<div class="row mt-3 text-center">
						<div class="form-group col-sm-6 col-md-6">
							<div class="alert alert-warning" role="alert">
								Recuerda que al ser <b>descontinuado</b>, este <b>producto</b> no se mostrara en los modulos correspondientes que se requieran.
							</div>
							{{ Form::cCheckboxBtn('¿Producto Descontinuado?','Si','descontinuado', $data['descontinuado'] ?? null, 'No') }}
						</div>
						<div class="form-group col-sm-6 col-md-6">
							<div class="alert alert-warning" role="alert">
								Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
							</div>
							{{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-upcs" aria-labelledby="upcs-tab">
					<div class="row">
						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								{{ Form::cSelectWithDisabled('* Subgrupo productos','fk_id_subgrupo',$subgrupo ?? [],[],$subgrupo_data ?? []) }}
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								{{ Form::cSelect('* Forma farmacéutica','fk_id_forma_farmaceutica',$formafarmaceutica ?? [],[
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '',
								]) }}
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								{{ Form::cSelect('* Presentación','fk_id_presentaciones', $presentaciones ?? [],[
									'style' => 'width:100%;',
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
									]) }}
							</div>
						</div>
					</div>
					
					<div class="row">
						<div id="tableIndicacion" class="col" style="display:none;">
							@if(!Route::currentRouteNamed(currentRouteName('show')))
								<div class="card-header">
									<form id="overallForm">
										<fieldset id="detalle-form">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														{{ Form::cSelect('* Indicacion terapeutica','indicacion', $indicaciones ?? [],[
														'style' => 'width:100%;',
														'class' => 'select2',
														]) }}
													</div>
												</div>
											</div><!--/row-->
										<div class="col-sm-12 text-center my-3">
											<div class="sep">
												<div class="sepBtn">
													<button id="addIndication" style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
												</div>
											</div>
										</div>
										</fieldset>
									</form>
								</div><!--/card-header-->
							@endif
							<div class="card-body">
								<table class="table table-responsive-sm table-striped table-hover">
									<thead>
										<tr>
											<th>Indicación terapéutica</th>
											<th> </th>
										</tr>
									</thead>
									<tbody id="tbodyIndication">
										@if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
											@foreach($data->indicaciones as $row => $detalle)
												<tr>
													<td><input type="hidden" value="{{$detalle->id_detalle}}" name="relations[has][detalle][{{$row}}][id_detalle]">{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_upc]',$detalle->fk_id_upc) }}{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_indicacion_terapeutica]',$detalle->fk_id_indicacion_terapeutica) }}{{ $detalle->indicacion->indicacion_terapeutica }}</td>
														@if(Route::currentRouteNamed(currentRouteName('show')))
														@else
													<td>
														<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>
													</td>
														@endif
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
						
						<div id="tableSal" class="col" style="display:none;">
							@if(!Route::currentRouteNamed(currentRouteName('show')))
								<div class="card-header">
									<form id="overallForm">
										<fieldset id="detalle-form">
											<div class="row">
												<div class="col-md-8">
													<div class="from-group">
														{{ Form::cSelect('* Sal','sal', $sales ?? [],[
															'style' => 'width:100%;',
															'class' => 'select2',
														]) }}
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														{{ Form::cSelect('* Concentración','concentracion', $presentaciones ?? [],[
														'style' => 'width:100%;',
														'class' => 'select2',
														]) }}
													</div>
												</div>
											</div><!--/row-->
										<div class="col-sm-12 text-center my-3">
											<div class="sep">
												<div class="sepBtn">
													<button id="addPresentation" style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
												</div>
											</div>
										</div>
										</fieldset>
									</form>
								</div><!--/card-header-->
							@endif
							<div class="card-body">
								<table class="table table-responsive-sm table-striped table-hover">
									<thead>
										<tr>
											<th>Sal</th>
											<th>Concentración</th>
											<th></th>
										</tr>
									</thead>
									<tbody id="tbodyPresentation">
										@if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
											@foreach($data->presentaciones as $row => $detalle)
												<tr>
													<td>
														<input type="hidden" value="{{$detalle->id_detalle}}" name="relations[has][detalle][{{$row}}][id_detalle]">
														{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_upc]',$detalle->fk_id_upc) }}
														{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_presentaciones]',$detalle->fk_id_presentaciones,['class' => 'id_concentracion']) }}
														{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_sal]',$detalle->fk_id_sal,['class' => 'id_sal']) }}
														{{ $detalle->sal->nombre }}
													</td>
													<td>
														{{ $detalle->presentacion->cantidad.' '.$detalle->presentacion->unidad->clave }}
													</td>
														@if(Route::currentRouteNamed(currentRouteName('show')))
														@else
													<td>
														<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>
													</td>
														@endif
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div><!--/table-->

					<div id="tableMaterial" class="col" style="display:none;">
							@if(!Route::currentRouteNamed(currentRouteName('show')))
								<div class="card-header">
									<form id="overallForm">
										<fieldset id="detalle-form">
											<div class="row">
												<div class="col-md-12">
													<div class="from-group">
														{{ Form::cSelect('* Especificaciones','especificacion', $especificaciones ?? [],[
															'style' => 'width:100%;',
															'class' => 'select2',
															]) }}
													</div>
												</div>
											</div><!--/row-->
										<div class="col-sm-12 text-center my-3">
											<div class="sep">
												<div class="sepBtn">
													<button id="addMaterial" style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
												</div>
											</div>
										</div>
										</fieldset>
									</form>
								</div><!--/card-header-->
							@endif
							<div class="card-body">
								<table class="table table-responsive-sm table-striped table-hover">
									<thead>
										<tr>
											<th>Especificación</th>
											<th></th>
										</tr>
									</thead>
									<tbody id="tbodyMaterials">
										@if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
											@foreach($data->especificaciones as $row => $detalle)
												<tr>
													<td>
														<input type="hidden" value="{{$detalle->id_detalle}}" name="especificaciones[{{$row}}][id_detalle]">
														{{ Form::hidden('especificaciones['.$row.'][fk_id_especificacion]',$detalle->id_especificacion,['class' => 'id_mat']) }}
														{{ Form::hidden('especificaciones['.$row.'][fk_id_sku]',$detalle->fk_id_sku) }}
														{{ $detalle->especificacion }}
													</td>
														@if(Route::currentRouteNamed(currentRouteName('show')))
														@else
													<td>
														<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>
													</td>
														@endif
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div><!--/detalle-->

					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-compra" aria-labelledby="compra-tab">
					<div class="row">
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								{{ Form::cSelect('* Clasificación producto','fk_id_presentacion_venta',$presentacionventa ?? [],[
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '',
								]) }}
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								{{ Form::cSelect('* Tipo producto','fk_id_tipo_producto',$tipoproducto ?? [],[
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '',
								]) }}
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								{{ Form::cSelect('* Vía Administración','fk_id_via_administracion',$viaadministracion ?? [],[
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '',
								]) }}
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-inventario" aria-labelledby="inventario-tab">
					<div class="row">
						<div class="form-group col-sm-6 col-md-2">
							{{ Form::cNumber('* Costo base', 'costo_base',['placeholder'=>'Ejm: 10', 'max'=>'999']) }}
						</div>
						<div class="col-sm-6 col-md-5">
							<div class="form-group">
								{{ Form::cSelect('* Tipo de moneda','fk_id_moneda',$monedas ?? [],[
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '',
								]) }}
							</div>
						</div>
						<div class="col-sm-12 col-md-5">
							<div class="form-group">
								{{ Form::cSelect('* Tipo de familia','fk_id_tipo_familia',$familias ?? [],[
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '',
								]) }}
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-clientes" aria-labelledby="clientes-tab">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-responsive-sm table-striped table-hover">
								<thead>
									<tr>
										<th>Clave cliente</th>
										<th>Subclave</th>
										<th>Descripción</th>
										<th>Precio</th>
									</tr>
								</thead>
								<tbody id="tbodyClients">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Content Panel -->
		</div>
	</div>
	<!--/Fin Tabs-->
@endsection