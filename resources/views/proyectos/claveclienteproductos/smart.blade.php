@extends(smart())

@section('header-bottom')
	@parent
	{{ HTML::script(asset('js/clave_cliente_producto.js')) }}
	<script type="text/javascript">
        var upcs_js = '{{$js_upcs ?? ''}}';
        var cantidad_upc_js = '{{$js_cantidad_upc ?? ''}}';
        var clave_producto_servicio_js = '{{$js_clave_producto_servicio ?? ''}}';
        var clave_unidad_js = '{{$js_clave_unidad ?? ''}}';
	</script>
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
    <div class="row">
		<div class="w-50 card container-fluid z-depth-1-half mt-2 px-0">
			<div class="card-header">
				<h1 class="text-center text-info">Generales</h1>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-12">
						{{Form::cSelectWithDisabled('* Cliente','fk_id_cliente',$clientes ?? [],['class'=>'select2'])}}
					</div>
					<div class="form-group col-md-6">
						{{Form::cText('* Clave','clave_producto_cliente')}}
					</div>
					<div class="form-group col-md-6">
						{{Form::cText('* Subclave','subclave')}}
					</div>
					<div class="form-group col-md-12">
						{{Form::cText('* Descripcion','descripcion')}}
					</div>
					<div  class="col-md-4 text-center mt-4">
						{{ Form::cCheckboxBtn('Dentro de Cuadro','Si','pertenece_cuadro', $data['pertenece_cuadro'] ?? null, 'No') }}
					</div>
					<div  class="col-md-4 text-center mt-4">
						{{ Form::cCheckboxBtn('Fraccionado','Si','fraccionado', $data['fraccionado'] ?? null, 'No') }}
					</div>
					<div  class="col-md-4 text-center mt-4">
						{{Form::cSelectWithDisabled('* Subgrupo','fk_id_subgrupo',$subgrupo ?? [],[],$subgrupo_data ?? [])}}
					</div>
					<div class="col-sm-6 col-md-6">
						{{ Form::cSelect('* Forma farmacéutica','fk_id_forma_farmaceutica',$formafarmaceutica ?? [],['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '']) }}
					</div>
					<div class="col-sm-6 col-md-6">
						{{ Form::cSelect('* Presentación','fk_id_presentacion', $presentaciones ?? [],['style' => 'width:100%;','class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',Route::currentRouteNamed(currentRouteName('create')) ? 'disabled' : '' ]) }}
					</div>
					<div class="form-group col-md-6">
						{{Form::cSelect('* Clave Producto SAT','fk_id_clave_producto_servicio',$clavesproductosservicios ?? [],['class'=>'select2','data-url'=>ApiAction('administracion.clavesproductosservicios')])}}
					</div>
					<div class="form-group col-md-6">
						{{Form::cSelectWithDisabled('* Clave Unidad Medida','fk_id_clave_unidad',$clavesunidades ?? [],['class'=>'select2','data-url'=>ApiAction('administracion.clavesunidades')])}}
					</div>
					<div class="form-group col-md-4">
						{{Form::cNumber('* Precio','precio',[],isset($data->precio) ? $data->precio : null,'4','.')}}
					</div>
					<div class="form-group col-md-4">
						{{Form::cNumber('* Tope Receta','tope_receta')}}
					</div>
					<div class="form-group col-md-4">
						{{Form::cSelectWithDisabled('* Impuesto','fk_id_impuesto',$impuestos ?? [],['class'=>'select2'])}}
					</div>
					<div  class="col-md-12 text-center mt-4">
						<div class="alert alert-warning" role="alert">
							Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrará en los modulos correspondientes que se requieran.
						</div>
						{{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
					</div>
				</div>
			</div>
		</div>
		<div class="w-50 card container-fluid z-depth-1-half mt-2 px-0">
			<div class="card-header">
				<h1 class="text-center text-info" id="titulo_detalle">Sales</h1>
			</div>
			<div id="tableSal" class="col-sm-12 col-md-12">
					<div class="card-header">
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
											<button id="addSal" style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
										</div>
									</div>
								</div>
							</fieldset>
					</div><!--/card-header-->
				<div class="card-body">
					<table class="table table-responsive-sm table-striped table-hover" width="100%">
						<thead>
						<tr>
							<th>Sal</th>
							<th>Concentración</th>
							<th></th>
						</tr>
						</thead>
						<tbody id="tbodySales">
						@if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
							@foreach($data->concentraciones ?? [] as $row => $detalle)
								<tr>
									<td>
										<input type="hidden" value="{{$detalle->id_detalle}}" name="relations[has][concentraciones][{{$row}}][id_detalle]">
										{{ Form::hidden('relations[has][concentraciones]['.$row.'][fk_id_clave_cliente_producto]',$detalle->fk_id_clave_cliente_producto) }}
										{{ Form::hidden('relations[has][concentraciones]['.$row.'][fk_id_concentracion]',$detalle->fk_id_concentracion) }}
										{{ Form::hidden('relations[has][concentraciones]['.$row.'][fk_id_sal]',$detalle->fk_id_sal) }}
										{{ $detalle->sal->nombre }}
									</td>
									<td>
										{{ $detalle->concentracion->cantidad.' '.$detalle->concentracion->unidad->clave }}
									</td>
                                    <td>
                                        <button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>
                                    </td>
								</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
			</div><!--/detalle-->
			<div id="tableEspecificaciones" class="col-md-12">
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
											<button id="addEspecificacion" style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
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
						<tbody id="tbodyEspecificaciones">
						@if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
							@foreach($data->especificaciones ?? [] as $row => $detalle)
								<tr>
									<td>
										{{ Form::hidden('especificaciones['.$row.'][fk_id_especificacion]',$detalle->fk_id_especificacion) }}
										{{ $detalle->especificacion }}
									</td>
                                    <td>
                                        <button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>
                                    </td>
								</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
			</div><!--/detalle-->
        </div>
        <div class="w-100 card">
            <div class="card-header">
                <h1 class="text-info text-center">Productos</h1>
                <div  class="col-md-12 text-center mt-4">
                    <div class="alert alert-warning" role="alert">
                        Recuerda que al cambiar <b>forma farmacéutica</b>, <b>presentación</b> o al <b>agregar una sal</b> se borrarán los productos actuales y se buscarán otros.
                    </div>
                </div>
            </div>
            <div class="card-body" id="productos" data-url="{{companyAction('Inventarios\ProductosController@getRelatedSkus')}}">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" id="skus" role="tablist">
                        @notroute(['index','create'])
                            @foreach($skus as $index=>$producto)
                                <li class="nav-item">
                                    <a class="nav-link {{$index == 0 ? 'active' : ''}}" id="sku_{{$index}}_tab" data-toggle="tab" href="#sku_{{$index}}" role="tab" aria-controls="sku_{{$index}}" aria-selected="{{$index == 0 ? 'true' : 'false'}}">{{$producto->sku}}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="tab-content" id="upcs">
                        @notroute(['index','create'])
                            @foreach($skus as $index=>$producto)
                                <div class="tab-pane fade {{$index==0 ? 'active show' : ''}}" id="sku_{{$index}}" aria-labelledby="sku_{{$index}}_tab" role="tabpanel">
                                    <table class="table table-responsive-sm table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>UPC</th>
                                                <th>Nombre Comercial</th>
                                                <th>Marca</th>
                                                <th>Descripcion</th>
                                                <th>Laboratorio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($producto->upcs as $indice=>$upc)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input name="productos[{{$indice}}][fk_id_upc]" type="hidden" value="0">
                                                        <label class="form-check-label custom-control custom-checkbox">
                                                            <input class="form-check-input custom-control-input" name="productos[{{$indice}}][fk_id_upc]" type="checkbox" value="{{$upc->id_upc}}" {{in_array($upc->id_upc,$data->productos->pluck('id_upc')->toArray()) ? 'checked' : ''}}>
                                                            <span class="custom-control-indicator"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>{{$upc->upc}}</td>
                                                <td>{{$upc->nombre_comercial}}</td>
                                                <td>{{$upc->marca}}</td>
                                                <td>{{$upc->descripcion}}</td>
                                                <td>{{$upc->laboratorio->laboratorio}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection