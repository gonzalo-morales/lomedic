@section('header-top')
	{{ HTML::style(asset('vendor/vanilla-dataTables/vanilla-dataTables.css')) }}
@endsection

@section('content-width', 'w-100')

@section('form-content')
{{ Form::setModel($data) }}
	<div class="row">
		<div class="form-group col-md-6 col-xs-12">
			{{Form::cText('Proyecto','proyecto',['id'=>'proyecto','data-url'=>companyAction('Proyectos\ProyectosController@obtenerProyectosCliente',['id'=>'?id'])])}}
		</div>
		<div class="form-group col-md-6 col-xs-12">
			{{Form::cSelectWithDisabled('Cliente','fk_id_cliente',isset($clientes)?$clientes:[])}}
			{{--{{Form::cSelectWithDisabled('Cliente','fk_id_cliente',isset($clientes)?$clientes:[],['id'=>'fk_id_cliente'])}}--}}
			{{--{{Form::label('fk_id_cliente','Cliente')}}--}}
			{{--{{Form::select('fk_id_cliente',isset($clientes)?$clientes->prepend('...',null):[],null,['id'=>'fk_id_cliente','class'=>'form-control'])}}--}}
		</div>
		<div class="form-group col-md-4 col-xs-12">
			{{Form::label('fecha_contrato','Fecha de creación del contrato')}}
			{{Form::text('fecha_contrato',null,['id'=>'fecha_contrato',"class"=>'form-control datepicker'])}}
		</div>
		<div class="form-group col-md-4 col-xs-12">
			{{Form::label('fecha_inicio_contrato','Fecha inicio del contrato')}}
			{{Form::text('fecha_inicio_contrato',null,['id'=>'fecha_inicio_contrato',"class"=>'form-control datepicker'])}}
		</div>
		<div class="form-group col-md-4 col-xs-12">
			{{Form::label('fecha_fin_contrato','Fecha fin del contrato')}}
			{{Form::text('fecha_fin_contrato',null,['id'=>'fecha_fin_contrato',"class"=>'form-control datepicker'])}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::cText('Número de contrato','numero_contrato')}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::label('monto_adjudicado','Monto adjudicado')}}
			{{Form::text('monto_adjudicado',isset($data->monto_adjudicado)?number_format($data->monto_adjudicado,2,'.',''):null,['id'=>'monto_adjudicado','class'=>'form-control'])}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::cSelectWithDisabled('Clasificación','fk_id_clasificacion_proyecto',isset($clasificaciones)?$clasificaciones:[])}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::cText('Plazo','plazo')}}
		</div>
		<div class="form-group col-md-6 col-xs-12">
			{{Form::cText('Representante legal','representante_legal')}}
		</div>
		<div class="form-group col-md-5 col-xs-12">
			{{Form::cText('Número de fianza','numero_fianza')}}
		</div>
		<div class="form-group col-md-1 col-xs-12">
			<div data-toggle="buttons">
				<label class="btn btn-secondary form-check-label {{ !empty($data->activo) || old('activo') ? 'active':''}}">
					{{Form::checkbox('activo',true,old('activo'),['id'=>'activo',Route::currentRouteNamed(currentRouteName('show'))?'disabled':''])}}
					Activo
				</label>
			</div>
		</div>
	</div>

<div class="row">
	<div id="detallesku" class="container-fluid">
		<div class="card text-center z-depth-1-half" style="min-height: 555px">
			<ul id="clothing-nav" class="nav nav-pills nav-justified card-header pb-0" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" role="tab" data-toggle="tab" href="#tab-productosProyectos" id="productosProyectos-tab" aria-controls="productosProyectos" aria-expanded="true">Productos-Proyectos</a>
				</li>
			</ul>
			<!-- Content Panel -->
			<div id="clothing-nav-content" class="card-body tab-content">
				<div role="tabpanel" class="tab-pane fade show active" id="tab-productosProyectos" aria-labelledby="productosProyectos-tab">
					<div class="card">
						@if(!Route::currentRouteNamed(currentRouteName('show')))
							<div class="card-header">
								<fieldset name="detalle-form" id="detalle-form">
									<div class="row">
										<div class="form-group input-field col-md-6 col-sm-6">
											{{Form::label('fk_id_clave_cliente_producto','* Clave cliente producto')}}
											{!!Form::select('fk_id_clave_cliente_producto',[],null,['id'=>'fk_id_clave_cliente_producto','disabled','class'=>'form-control','style'=>'width:100%','data-url'=>companyAction('Proyectos\ClaveClienteProductosController@obtenerClavesCliente',['id'=>'?id'])])!!}
											{{--								{{Form::cSelect('Clave cliente producto','fk_id_clave_cliente_producto',[],['disabled'])}}--}}
										</div>
										<div class="form-group input-field col-md-6 col-sm-6">
											{{Form::label('fk_id_upc','UPC')}}
											<div class="input-group">
												<span class="input-group-addon">
													<input type="checkbox" id="activo_upc">
												</span>
												{!! Form::select('fk_id_upc',[],null,['id'=>'fk_id_upc','disabled',
                                                'data-url'=>companyAction('Inventarios\ProductosController@obtenerUpcs',['id'=>'?id']),
                                                'class'=>'form-control','style'=>'width:100%']) !!}
											</div>
										</div>
									</div>
									<div>
										<div class="col-sm-12 text-center border">
											<div class="sep">
												<div class="sepText bg-light">
													ó
												</div>
											</div>
										</div>
										<p class="text-center mt-2">Puedes descargar un layout e importar el excel</p>
									</div>
									<div class="row mt-3">
										<div class="form-goup col-md-6 text-center">
											<a href="{{companyAction('layoutProtudctosProyecto')}}" id="layout" class="btn btn-primary">Descargar Layout</a>
{{--												{!! Form::button('Descargar Layout',['id'=>'layout','class'=>'btn btn-primary','name'=>'layout','href'=>])!!}--}}
											</div>
											<div class="form-goup col-md-6">
												<label class="custom-file">
													<input type="file" id="file_csv" name="file_csv" data-url="{{companyAction('loadLayoutProductosProyectos')}}">
													<span class="custom-file-control"></span>
												</label>
											</div>
											<div class="col-sm-12 text-center">
												<div class="sep">
													<div class="sepBtn">
														<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large tooltipped "
																data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" id="agregar">
															<i class="material-icons">add</i>
														</button>
													</div>
												</div>
											</div>
									</div>
								</fieldset>
							</div>
						@endif
						<div class="card-body">
							<table id="productosproyectos" class="table-responsive highlight"
								   @if(isset($data->ProyectosProductos))
								   data-delete="{{companyAction('Proyectos\ProyectosProductosController@destroy')}}"
									@endif
							>
								<thead>
								<tr>
									<th>Clave cliente producto</th>
									<th>Descripción clave</th>
									<th>UPC</th>
									<th>Descripción UPC</th>
									<th>Prioridad</th>
									<th>Cantidad</th>
									<th>Precio sugerido</th>
									<th>Estatus</th>
									<th></th>
								</tr>
								</thead>
								<tbody id="tbodyproductosproyectos">
								@if( isset( $data->ProyectosProductos ) )
									@foreach( $data->ProyectosProductos->where('eliminar',false) as $ProyectoProducto )
										<tr id="{{$ProyectoProducto->id_proyecto_producto}}">
											<td>
												{!! Form::hidden('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][id_proyecto_producto]',$ProyectoProducto->id_proyecto_producto) !!}
												{{$ProyectoProducto->claveClienteProducto->clave_producto_cliente}}
											</td>
											<td>
												{{$ProyectoProducto->claveClienteProducto->sku->descripcion_corta}}
											</td>
											<td>
												{{isset($ProyectoProducto->upc)?$ProyectoProducto->upc->upc:'Sin UPC'}}
											</td>
											<td>
												{{isset($ProyectoProducto->upc)?$ProyectoProducto->upc->descripcion:''}}
											</td>
											<td>
												{!!
													Form::text('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][prioridad]',
													$ProyectoProducto->prioridad,
													['class'=>'form-control prioridad'])
												!!}
											</td>
											<td>
												{!!
													Form::text('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][cantidad]',
													$ProyectoProducto->cantidad,
													['class'=>'form-control cantidad'])
												!!}
											</td>
											<td>
												{!!
													Form::text('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][precio_sugerido]',
													bcdiv($ProyectoProducto->precio_sugerido,'1',2),
													['class'=>'form-control precio_sugerido'])
												!!}
											</td>
											<td>
												{!! Form::cCheckbox('','productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][activo]',[!empty($ProyectoProducto->activo)?'checked':'']) !!}
											</td>
											<td>
												{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
												@if(Route::currentRouteNamed(currentRouteName('edit')))
													<button class="btn is-icon text-primary bg-white "
															type="button" data-item-id="{{$ProyectoProducto->id_proyecto_producto}}"
															id="{{$ProyectoProducto->id_proyecto_producto}}" data-delay="50"
															onclick="borrarFilaProyectoProducto_edit(this)" data-delete-type="single">
														<i class="material-icons">delete</i></button>
												@endif
											</td>
										</tr>
									@endforeach
								@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- End Content Panel -->
		</div>
	</div>
</div>

@section('header-bottom')
	@parent
	{{ HTML::script(asset('vendor/vanilla-datatables/vanilla-dataTables.js')) }}
	{{ HTML::script(asset('js/proyectos.js')) }}
	{{ HTML::script(asset('js/maestro_materiales.js')) }}
@endsection

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

@if (Route::currentRouteNamed(currentRouteName('export')))
	@include('layouts.smart.export')
@endif
