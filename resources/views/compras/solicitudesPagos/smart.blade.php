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
			<h3>Factura No. {{$data->id_solicitud_pago}}</h3>
		</div>
	</div>
@endif
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
		</div>
	</div>
	<div class="form-group col-sm-6 col-md-6">
		<div>
			<div class="card-header">
				<h1>Detalle</h1>
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
						<div class="card">
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
						<div class="card">
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
				@if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
					@foreach($data->detalle->where('eliminar',0) as $row=>$detalle)
					<tr>
						<td><input type="hidden" value="{{$detalle->id_detalle_solicitud_pago}}" name="relations[has][detalle][{{$row}}][id_detalle_solicitud_pago]">{{$detalle->fk_id_orden_compra ?? 'N/A'}}</td>
						<td>{{$detalle->descripcion}}</td>
						<td>{{$detalle->cantidad}}</td>
						<td>{{number_format($detalle->impuesto,2)}}</td>
						<td>{{number_format($detalle->precio_unitario,2)}}</td>
						<td>{{number_format($detalle->importe,2)}}</td>
						@if(Route::currentRouteNamed(currentRouteName('edit')))
						<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button></td>
						@endif
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
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
                if(el.dataset.fk_id_estatus_factura != 1)
                {
                    $(el).hide();
                }
            }
        };
        rivets.binders['hide-update'] = {
            bind: function (el) {
                if(el.dataset.fk_id_estatus_factura != 1)
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
	@section('form-title')
		<h1 class="display-4">Solicitud Pago</h1>
	@endsection
	@include('layouts.smart.show')
@endif

{{--@if (currentRouteName('createSolicitudOrden'))--}}
	{{--@include('layouts.smart.create')--}}
{{--@endif--}}