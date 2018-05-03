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
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 align="center" class="modal-title text-danger"><i class="material-icons">warning</i>¡Advertencia!</h5>
					<button type="button" class="close cancelar_cambio" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<p class="modal-body">
					<p class="text-center">Recuerda que al cambiar alguno de los siguientes campos, se eliminarán los productos asociados.</p>
					<ul>
						<li class="list-group-item forma_farmaceutica_modal">Forma Farmacéutica</li>
						<li class="list-group-item presentacion_modal">Presentación</li>
						<li class="list-group-item sales_modal">Tabla de sales</li>
					</ul>
					<h6 class="text-info">*El elemento que cambió está seleccionado de color azul.</h6>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="aceptar_cambio">Aceptar</button>
					<button type="button" class="btn btn-secondary cancelar_cambio" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
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
					<div class="form-group col-md-6">
						{{Form::cText('* Descripcion','descripcion')}}
					</div>
					<div  class="col-md-6 text-center mt-4">
						{{ Form::cCheckboxBtn('Dentro de Cuadro','Si','pertenece_cuadro', $data['activo'] ?? null, 'No') }}
					</div>
					<div class="col-sm-6 col-md-4">
						{{ Form::cSelect('* Forma farmacéutica','fk_id_forma_farmaceutica',$formafarmaceutica ?? [],['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2': '','data-old'=>'']) }}
					</div>
					<div class="col-sm-6 col-md-4">
						{{ Form::cSelect('* Presentación','fk_id_presentacion', $presentaciones ?? [],['style' => 'width:100%;','class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '','data-old'=>'']) }}
					</div>
					<div class="form-group col-md-4">
						{{Form::cSelectWithDisabled('* Unidad Medida','fk_id_unidad_medida',$unidadesmedidas ?? [],['class'=>''])}}
					</div>
					<div class="form-group col-md-6">
						{{Form::cSelect('* Clave Producto SAT','fk_id_clave_producto_servicio',$clavesproductosservicios ?? [],['class'=>'select2','data-url'=>ApiAction('administracion.clavesproductosservicios')])}}
					</div>
					<div class="form-group col-md-6">
						{{Form::cSelectWithDisabled('* Clave Unidad Medida','fk_id_clave_unidad',$clavesunidades ?? [],['class'=>'select2','data-url'=>ApiAction('administracion.clavesunidades')])}}
					</div>
					<div class="form-group col-md-4">
						{{Form::cNumber('* Precio','precio')}}
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
				<h1 class="text-center text-info">Sales</h1>
			</div>
			<div class="col-sm-12 col-md-12">
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
							@foreach($data->concentraciones as $row => $detalle)
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
		<div class="w-100 card">
			<div class="card-header">
				<h1 class="text-info text-center">Productos</h1>
			</div>
			<div class="card-body" id="productos">
				<div class="col-md-12">
					<ul class="nav nav-tabs" id="skus" role="tablist">
						{{--<li class="nav-item">--}}
							{{--<a class="nav-link active" id="sku_1_tab" data-toggle="tab" href="#sku_1" role="tab" aria-controls="sku_1" aria-selected="true">SKU 1</a>--}}
						{{--</li>--}}
						{{--<li class="nav-item">--}}
							{{--<a class="nav-link" id="sku_2_tab" data-toggle="tab" href="#sku_2" role="tab" aria-controls="sku_2" aria-selected="false">SKU 2</a>--}}
						{{--</li>--}}
					</ul>
					<div class="tab-content" id="upcs">
						{{--<div class="tab-pane fade active" id="sku_1" aria-labelledby="sku_1_tab" role="tabpanel">--}}
							{{--<div>AAAAAAAAAA</div>--}}
						{{--</div>--}}
						{{--<div class="tab-pane fade" id="sku_2" aria-labelledby="sku_2_tab" role="tabpanel">--}}
							{{--<div>BBBBBBBBBB</div>--}}
						{{--</div>--}}
					</div>
				</div>
			</div>
		</div>
    </div>
@endsection