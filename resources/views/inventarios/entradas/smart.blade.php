@extends(smart())
@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.date.css') }}">
@endsection


@section('form-actions')
	<div class="col-md-12">
		<div class="text-right">
			@yield('left-actions')
			{{ Form::button(cTrans('forms.save','Guardar'), ['type'=>'button','id'=>'guardar','class'=>'btn btn-primary progress-button']) }}
			{{ link_to(companyRoute('index'), cTrans('forms.close','Cerrar'), ['class'=>'btn btn-default progress-button']) }}
			@yield('right-actions')
		</div>
	</div>
@endsection



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
	{{--{{dd($data)}}--}}
	@if (Route::currentRouteNamed(currentRouteName('create')) || Route::currentRouteNamed(currentRouteName('show')) )
			<div class="card z-depth-1-half">
				<div class="card-body">
					<div class="col-sm-12">
						<div class="row">
							<form href="javascript:void(0)">
							<div class="col-12 col-md-4 col-lg-2">
								<div class="form-group">
									{{ Form::cSelect('* Tipo de documento', 'fk_id_tipo_documento', $tipo_documento ?? [],['class'=>'select2','data-url'=>companyRoute('getDocumento')]) }}
								</div>
							</div>
							<div class="col-12 col-md-4 col-lg-2">
								{{--@if(Route::currentRouteNamed(currentRouteName('create')))--}}
									<div class="form-group">
										{{ Form::cSelect('* Numero de documento','numero_documento',$numero_documento ?? [],['class'=>'select2','data-url'=>companyRoute('getDetalleDocumento')]) }}
									</div>

							</div>
							<div class="col-md-6 col-sm-4 col-lg-3">
								<div class="form-group">
									{{ Form::cText('Sucursal', 'nombre_sucursal','disabled') }}
								</div>
							</div>
							<div class="col-md-6 col-sm-4 col-lg-3">
								<div class="form-group">
									{{ Form::cText('Proveedor', 'nombre_proveedor','disabled') }}
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-lg-2">
								<div class="form-group">
									{{ Form::cText('Documento de referencia', 'referencia_documento') }}
								</div>
							</div>

							</form>
						</div>
					</div><!--/row-->
				</div><!--/row-->
			</div><!--/row-->

			@if(Route::currentRouteNamed(currentRouteName('create')))
			<div class="card z-depth-1-half">

				<div class="card-body">
					{{--<h3 class="text-center">Entrada:</h3>--}}
					<nav>
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
							<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" onclick="activar_boton_agregar(false)">Codigo de barras</a>
							<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="activar_boton_agregar(true)">Teclado</a>
							{{--<a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</a>--}}
							{{--<input type="hidden" name="id_documento" id="id_documento" value="0">--}}
						</div>
					</nav>


					<div class="tab-content" id="nav-tabContent">
						<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
							<div class="row">
								<div class="col-md-2 col-sm-3 col-lg-2">
									<div class="form-group">
										{{ Form::cText('Lote', 'lote_cb') }}
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-lg-3">
									{{Form::cDate('Fecha de caducidad','fecha_caducidad_cb',['class'=>' datepicker'])}}
								</div>
								<div class="col-12 col-md-6 col-lg-4">
									<div class="form-group">
										{{ Form::cText('Codigo del producto', 'upcs_cb',['onchange'=>'agregar_info_upc(1);']) }}
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
							<div class="row">

								<div class="col-md-2 col-sm-3 col-lg-2">
									<div class="form-group">
										{{ Form::cText('Lote', 'lote') }}
									</div>
								</div>
								<div class="col-md-6 col-sm-3 col-lg-2">
									{{Form::cDate('Fecha de caducidad','fecha_caducidad',['class'=>' datepicker'])}}
								</div>
								<div class="col-12 col-md-6 col-lg-3">
									<div class="form-group">
										{{ Form::cSelect('UPC','upcs',[],['class'=>'select2','data-url'=>companyRoute('getDetalleDocumento')]) }}
									</div>
								</div>
								<div class="col-12 col-md-6 col-lg-2">
									<div class="form-group">
										{{ Form::cText('Cantidad', 'cantidad') }}
									</div>
								</div>
								<div class="col-12 col-md-6 col-lg-2">
									<div style=" display: flex;align-items: center;justify-content: center;margin-top: 23px;">
										<button type="button" class="btn btn-primary" id="agrgar_upc" onclick="agregar_info_upc(2);" disabled>Agregar</button>
									</div>
								</div>
							</div>

						</div>

					</div>

					<div class="row justify-content-center"></div>

				</div>
				{{--</div>--}}
			</div>
			@endif
			<div class="card z-depth-1-half">

				<div class="card-body">
					{{--<h3 class="text-center">Entrada:</h3>--}}
					{{--<div class="row">--}}

						{{--<div class="col-md-6 col-sm-6 col-lg-3">--}}
							{{--<div class="form-group">--}}
								{{--{{ Form::cText('Documento de referencia', 'referencia_documento') }}--}}
							{{--</div>--}}
						{{--</div>--}}

						{{--<div class="col-md-2 col-sm-3 col-lg-2">--}}
							{{--<div class="form-group">--}}
								{{--{{ Form::cText('Lote', 'lote') }}--}}
							{{--</div>--}}
						{{--</div>--}}
						{{--<div class="col-md-6 col-sm-6 col-lg-3">--}}
							{{--{{Form::cDate('Fecha de caducidad','fecha_caducidad',['class'=>' datepicker'])}}--}}
						{{--</div>--}}
						{{--<div class="col-12 col-md-6 col-lg-4">--}}
							{{--<div class="form-group">--}}
								{{--{{ Form::cText('Codigo del producto', 'codigo_upc') }}--}}
							{{--</div>--}}
						{{--</div>--}}

					{{--</div>--}}

					<div class="row justify-content-center">

					</div>
					<div class="row justify-content-center">
						<h4 class="text-center">Detalle de la entrada</h4>
						<table class="table table-hover table-responsive-sm" name="table2">
							<thead>
								<tr>
									{{--<th>Sku</th>--}}
									<th>Upc</th>
									<th>Descripción</th>
									<th>Cliente</th>
									<th>Proyecto</th>
									{{--<th>Lote</th>--}}
									{{--<th>F. Caducidad</th>--}}
									<th>C. Entrada</th>
									<th>C. Surtida</th>
									{{--<th>Escaneado</th>--}}
									<th>Costo Unitario</th>
									<th>Total</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="table_detalle">
							</tbody>
						</table>
					</div>
				</div>
				{{--</div>--}}
			</div>

	@endif
@endsection
