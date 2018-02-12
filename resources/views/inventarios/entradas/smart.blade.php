@extends(smart())
@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.date.css') }}">
@endsection
@section('content-width', 's12')

@section('header-bottom')
	@parent

	<script type="text/javascript" src="{{ asset('js/pickadate/picker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/pickadate/picker.date.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/pickadate/translations/es_Es.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/toaster.js') }}"></script>
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	@if (!Route::currentRouteNamed(currentRouteName('index'))) )
		<script type="text/javascript" src="{{ asset('js/entradas.js') }}"></script>
		{{--@endif--}}
	@endif
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	@if (Route::currentRouteNamed(currentRouteName('create')) || Route::currentRouteNamed(currentRouteName('show')) )
		<div class="row">
			<div class="col-sm-12">
				<div class="tab-content">
					<div class="tab-pane active" id="scanner" role="tabpanel">
						<div class="row">
							<form href="javascript:void(0)" onsubmit="return agregarEntrada();">
								<div class="col-12 col-md-6 col-lg-4">
									<div class="form-group">
										{{ Form::cSelect('* Tipo de documento', 'fk_id_tipo_documento', $tipo_documento ?? []) }}
									</div>
								</div>
								<div class="col-12 col-md-6 col-lg-4">
									@if(Route::currentRouteNamed(currentRouteName('create')))
										<div class="form-group">
											{!! Form::cText('* Entradas a escanear','entrada_escaner',['placeholder'=>'Codigo de la entrada a escanear.','data-url'=>companyRoute('getDetalleEntrada')]) !!}
										</div>
									@endif
								</div>
							</form>
						</div><!--/row forms-->
					</div>
				</div><!--/row-->
				@if(Route::currentRouteNamed(currentRouteName('create')))
					<ul class="nav nav-pills justify-content-center" role="tablist" id="lista_entradas"></ul>
					<div class="tab-content mt-2" id="detalle_entrada"></div>
				@endif
				@if(Route::currentRouteNamed(currentRouteName('show')))
					{{--{{dd($datos_documento)}}--}}
					<div role="tabpanel" class="tab-pane " >
						<div class="card z-depth-1-half">
							<div class="card-body">
								<h3 class="text-center">Entrada:</h3>
								<div class="row">
									<div class="col-md-6 col-sm-6 col-lg-3">
										<div class="form-group">
											{{ Form::cText('Sucursal', 'nombre_sucursal') }}
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-lg-3">
										<div class="form-group">
											{{ Form::cText('Proveedor', 'nombre_proveedor') }}
										</div>
									</div>
									@if(Route::currentRouteNamed(currentRouteName('show')))
										<div class="col-md-6 col-sm-6 col-lg-3">
											<div class="form-group">
												{{--{{dd($data)}}--}}
												{{ Form::cText('No. de entrada', 'id_entrada_almacen') }}
											</div>
										</div>
									@endif
									<div class="col-md-6 col-sm-6 col-lg-3">
										<div class="form-group">
											{{ Form::cText('Documento de referencia', 'referencia_documento') }}
										</div>
									</div>
									@if(Route::currentRouteNamed(currentRouteName('create')))
										<div class="text-right d-flex ">
											<button type="button" class="btn btn-primary" id="guardar_entrada"  >Guardar</button>
										</div>
									@endif
									<div class="col-12">
										<h4 class="text-center">Detalle de la entrada</h4>

										<table class="table table-hover table-responsive-sm" name="table2">
											<thead>
											<tr>
												<th>Sku</th>
												<th>Upc</th>
												<th>Descripción</th>
												<th>Cliente</th>
												<th>Proyecto</th>
												<th>Lote</th>
												<th>F. Caducidad</th>
												<th>C. Entrada</th>
												<th>C. Surtida</th>
												<th></th>
											</tr>
											</thead>
											<tbody>

											{{--{{dump($detalle_entrada)}}--}}
											{{--{{dd($productos_entrada)}}--}}
											@foreach($detalle_entrada as $detalle_producto)
												<tr>
													<td>{{$detalle_producto['sku']}}</td>
													<td>{{$detalle_producto['upc']}}</td>
													<td>{{$detalle_producto['sku_descripcion']}}</td>
													<td>{{$detalle_producto['nombre_cliente']}}</td>
													<td>{{$detalle_producto['nombre_proyecto']}}</td>
													<td>{{$detalle_producto['lote']}}</td>
													<td>{{$detalle_producto['fecha_caducidad']}}</td>
													<td>{{$detalle_producto['cantidad']}}</td>
													<td>{{$detalle_producto['cantidad_surtida']}}</td>
													<td></td>
												</tr>
											@endforeach

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	@endif
@endsection
