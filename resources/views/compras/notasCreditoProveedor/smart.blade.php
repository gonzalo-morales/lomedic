@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript">
			var facturas_js = '{{$js_facturas ?? ''}}';
		</script>
		{{HTML::script(asset('js/notas_credito_proveedores.js'))}}
	@endif
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
@if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
	<div class="row">
		<div class="col-md-12 text-center text-success">
			<h3>Factura No. {{$data->id_factura_proveedor}}</h3>
		</div>
	</div>
@endif
@if (Route::currentRouteNamed(currentRouteName('create')))
<div id="confirmar_sobreescritura" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Deseas agregar una nota de crédito?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Si agregas una nueva nota de crédito se eliminarán los datos actuales</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button id="cargar" type="button" data-dismiss="modal" class="btn btn-primary">Cargar</button>
			</div>
		</div>
	</div>
</div>
@endif
<div class="row">
	<div class="col-md-8">
		<div class="row">
			<div class="form-group col-md-6 col-sm-12">
				{{Form::cSelectWithDisabled('Proveedor','fk_id_socio_negocio',$proveedores ?? [],
				[!Route::currentRouteNamed(currentRouteName('create')) ? 'disabled' : '',
				'data-url'=>companyAction('HomeController@index').'/Compras.facturasproveedores/api'])}}
			</div>
			<div class="form-group col-md-6 col-sm-12">
				{{Form::cSelectWithDisabled('Sucursal','fk_id_sucursal', $sucursales ?? [])}}
			</div>
			@if(!Route::currentRouteNamed(currentRouteName('create')) && !Route::currentRouteNamed(currentRouteName('index')))
				<div class="form-group col-md-2 col-sm-6">
					{{Form::label('serie_folio_factura','Serie y Folio',['style'=>'display: block;text-align: center;'])}}
					<div class="input-group">
						{{Form::Text('serie_factura',null,['disabled','class'=>'form-control'])}}
						{{Form::Text('folio_factura',null,['disabled','class'=>'form-control'])}}
					</div>
				</div>
				<div class="form-group col-md-2 col-sm-4">
					{{Form::cText('Fecha Factura','fecha_factura',['disabled'])}}
				</div>
				<div class="form-group col-md-2 col-sm-2">
					{{Form::cText('Versión','version_sat',['disabled'])}}
				</div>
				<div class="form-group col-md-2 col-sm-6">
					{{Form::label('fk_id_estatus_nota','Estatus Nota')}}
					{{Form::Text('fk_id_estatus_nota',$data->estatus->estatus,['disabled','class'=>'form-control'])}}
				</div>
				<div class="form-group col-md-2 col-sm-6">
					{{--{{Form::cText('Moneda','fk_id_moneda',['disabled','value'=>'('.$data->moneda->moneda.') '.$data->moneda->descripcion])}}--}}
					{{Form::hidden('fk_id_moneda')}}
					{{Form::label('moneda','Moneda')}}
					{{Form::Text('moneda','('.$data->moneda->moneda.') '.$data->moneda->descripcion,['disabled','class'=>'form-control'])}}
				</div>
				<div class="form-group col-md-2 col-sm-6">
					{{Form::label('fk_id_forma_pago','Forma Pago')}}
					{{Form::Text('fk_id_forma_pago',!empty($data->fk_id_forma_pago) ?'('.$data->forma_pago->forma_pago.') '.$data->forma_pago->descripcion : '',['disabled','class'=>'form-control'])}}
				</div>
				<div class="form-group col-md-3 col-sm-6">
					{{Form::cText('Subtotal','subtotal',['disabled'])}}
				</div>
				<div class="form-group col-md-3 col-sm-6">
					{{Form::cText('IVA','iva',['disabled'])}}
				</div>
				<div class="form-group col-md-3 col-sm-6">
					{{Form::cText('Total pagado','total_pagado',['disabled'])}}
				</div>
				<div class="form-group col-md-3 col-sm-6">
					{{Form::cText('Total','total',['disabled'])}}
				</div>
				@if($data->fk_id_estatus_nota == 3){{-- Si está cancelado --}}
				<div class="form-group col-md-12 col-sm-12 text-center">
					{{Form::cTextArea('Motivo Cancelacion','motivo_cancelacion',['rows'=>2,'style'=>'resize:none'])}}
				</div>
				@endif
			@endif
			@if(Route::currentRouteNamed(currentRouteName('create')))
				<div class="form-goup col-md-6 text-center">
					{{Form::cFile('XML','archivo_xml_input',['data-url'=>companyAction('parseXML'),'accept'=>'.xml'])}}
					<input id="archivo_xml_hidden" class="custom-file-input" style="display:none" name="archivo_xml_hidden" type="file">
					{{Form::hidden('uuid','',['id'=>'uuid','data-url'=>companyAction('getFacturaRelacionada')])}}
					{{Form::hidden('version_sat','',['id'=>'version_sat'])}}
				</div>
				<div class="form-goup col-md-6 text-center">
					{{Form::cFile('PDF','archivo_pdf_input',['accept'=>'.pdf'])}}
					<input id="archivo_pdf_hidden" class="custom-file-input" style="display:none" name="archivo_pdf_hidden" type="file">
				</div>
				<div class="form-group col-sm-12 text-center mt-3">
					<div class="sep">
						<div class="sepBtn">
							<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large tooltipped"
									data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" id="agregar"
									data-toggle="modal" data-target="#confirmar_sobreescritura">
								<i class="material-icons">add</i>
							</button>
						</div>
					</div>
				</div>
			@endif
				<div class="form-group col-md-12 text-center mt-5">
					{{Form::cTextArea('Observaciones','observaciones',['rows'=>2,'style'=>'resize:none'])}}
				</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12 card">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<h2 class="text-success text-center">Relaciones Facturas</h2>
			</div>
			<div class="col-md-5 col-sm-10">
				{{Form::cSelect('Tipo Relacion','fk_id_tipo_relacion',$relaciones ?? [],[empty($data->version_sat) || $data->version_sat == '3.3' ? 'disabled' : ''])}}
			</div>
			<div class="col-md-5 col-sm-10">
				{{Form::cSelect('Factura','fk_id_factura_proveedor',$facturas ?? [],[empty($data->version_sat) || $data->version_sat == '3.3' ? 'disabled' : '','class'=>'select2'])}}
			</div>
			<div class="col-md-2 col-sm-2">
				<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large tooltipped"
						data-position="bottom" data-delay="50" data-tooltip="Agregar" {{empty($data->version_sat) || $data->version_sat == '3.3' ? 'disabled' : ''}} type="button" id="agregar_relacion"
						>
					<i class="material-icons">add</i>
				</button>
			</div>
			<div class="col-md-12 col-sm-12 card-action">
				<table class="table">
					<thead>
					<tr>
						<th>#</th>
						<th>Serie y Folio</th>
						<th>Tipo Relacion</th>
						<th></th>
					</tr>
					</thead>
					<tbody id="relaciones">
					@if(!Route::currentRouteNamed(currentRouteName('create')) && !Route::currentRouteNamed(currentRouteName('index')))
						@if(!empty($data->cfdirelacionado))
							@foreach($data->cfdirelacionado->where('eliminar',false) as $index => $cfdirelacionado)
								{{--{{dd($cfdirelacionado)}}--}}
								<tr>
									<td>
										{{Form::hidden('index',$index)}}
										{{Form::hidden('relations[has][cfdirelacionado]['.$index.'][id_relacion_cfdi_proveedores]',$cfdirelacionado->id_relacion_cfdi_proveedores)}}
										{{$cfdirelacionado->fk_id_documento}}
									</td>
									<td>{{$cfdirelacionado->factura->serie_factura.$cfdirelacionado->factura->folio_factura}}</td>
									<td>{{'('.$cfdirelacionado->tiporelacion->tipo_relacion.') '.$cfdirelacionado->tiporelacion->descripcion}}</td>
									<td>
										<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button>
									</td>
								</tr>
							@endforeach
						@endif
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="detallefactura" class="container-fluid w-100 mt-2 px-0">
	<div class="card text-center z-depth-1-half" style="min-height: 555px">
		<div class="card-header py-2">
			<h4 class="card-title">Detalle de la factura</h4>
			<div class="divider my-2"></div>
			<ul id="clothing-nav" class="nav nav-pills nav-justified" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" role="tab" data-toggle="tab" href="#tab-xml" id="xml-tab" aria-controls="xml" aria-expanded="true">Productos</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" role="tab" data-toggle="tab" href="#tab-pdf" id="pdf-tab" aria-controls="pdf" aria-expanded="true">PDF</a>
				</li>
			</ul>
		</div>
		<!-- Content Panel -->
		<div id="clothing-nav-content" class="card-body tab-content text-center">
			<div role="tabpanel" class="tab-pane fade show active" id="tab-xml" aria-labelledby="xml-tab">
				<div class="card">
					<div class="card-body mt-3">
						<div id="loadingxml" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
							Cargando datos... <i class="material-icons align-middle loading">cached</i>
						</div>
						<h1 class="text-success text-center">Productos facturados</h1>
						<table id="factura" class="table responsive-table highlight" style="display: {{Route::currentRouteNamed(currentRouteName('create')) ?? 'none'}};">
							<thead id="encabezado_factura">
							@if(!Route::currentRouteNamed(currentRouteName('create')) && !Route::currentRouteNamed(currentRouteName('index')))
								@if($data->version_sat == "3.3")
									<tr>
										<th>Clave Producto Servicio</th>
										<th>Clave Unidad</th>
										<th>Descripcion</th>
										<th>Cantidad</th>
										<th>Valor Unitario</th>
										<th>Descuento</th>
										<th>Impuesto</th>
										<th>Importe</th>
										<th>Orden de Compra</th>
									</tr>
								@elseif($data->version_sat == "3.2")
									<tr>
										<th>Descripcion</th>
										<th>Unidad</th>
										<th>Cantidad</th>
										<th>Valor Unitario</th>
										<th>Importe</th>
									</tr>
								@endif
							@endif
							</thead>
							<tbody id="productos_facturados">
							@if(!Route::currentRouteNamed(currentRouteName('create')) && !Route::currentRouteNamed(currentRouteName('index')))
								@if($data->version_sat == "3.3")
									@foreach($data->detalle as $detalle)
									<tr>
										<td>{{$detalle->clave_producto_servicio->clave_producto_servicio}}</td>
										<td>{{$detalle->clave_unidad->clave_unidad}}</td>
										<td>{{$detalle->descripcion}}</td>
										<td>{{$detalle->cantidad}}</td>
										<td>${{number_format($detalle->precio_unitario,2)}}</td>
										<td>${{number_format($detalle->descuento,2)}}</td>
										<td>{{$detalle->impuesto->impuesto}}</td>
										<td>${{number_format($detalle->importe,2)}}</td>
									</tr>
									@endforeach
								@elseif($data->version_sat == "3.2")
									@foreach($data->detalle as $detalle)
									<tr>
										<td>{{$detalle->descripcion}}</td>
										<td>{{$detalle->unidad}}</td>
										<td>{{$detalle->cantidad}}</td>
										<td>${{number_format($detalle->precio_unitario,2)}}</td>
										<td>{{$detalle->importe}}</td>
									</tr>
									@endforeach
								@endif
							@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="tab-pdf" aria-labelledby="pdf-tab">
				<div id="loadingpdf" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
					Cargando pdf... <i class="material-icons align-middle loading">cached</i>
				</div>
				<div>
					<object id="pdf" data="{!! !Route::currentRouteNamed(currentRouteName('create')) && !Route::currentRouteNamed(currentRouteName('index')) ?
					 'data:application/pdf;base64,'.base64_encode(file_get_contents(Storage::disk('notas_proveedor')->getDriver()->getAdapter()->getPathPrefix().$data->archivo_pdf)) :
					  ''!!}" style="display: block" type="application/pdf" width="100%" height="1100" >
					</object>
				</div>
			</div>
		</div>
		<!-- End Content Panel -->
	</div>
</div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('form-title')
		<h1 class="display-4">Notas de Credito de Proveedores</h1>
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
                if(el.dataset.fk_id_estatus_nota != 1)
                {
                    $(el).hide();
                }
            }
        };
        rivets.binders['hide-update'] = {
            bind: function (el) {
                if(el.dataset.fk_id_estatus_nota != 1)
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
		<h1 class="display-4">Agregar Nota de Crédito de Proveedor</h1>
	@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Editar Nota de Crédito de Proveedor</h1>
	@endsection
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('form-title')
		<h1 class="display-4">Nota de Crédito de Proveedor</h1>
	@endsection
	@include('layouts.smart.show')
@endif