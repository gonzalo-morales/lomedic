@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.date.css') }}">
@endsection
@section('header-bottom')
	@parent

	<script type="text/javascript" src="{{ asset('js/pickadate/picker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/pickadate/picker.date.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/pickadate/translations/es_Es.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/toaster.js') }}"></script>
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	{{--@if (!Route::currentRouteNamed(currentRouteName('index')))--}}
	<script type="text/javascript" src="{{ asset('js/entradas.js') }}"></script>
	{{--@endif--}}

	<script type="text/javascript">

	</script>
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}

@if (Route::currentRouteNamed(currentRouteName('create')) || Route::currentRouteNamed(currentRouteName('show')) )
	<div class="row">
		<div class="col-sm-12">
			<div class="card z-depth-1-half">
				<div class="card-header">
					<div class="row">
						<div class="col-12 mb-3">
							<div class="tab-content">
								<div class="tab-pane active" id="scanner" role="tabpanel">
									<div class="row">
										<form href="javascript:void(0)" onsubmit="return agregarEntrada();">
										<div class="col-12 col-md-6 col-lg-4">

												<div class="form-group">
													{{ Form::cSelect('Tipó de documento', 'fk_id_tipo_documento', $tipo_documento ?? []) }}
												</div>
										</div>
											<div class="col-12 col-md-6 col-lg-4">
												@if(Route::currentRouteNamed(currentRouteName('create')))
													<div class="form-group">
													{{ Form::label('entrada_escaner', 'Entradas a escanear') }}
													{!! Form::text('entrada_escaner',null,['id'=>'entrada_escaner','class'=>'form-control','placeholder'=>'Codigo de la entrada a escanear.','data-url'=>companyRoute('getDetalleEntrada')]) !!}
													</div>
												@endif

										</div>
										</form>
									</div><!--/row forms-->
								</div>
							</div><!--/row-->
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						@if(Route::currentRouteNamed(currentRouteName('create')))
							<div class="col-12">
								<ul class="nav nav-tabs" role="tablist" id="lista_entradas"></ul>
								<div class="tab-content" id="detalle_entrada"></div>
							</div>
						@endif
						@if(Route::currentRouteNamed(currentRouteName('show')))

							<div role="tabpanel" class="tab-pane " >
								<div class="row">
									<div class="col-sm-12">
										<h3>Entrada:</h3>
										<div class="card z-depth-1-half">
											<div class="card-body">
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
														<h3>Detalle de la entrada</h3>

														<table class="table table-hover table-responsive" name="table2">
															<thead>
																<tr>
																	<th>Sku</th>
																	<th>Upc</th>
																	<th>Descripción</th>
																	<th>Cliente</th>
																	<th>Proyecto</th>
																	<th>Lote</th>
																	<th>C. Entrada</th>
																	<th>C. Surtida</th>
																	<th></th>
																</tr>
															</thead>
															<tbody>
																{{--{{dd($productos_entrada)}}--}}
																@foreach($productos_entrada as $producto)
																	<tr>
																		<td>{{$producto->sku}}</td>
																		<td>{{$producto->upc}}</td>
																		<td>{{$producto->descripcion}}</td>
																		<td>{{$producto->fk_id_cliente}}</td>
																		<td>{{$producto->fk_id_proyecto}}</td>
																		<td></td>
																		<td>{{$producto->cantidad}}</td>
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
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endif

@endsection





{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	{{--@section('smart-js')--}}
		{{--<script type="text/javascript">--}}
            {{--if ( sessionStorage.reloadAfterPageLoad ) {--}}
                {{--sessionStorage.clear();--}}
                {{--$.toaster({--}}
                    {{--priority: 'success', title: 'Exito', message: 'Orden cancelada',--}}
                    {{--settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}--}}
                {{--});--}}
            {{--}--}}
		{{--</script>--}}
		{{--@parent--}}
		{{--<script type="text/javascript">--}}
			{{--rivets.binders['hide-delete'] = {--}}
				{{--bind: function (el) {--}}
					{{--if(el.dataset.fk_id_estatus_orden != 1)--}}
					{{--{--}}
						{{--$(el).hide();--}}
					{{--}--}}
				{{--}--}}
			{{--};--}}
			{{--rivets.binders['hide-update'] = {--}}
				{{--bind: function (el) {--}}
					{{--if(el.dataset.fk_id_estatus_orden != 1)--}}
					{{--{--}}
						{{--$(el).hide();--}}
					{{--}--}}
				{{--}--}}
			{{--};--}}
			{{--@can('update', currentEntity())--}}
				{{--window['smart-model'].collections.itemsOptions.edit = {a: {--}}
				{{--'html': '<i class="material-icons">mode_edit</i>',--}}
				{{--'class': 'btn is-icon',--}}
				{{--'rv-get-edit-url': '',--}}
				{{--'rv-hide-update':''--}}
			{{--}};--}}
			{{--@endcan--}}
			{{--@can('delete', currentEntity())--}}
				{{--window['smart-model'].collections.itemsOptions.delete = {a: {--}}
				{{--'html': '<i class="material-icons">not_interested</i>',--}}
				{{--'href' : '#',--}}
				{{--'class': 'btn is-icon',--}}
				{{--'rv-on-click': 'actions.showModalCancelar',--}}
				{{--'rv-get-delete-url': '',--}}
				{{--'data-delete-type': 'single',--}}
				{{--'rv-hide-delete':''--}}
			{{--}};--}}
			{{--@endcan--}}
				{{--window['smart-model'].actions.itemsCancel = function(e, rv, motivo){--}}
				{{--if(!motivo.motivo_cancelacion){--}}
					{{--$.toaster({--}}
						{{--priority : 'danger',--}}
						{{--title : '¡Error!',--}}
						{{--message : 'Por favor escribe un motivo por el que se está cancelando esta orden de compra',--}}
						{{--settings:{--}}
							{{--'timeout':10000,--}}
							{{--'toaster':{--}}
								{{--'css':{--}}
									{{--'top':'5em'--}}
								{{--}--}}
							{{--}--}}
						{{--}--}}
					{{--});--}}
				{{--}else{--}}
					{{--let data = {motivo};--}}
					{{--$.delete(this.dataset.deleteUrl,data,function (response) {--}}
						{{--if(response.success){--}}
							{{--sessionStorage.reloadAfterPageLoad = true;--}}
							{{--location.reload();--}}
						{{--}--}}
					{{--})--}}
				{{--}--}}
			{{--};--}}
			{{--window['smart-model'].actions.showModalCancelar = function(e, rv) {--}}
				{{--e.preventDefault();--}}

				{{--let modal = window['smart-modal'];--}}
				{{--modal.view = rivets.bind(modal, {--}}
					{{--title: '¿Estas seguro que deseas cancelar la orden?',--}}
					{{--content: '<form  id="cancel-form">' +--}}
					{{--'<div class="form-group">' +--}}
					{{--'<label for="recipient-name" class="form-control-label">Motivo de cancelación:</label>' +--}}
					{{--'<input type="text" class="form-control" id="motivo_cancelacion" name="motivo_cancelacion">' +--}}
					{{--'</div>' +--}}
					{{--'</form>',--}}
					{{--buttons: [--}}
						{{--{button: {--}}
							{{--'text': 'Cerrar',--}}
							{{--'class': 'btn btn-secondary',--}}
							{{--'data-dismiss': 'modal',--}}
						{{--}},--}}
						{{--{button: {--}}
							{{--'html': 'Cancelar',--}}
							{{--'class': 'btn btn-danger',--}}
							{{--'rv-on-click': 'action',--}}
						{{--}}--}}
					{{--],--}}
					{{--action: function(e,rv) {--}}
						{{--var formData = new FormData(document.querySelector('#cancel-form')), convertedJSON = {}, it = formData.entries(), n;--}}

						{{--while(n = it.next()) {--}}
							{{--if(!n || n.done) break;--}}
							{{--convertedJSON[n.value[0]] = n.value[1];--}}
						{{--}--}}
						{{--console.log(convertedJSON);--}}
						{{--window['smart-model'].actions.itemsCancel.call(this, e, rv,convertedJSON);--}}
					{{--}.bind(this),--}}
					{{--// Opcionales--}}
					{{--onModalShow: function() {--}}

						{{--let btn = modal.querySelector('[rv-on-click="action"]');--}}

						{{--// Copiamos data a boton de modal--}}
						{{--for (var i in this.dataset) btn.dataset[i] = this.dataset[i];--}}

					{{--}.bind(this),--}}
					{{--// onModalHide: function() {}--}}
				{{--});--}}
				{{--// Abrimos modal--}}
				{{--$(modal).modal('show');--}}
			{{--};--}}
		{{--</script>--}}
	{{--@endsection--}}
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('extraButtons')
		@parent
		{!!isset($data->id_orden) ? HTML::decode(link_to(companyAction('impress',['id'=>$data->id_orden]), '<i class="material-icons">print</i> Imprimir', ['class'=>'btn btn-default imprimir'])) : ''!!}
	@endsection
	@include('layouts.smart.show')
@endif

{{--@if (currentRouteName('createSolicitudOrden'))--}}
	{{--@include('layouts.smart.create')--}}
{{--@endif--}}