@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript">
			var sucursales_js = '{{$js_sucursales ?? ''}}';
			var orden_js = '{{$js_orden ?? ''}}';
		</script>
		{{HTML::script(asset('js/solicitudes_pagos.js'))}}
{{--		<script type="text/javascript" src="{{ asset('js/facturas_proveedores.js') }}"></script>--}}
	@endif
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
@if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
	<div class="row">
		<div class="col-md-12 text-center text-success">
			<h1>Factura No. {{$data->id_solicitud_pago}}</h1>
		</div>
	</div>
@endif
<div id="autorizacion" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Autorizar el pago?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					{{Form::hidden('',null,['id'=>'id_autorizacion','data-url'=>companyAction('Compras\AutorizacionesController@update',['id'=>'?id'])])}}
					<div class="form-group col-md-12 col-sm-6">
						{{Form::label('','Tipo de autorizacion')}}
						{{Form::text('',null,['class'=>'form-control','readonly','id'=>'motivo_autorizacion'])}}
					</div>
					<div class="form-group text-center col-md-12 col-sm-6">
						{{Form::cRadio('¿Autorizado?','fk_id_estatus',[4=>'Autorizado',3=>'No Autorizado'])}}
					</div>
					<div class="form-group col-md-12 col-sm-12">
						{{Form::cTextArea('Motivo','observaciones',['readonly','style'=>'resize:none;'])}}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				@if(Route::currentRouteNamed(currentRouteName('edit')))
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button id="guardar_autorizacion" type="button" class="btn btn-primary">Guardar</button>
				@endif
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="form-group col-md-6 col-sm-6">
		<div class="row">
			<div class="col-md-6 col-sm-6">
				{{Form::cSelectWithDisabled('*Solicitante','fk_id_solicitante',$solicitantes ?? [])}}
			</div>
			<div class="col-md-6 col-sm-6">
				<div id="loadingsucursales" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
					Cargando datos... <i class="material-icons align-middle loading">cached</i>
				</div>
				{{Form::cSelect('*Sucursal','fk_id_sucursal', $sucursales ?? [],['data-url'=>companyAction('HomeController@index').'/Administracion.sucursales/api'])}}
			</div>
			<div class="col-md-4 col-sm-6">
				{{Form::cText('*Fecha necesaria','fecha_necesaria',['class'=>'datepicker','placeholder'=>'Vence'])}}
			</div>
			<div class="col-md-4 col-sm-6">
				{{Form::cSelectWithDisabled('*Forma de Pago','fk_id_forma_pago',$formas_pago ?? [])}}
			</div>
			<div class="col-md-4 col-sm-6">
				{{Form::cSelectWithDisabled('*Monedas','fk_id_moneda',$monedas ?? [])}}
			</div>
			@if(isset($data->fk_id_estatus_solicitud_pago) && $data->fk_id_estatus_solicitud_pago != 1)
			<div class="col-md-4 col-sm-6">
				{{Form::cText('Fecha Cancelación','fecha_cancelacion',['disabled'])}}
			</div>
			<div class="col-md-8 col-sm-6">
				{{Form::cTextArea('Motivo Cancelación','motivo_cancelacion',['disabled','rows'=>'5'])}}
			</div>
			@endif
		</div>
	</div>
	<div class="form-group col-sm-6 col-md-6">
		<div class="card">
			<div class="card-header">
				<h4 class="text-center">Detalle</h4>
				<ul id="clothing-nav" class="nav nav-pills nav-justified" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" role="tab" data-toggle="tab" href="#tab-orden" id="orden-tab" aria-controls="orden" aria-expanded="true">Orden de Compra</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-concepto" id="concepto-tab" aria-controls="concepto" aria-expanded="true">Captura manual</a>
					</li>
				</ul>
				<div id="clothing-nav-content" class="card-body tab-content text-center">
					<div role="tabpanel" class="tab-pane fade show active" id="tab-orden" aria-labelledby="orden-tab">
						<div class="">
							<div class="card-body">
								<div id="detalle" class="col-md-12 col-sm-12">
									<h5>Orden de compra</h5>
									{{Form::Select('fk_id_orden_compra',
									collect($ordenes ?? [])->prepend('...','0'),
									null,
									['id'=>'fk_id_orden_compra','class'=>'form-control','data-url'=>companyAction('HomeController@index').'/Compras.ordenes/api'])}}
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tab-concepto" aria-labelledby="concepto-tab">
						<div class="">
							<div class="card-body">
								<div class="col-md-12 col-sm-12">
									<h5>Concepto</h5>
									<div class="row">
										<div class="col-md-12 col-sm-6">
											{{Form::cText('*Descripcion','descripcion')}}
										</div>
										<div class="col-md-6 col-sm-6">
											{{Form::cText('*Cantidad','cantidad',['class'=>'calculo'])}}
										</div>
										<div class="col-md-6 col-sm-6">
											{{Form::cText('Impuesto','impuesto',['class'=>'calculo'])}}
										</div>
										<div class="col-md-6 col-sm-6">
											{{Form::cText('*Subtotal','precio_unitario',['class'=>'calculo'])}}
										</div>
										<div class="col-md-6 col-sm-6">
											{{Form::cText('Importe','importe',['disabled'])}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-12 text-center mt-3">
								<div class="sep">
									<div class="sepBtn">
										<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large tooltipped"
												data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" id="agregar">
											<i class="material-icons">add</i>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="loadingtabla" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
				Cargando datos... <i class="material-icons align-middle loading">cached</i>
			</div>
			<table id="solicitud_pago" class="table responsive-table highlight" style="display: {{Route::currentRouteNamed(currentRouteName('create')) ?? 'none'}};">
				<thead>
					<tr>
						<th># Orden</th>
						<th>Descripción</th>
						<th>Cantidad</th>
						<th>Impuesto</th>
						<th>Subtotal</th>
						<th>Importe</th>
						<th></th>
					</tr>
				</thead>
				<tbody id="detalle_solicitud_pago">
				<input type="hidden" name="relations[has][detalle][-1][id_detalle_solicitud_pago]" value="-1">
				@if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
					@foreach($data->detalle->where('eliminar',0) as $row=>$detalle)
					<tr>
						<td><input type="hidden" id="index" value="{{$row}}"><input class="orden" type="hidden" name="relations[has][detalle][{{$row}}][fk_id_orden_compra]" value="{{$detalle->fk_id_orden_compra ?? ''}}">{{$detalle->fk_id_orden_compra ?? 'N/A'}}<input type="hidden" value="{{$detalle->id_detalle_solicitud_pago}}" name="relations[has][detalle][{{$row}}][id_detalle_solicitud_pago]"></td>
						<td>{{$detalle->descripcion}}</td>
						<td>{{$detalle->cantidad}}</td>
						<td>{{number_format($detalle->impuesto,2)}}</td>
						<td>{{number_format($detalle->precio_unitario,2)}}</td>
						<td>{{number_format($detalle->importe,2)}}<input type="hidden" class="importe" name="relations[has][detalle][{{$row}}][importe]" value="{{number_format($detalle->importe,2)}}"></td>
						<td>
							@if(Route::currentRouteNamed(currentRouteName('edit')))
							<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button>
							@endif
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" style="text-align: right">Total</td>
						<td>{{Form::Text('total',null,['class'=>'form-control','readonly','id'=>'total'])}}</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
@if(!empty($data->autorizaciones) && $data->fk_id_estatus_autorizacion != 1)
<div id="autorizaciones" class="row card z-depth-1-half">
	<div class="card-header">
		<div class="col-md-12 col-sm-12">
			<h3 class="text-danger text-center">Autorizaciones</h3>
		</div>
	</div>
	<div class="card-body">
		<div class="text-right">
			<a href="#" class="btn p-2 btn-dark" id="reload"><i class="material-icons align-middle">cached</i>Recargar</a>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>Condición</th>
					<th>Fecha</th>
					<th>Estatus</th>
					<th>Fecha estatus</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="autorizaciones_detalle">
				@foreach($data->autorizaciones->where('activo',1)->where('eliminar',0) as $autorizacion)
					@foreach($condiciones as $condicion)
						@if($autorizacion->fk_id_condicion == $condicion->id_condicion)
							<tr>
								<td>{{$autorizacion->condicion->nombre}}{{Form::hidden('',$autorizacion->id_autorizacion)}}{{Form::hidden('',$autorizacion->observaciones)}}{{Form::hidden('',$autorizacion->fk_id_estatus)}}</td>
								<td>{{$autorizacion->fecha_creacion}}</td>
								@if($autorizacion->fk_id_estatus == 1)
									<td>{{$autorizacion->estatus->estatus}}</td>
								@elseif($autorizacion->fk_id_estatus == 2)
									<td class="text-info">{{$autorizacion->estatus->estatus}}</td>
								@elseif($autorizacion->fk_id_estatus == 3)
									<td class="text-danger">{{$autorizacion->estatus->estatus}}</td>
								@else
									<td class="text-success">{{$autorizacion->estatus->estatus}}</td>
								@endif
								<td>{{$autorizacion->fecha_autorizacion}}</td>
								<td>
									@if(Route::currentRouteNamed(currentRouteName('edit')))
									 {{--Ventana de autorizaciones --}}
									<button class="condicion text-primary btn btn_tables is-icon eliminar bg-white" type="button" data-toggle="modal" data-target="#autorizacion">
									<i class="material-icons">new_releases</i>
									</button>
									@endif
								</td>
							</tr>
						@endif
					@endforeach
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('form-title')
		<h1 class="display-4">Solicitudes pagos</h1>
	@endsection
@section('smart-js')
	<script type="text/javascript">
        if ( sessionStorage.reloadAfterPageLoad ) {
            sessionStorage.clear();
            $.toaster({
                priority: 'success', title: 'Exito', message: 'Factura cancelada',
                settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
            });
        }
	</script>
	@parent
	<script type="text/javascript">
        rivets.binders['hide-delete'] = {
            bind: function (el) {
                if(el.dataset.fk_id_estatus_solicitud_pago != 1)
                {
                    $(el).hide();
                }
            }
        };
        rivets.binders['hide-update'] = {
            bind: function (el) {
                if(el.dataset.fk_id_estatus_solicitud_pago != 1)
                {
                    $(el).hide();
                }
            }
        };
		@can('update', currentEntity())
            window['smart-model'].collections.itemsOptions.edit ={a: {
            'html': '<i class="material-icons">mode_edit</i>',
            'class': 'btn is-icon',
            'rv-get-edit-url': '',
            'rv-hide-update':'',
            'data-toggle':'tooltip',
            'title':'Editar'
        }};
		@endcan
		@can('delete', currentEntity())
            window['smart-model'].collections.itemsOptions.delete = {a: {
            'html': '<i class="material-icons">delete</i>',
            'href' : '#',
            'class': 'btn is-icon',
            'rv-on-click': 'actions.showModalCancelar',
            'rv-get-delete-url': '',
            'data-delete-type': 'single',
            'rv-hide-delete':'',
            'data-toggle':'tooltip',
            'title':'Cancelar'
        }};
		@endcan
        window['smart-model'].actions.itemsCancel = function(e, rv,motivo){
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            let data = {motivo}
            $.delete(this.dataset.deleteUrl,data,function (response) {
                if(response.success){
                    sessionStorage.reloadAfterPageLoad = true;
                    location.reload();
                }
            });
        };
        window['smart-model'].actions.showModalCancelar = function(e, rv) {
            e.preventDefault();
            let modal = window['smart-modal'];
            modal.view = rivets.bind(modal, {
                title: '¿Estas seguro que deseas cancelar la factura?',
                content: '<form  id="cancel-form">' +
                '<div class="alert alert-warning text-center"><span class="text-danger">La cancelación de un documento es irreversible.</span><br>'+
                'Para continuar, especifique el motivo y de click en cancelar.</div>'+
                '<div class="form-group">' +
                '<label for="recipient-name" class="form-control-label">Cancelar:</label>' +
                '<input type="text" class="form-control" id="motivo_cancelacion" name="motivo_cancelacion">' +
                '</div>' +
                '</form>',
                buttons: [
                    {button: {
                        'text': 'Cerrar',
                        'class': 'btn btn-secondary',
                        'data-dismiss': 'modal',
                    }},
                    {button: {
                        'html': 'Cancelar',
                        'class': 'btn btn-danger',
                        'rv-on-click': 'action',
                    }}
                ],
                action: function(e,rv) {
                    var formData = new FormData(document.querySelector('#cancel-form')), convertedJSON = {}, it = formData.entries(), n;

                    while(n = it.next()) {
                        if(!n || n.done) break;
                        convertedJSON[n.value[0]] = n.value[1];
                    }
                    console.log(convertedJSON);
                    if(convertedJSON.motivo_cancelacion != ""){
                    	window['smart-model'].actions.itemsCancel.call(this, e, rv,convertedJSON);
                    }else{
                        $.toaster({
                            priority: 'danger', title: 'Por favor escriba un motivo de la cancelación', message: 'Error al cancelar',
                            settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
                        });
					}
                }.bind(this),
                // Opcionales
                onModalShow: function() {

                    let btn = modal.querySelector('[rv-on-click="action"]');

                    // Copiamos data a boton de modal
                    for (var i in this.dataset) btn.dataset[i] = this.dataset[i];

                }.bind(this),
                // onModalHide: function() {}
            });
            // Abrimos modal
            $(modal).modal('show');
        };
	</script>
@endsection
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@section('form-title')
		<h1 class="display-4">Agregar Solicitud Pago</h1>
	@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Editar Solicitud Pago</h1>
	@endsection
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('form-actions')
		@if($data->fk_id_estatus_solicitud_pago == 1)
			@parent
		@else
			{{ link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn btn-default progress-button']) }}

		@endif
	@endsection
	@section('form-title')
		<h1 class="display-4">Solicitud Pago</h1>
	@endsection
	@include('layouts.smart.show')
@endif