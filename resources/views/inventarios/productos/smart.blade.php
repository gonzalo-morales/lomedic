@extends(smart())

@section('header-bottom')
    @parent
    @if(!Route::currentRouteNamed(currentRouteName('index')))
    	<script type="text/javascript">
        	var param_js = '{{ $api_js ?? '' }}';
        </script>
    	{{ HTML::script(asset('js/inventarios/productos.js')) }}
    @endif
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
    <div class="p-3 my-2">
        <div class="row">
        	<div class="col-sm-6 col-md-9 row">
				<div class="col-sm-12 col-md-4">
					<div class="form-group">
						{{ Form::cSelect('Clave CBN','fk_id_cbn',$cbn ?? [],[
							'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '',
						]) }}
					</div>
				</div>
            	<div class="col-sm-12 col-md-2">
            		<div class="form-group">
            			{{ Form::cSelect('* Serie Sku', 'fk_id_serie_sku', $seriesku ?? [], !Route::currentRouteNamed(currentRouteName('create')) ? ['readonly'=>true] : ['data-url'=>companyAction('Administracion\SeriesSkusController@getSerie',['id'=>'?id'])]) }}
            		</div>
            	</div>
            	<div class="col-sm-12 col-md-3">
            		<div class="form-group">
            			@if (Route::currentRouteNamed(currentRouteName('create')))
            			<i class="material-icons text-danger float-left" data-toggle="tooltip" data-placement="top" title="El numero de serie puede cambiar si otro usuario genero un sku antes de que se guardara este. Verificalo despues de guardarlo.">warning</i>
            			@endif
            			{{ Form::cText('* Sku', 'sku', ['placeholder'=>'Ejemplo: SO-01922-09','readonly'=>true]) }}
            		</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="form-group">
						{{ Form::cSelect('* Forma farmacéutica','fk_id_forma_farmaceutica',$formafarmaceutica ?? [],[
							'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '',
							'data-url'=>companyRoute('getUpcs'),
						]) }}
					</div>
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="form-group">
						{{ Form::cSelect('* Presentación', 'fk_id_presentaciones', $presentaciones ?? [],['class'=>'select2']) }}
					</div>
				</div>
            	<div class="col-sm-12 col-md-8">
            		<div class="form-group">
            			{{ Form::cTextArea('* Descripcion Corta', 'descripcion_corta',['maxlength' => '200', 'rows' => '1']) }}
            		</div>
            	</div>
        	</div>
        	<div class="col-sm-6 col-md-4 col-lg-3 row">
            	<div class="col-sm-12">
            		<div class="form-group">
            			{{ Form::cCheckbox('Articulo de Venta', 'articulo_venta') }}
            		</div>
            	</div>
            	<div class="col-sm-12">
            		<div class="form-group">
            			{{ Form::cCheckbox('Articulo de Compra', 'articulo_compra') }}
            		</div>
            	</div>
            	<div class="col-sm-12">
            		<div class="form-group">
            			{{ Form::cCheckbox('Articulo de Inventario', 'articulo_inventario') }}
            		</div>
            	</div>
            	<div class="col-sm-12">
            		<div class="form-group">
            			{{ Form::cCheckbox('Maneja Lote', 'maneja_lote') }}
            		</div>
            	</div>
            </div>
        </div>
    </div>
    
    <!--/Inicio Tabs-->
    <div id="detallesku" class="w-100 container-fluid z-depth-1-half mt-2 px-0">
        <div class="card">
            <div class="card-header text-center py-2">
				<h4>Informacion del producto</h4>
				<div class="divider my-2"></div>
				<ul id="clothing-nav" class="nav nav-pills nav-justified" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" role="tab" data-toggle="tab" href="#tab-general" id="general-tab" aria-controls="general" aria-expanded="true">General</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-upcs" id="upcs-tab" aria-controls="upcs" aria-expanded="true">Upc's <span id="current_upcs" class="badge badge-light">0</span> </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-inventario" id="inventario-tab" aria-controls="inventario" aria-expanded="true">Inventario</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-planificacion" id="planificacion-tab" aria-controls="planificacion" aria-expanded="true">Planificacion</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-especificaciones" id="especificaciones-tab" aria-controls="especificaciones" aria-expanded="true">Especificaciones</a>
					</li>
				</ul>
            </div>
            
        <!-- Content Panel -->
        <div class="card-body">
            <div id="clothing-nav-content" class="tab-content">
                <div role="tabpanel" class="tab-pane fade show active" id="tab-general" aria-labelledby="general-tab">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="form-group">
										{{ Form::cSelect('* Impuesto', 'fk_id_impuesto', $impuesto ?? [],['class'=>'select2']) }}
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="form-group">
										{{ Form::cSelect('* Subgrupo', 'fk_id_subgrupo', $subgrupo ?? [],['class'=>'select2']) }}
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="form-group">
										{{ Form::cSelect('Familia', 'fk_id_familia', $familia ?? [],['class'=>'select2']) }}
									</div>
								</div>
								<div class="col-sm-12 col-md-12 row">
									<div class="col-sm-12 col-md-4 text-center">
										<div class="form-group">
											{{ Form::cCheckboxBtn('Estatus', 'Activo', 'activo', $data['activo'] ?? null, 'Inactivo') }}
										</div>
									</div>
									<div class="col-sm-6 col-md-4">
										<div class="form-group">
											{{ Form::cText('Desde', 'activo_desde') }}
										</div>
									</div>
									<div class="col-sm-6 col-md-4">
										<div class="form-group">
											{{ Form::cText('Hasta', 'activo_hasta') }}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6">
							<div class="row">
								<div class="col-sm-12">
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
																{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_presentaciones]',$detalle->fk_id_presentaciones) }}
																{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_sal]',$detalle->fk_id_sal) }}
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
								</div>
							</div>
						</div>
					</div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-upcs" aria-labelledby="upcs-tab">
                	<div class="row">
						<div class="card-body">
							<table id="upcs" class="table table-responsive-sm highlight mt-3">
								<thead>
									<tr>
										<th>Upc</th>
										<th>Nombre Comercial</th>
										<th>Descripcion</th>
										<th>Laboratorio</th>
										<th>Precio</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
								@if(isset($data->upcs)) 
									@foreach($data->upcs as $key=>$detalle)
									<tr>
										<td>{!! Form::hidden('detalles['.$key.'][fk_id_upc]',$detalle->id_upc,['class'=>'id_upc']) !!} {{$detalle->upc}}</td>
										<td>{{$detalle->nombre_comercial ?? ''}}</td>
										<td>{{$detalle->descripcion ?? ''}}</td>
										<td>{{$detalle->laboratorio->laboratorio ?? ''}}</td>
										<td>
											{{$cantidad = $detalle->pivot->where('fk_id_upc',$detalle->id_upc)->where('fk_id_sku',$data->id_sku)->first()->cantidad ?? '0'}}
											{!! Form::hidden('detalles['.$key.'][cantidad]',$cantidad) !!} 
										</td>
										<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button></td>
									</tr>
									@endforeach
								@endif
								</tbody>
							</table>
						</div>
            		</div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-inventario" aria-labelledby="inventario-tab">
                	<div class="row">
        	  			<div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-2">
                    		{{ Form::cNumber('Necesario', 'necesario',['placeholder'=>'Ejm: 40']) }}
                    	</div>
                    	<div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-2">
                    		{{ Form::cNumber('Minimo', 'minimo',['placeholder'=>'Ejm: 10']) }}
                    	</div>
        	  			<div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-2">
                    		{{ Form::cNumber('Maximo', 'maximo',['placeholder'=>'Ejm: 90']) }}
                    	</div>
                    	<div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-2">
                			{{ Form::cSelect('Metodo Valoracion', 'fk_id_metodo_valoracion', $metodovaloracion ?? [],['class'=>'select2']) }}
                		</div>
        	  		</div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-planificacion" aria-labelledby="planificacion-tab">
                	<div class="row">
        	  			<div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-2">
                			{{ Form::cNumber('Punto Reorden', 'punto_reorden',['placeholder'=>'Ejm: 15']) }}
                		</div>
                		<div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-3">
                			{{ Form::cSelect('Intervalo del periodo', 'fk_id_intervalo', $periodos ?? [],['class'=>'select2']) }}
                		</div>
                		<div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-3">
                			{{ Form::cNumber('Cantidad minima por periodo', 'minima_periodo',['placeholder'=>'Ejm: 5']) }}
                		</div>
        	  			<div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-3">
                    		{{ Form::cNumber('Tiempo lead (Días)', 'tiempo_lead',['placeholder'=>'Ejm: 7']) }}
                    	</div>
                    	<div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-2">
                    		{{ Form::cNumber('Días de tolerancia', 'dias_tolerancia',['placeholder'=>'Ejm: 3']) }}
                    	</div>
        	  		</div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-especificaciones" aria-labelledby="especificaciones-tab">
                	<div class="row">
            			<div class="col-md-12 col-lg-6 form-group">
                    		{{ Form::cTextArea('Descripcion', 'descripcion') }}
                    	</div>
                    	<div class="col-md-12 col-lg-6 form-group">
                    		{{ Form::cTextArea('Descripcion de Cenefas', 'descripcion_cenefas') }}
                    	</div>
                    	<div class="col-md-12 col-lg-6 form-group">
                    		{{ Form::cTextArea('Descripcion en Ticket', 'descripcion_ticket') }}
                    	</div>
                    	<div class="col-md-12 col-lg-6 form-group">
                    		{{ Form::cTextArea('Descripcion en Rack', 'descripcion_rack') }}
                    	</div>
                    	<div class="col-md-12 col-lg-6 form-group">
                    		{{ Form::cTextArea('Descripcion Cuadro Basico Nacional', 'descripcion_cbn') }}
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