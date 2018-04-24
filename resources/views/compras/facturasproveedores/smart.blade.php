@extends(smart())

@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index'))) )
		<script type="text/javascript">
			var comprador_js = '{{$js_comprador ?? ''}}';
		</script>
		<script type="text/javascript" src="{{ asset('js/facturas_proveedores.js') }}"></script>
	@endif
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
    @inroute(['show','edit'])
    	<div class="row">
    		<div class="col-md-12 text-center text-success">
    			<h3>Factura No. {{$data->id_documento}}</h3>
    		</div>
    	</div>
    @endif
    @crear
    <div id="confirmar_sobreescritura" class="modal" tabindex="-1" role="dialog">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title">¿Deseas agregar una factura?</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
    				</button>
    			</div>
    			<div class="modal-body">
    				<p>Si agregas una nueva factura se eliminarán los datos actuales</p>
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    				<button id="cargar" type="button" data-dismiss="modal" class="btn btn-primary">Cargar</button>
    			</div>
    		</div>
    	</div>
    </div>
    @else
    <div id="confirmar_eliminar_pago" class="modal" tabindex="-1" role="dialog">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title">¿Deseas cancelar el pago?</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
    				</button>
    			</div>
    			<div class="modal-body">
    				<p>Si eliminas el pago no se podrá recuperar</p>
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    				<button id="eliminar_pago_button" type="button" data-dismiss="modal" class="btn btn-primary">Eliminar</button>
    			</div>
    		</div>
    	</div>
    </div>
    @endif
    <div class="row">
    	<div class="form-group col-md-3 col-sm-12">
    		{{Form::cSelectWithDisabled('Proveedor','fk_id_socio_negocio',$proveedores ?? [],
    		[!Route::currentRouteNamed(currentRouteName('create')) ? 'readonly' : ''])}}
    	</div>
    	<div class="form-group col-md-3 col-sm-6">
    		<div id="loadingcomprador" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
    			Cargando datos... <i class="material-icons align-middle loading">cached</i>
    		</div>
    	{{Form::cText('Comprador','comprador',['disabled','data-url'=>companyAction('HomeController@index').'/recursoshumanos.empleados/api'],
    		isset($data->proveedor) && isset($data->proveedor->ejecutivocompra) ? $data->proveedor->ejecutivocompra->nombre ?? '' .' '.$data->proveedor->ejecutivocompra->apellido_paterno ?? '' .' '.$data->proveedor->ejecutivocompra->apellido_materno ?? ''  : null)}}
    	</div>
    	<div class="form-group col-md-3 col-sm-12">
    		{{Form::cSelectWithDisabled('Sucursal','fk_id_sucursal', $sucursales ?? [])}}
    	</div>
    	<div class="form-group col-md-3 col-sm-6">
    		{{Form::cText('Fecha vencimiento','fecha_vencimiento',['class'=>'datepicker','placeholder'=>'Vence'])}}
    	</div>
    	@inroute(['edit','show'])
    		<div class="form-group col-md-2 col-sm-6">
    			{{Form::label('serie_folio_factura','Serie y Folio',['style'=>'display: block;text-align: center;'])}}
    			<div class="input-group">
    				{{Form::cText('','serie_factura',['readonly'])}}
    				{{Form::cText('','folio_factura',['readonly'])}}
    			</div>
    		</div>
    		<div class="form-group col-md-2 col-sm-4">
    			{{Form::cText('Fecha Factura','fecha_factura',['readonly'])}}
    		</div>
    		<div class="form-group col-md-2 col-sm-2">
    			{{Form::cText('Versión','version_sat',['readonly'])}}
    		</div>
			<div class="form-group col-md-3 col-sm-6">
				{{Form::hidden('fk_id_forma_pago')}}
				{{Form::cText($data->version_sat == 3.3 ? 'Forma Pago' : 'Método Pago','forma_pago',['disabled'],'('.$data->forma_pago->forma_pago.') '.$data->forma_pago->descripcion)}}
			</div>
            <div class="form-group col-md-3 col-sm-6">
                {{Form::hidden('fk_id_metodo_pago')}}
                {{Form::cText($data->version_sat == 3.3 ? 'Método Pago' : 'Forma Pago','metodo_pago',['disabled'],'('.$data->metodo_pago->metodo_pago.') '.$data->metodo_pago->descripcion)}}
            </div>
    		<div class="form-group col-md-2 col-sm-6">
				{{Form::hidden('fk_id_estatus_factura')}}
				{{Form::cText('Estatus Factura','estatus_factura',['disabled'],$data->estatus->estatus ?? null)}}
    		</div>
    		<div class="form-group col-md-3 col-sm-6">
    			{{--{{Form::cText('Moneda','fk_id_moneda',['disabled','value'=>'('.$data->moneda->moneda.') '.$data->moneda->descripcion])}}--}}
    			{{Form::hidden('fk_id_moneda')}}
				{{Form::cText('Moneda','moneda',['disabled'],'('.$data->moneda->moneda.') '.$data->moneda->descripcion)}}
    		</div>
    		<div class="form-group col-md-3 col-sm-6">
    			{{Form::cText('Subtotal','subtotal',['readonly'])}}
    		</div>
    		<div class="form-group col-md-3 col-sm-6">
    			{{Form::cText('IVA','iva',['readonly'])}}
    		</div>
    		<div class="form-group col-md-3 col-sm-6">
    			{{Form::cText('Total pagado','total_pagado',['readonly'])}}
    		</div>
    		<div class="form-group col-md-3 col-sm-6">
    			{{Form::cText('Total','total',['readonly'])}}
    		</div>
    		@if($data->fk_id_estatus_factura == 3){{-- Si está cancelado --}}
    		<div class="form-group col-md-12 col-sm-12 text-center">
    			{{Form::cTextArea('Motivo Cancelacion','motivo_cancelacion',['rows'=>2,'style'=>'resize:none'])}}
    		</div>
    		@endif
    	@endif
    	@crear
    		<div class="form-goup col-md-6 text-center">
    			{{Form::cFile('XML','archivo_xml_input',['data-url'=>companyAction('parseXML'),'accept'=>'.xml'])}}
    			<input id="archivo_xml_hidden" style="display:none" name="archivo_xml_hidden" type="file">
    			{{Form::hidden('uuid','',['id'=>'uuid'])}}
    			{{Form::hidden('version_sat','',['id'=>'version_sat'])}}
    		</div>
    		<div class="form-goup col-md-6 text-center">
    			{{Form::cFile('PDF','archivo_pdf_input',['accept'=>'.pdf'])}}
    			<input id="archivo_pdf_hidden" style="display:none" name="archivo_pdf_hidden" type="file">
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
					@inroute(['edit','show'])
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-pagos" id="pagos-tab" aria-controls="pagos" aria-expanded="true">Pagos</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-ordenes" id="ordenes-tab" aria-controls="ordenes" aria-expanded="true">Órdenes</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-notas" id="notas-tab" aria-controls="notas" aria-expanded="true">Notas</a>
					</li>
					@endif
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
							<table id="factura" class="table responsive-table highlight" style="display: {{Route::currentRouteNamed(currentRouteName('create')) ?? 'none'}};" data-urlorden="{{ companyAction('Compras\OrdenesController@getDetallesOrden') }}">
								<thead id="encabezado_factura">
								@inroute(['edit','show'])
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
											<th>Detalle O.C.</th>
										</tr>
									@elseif($data->version_sat == "3.2")
										<tr>
											<th>Descripcion</th>
											<th>Unidad</th>
											<th>Cantidad</th>
											<th>Valor Unitario</th>
											<th>Importe</th>
											<th>Orden de Compra</th>
											<th>Detalle O.C.</th>
										</tr>
									@endif
								@endif
								</thead>
								<tbody id="productos_facturados">
								@inroute(['edit','show'])
									@if($data->version_sat == "3.3")
										{{-- {{ dd($data->detalle_facturas_proveedores) }} --}}
										@foreach($data->detalle as $detalle)
										<tr>
											<td>
												{{Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][id_documento_detalle]',$detalle->id_documento_detalle)}}
												{{$detalle->clave_producto_servicio->clave_producto_servicio}}
											</td>
											<td>{{$detalle->clave_unidad->clave_unidad}}</td>
											<td>{{$detalle->descripcion}}</td>
											<td>{{$detalle->cantidad}}</td>
											<td>${{number_format($detalle->precio_unitario,2)}}</td>
											<td>${{number_format($detalle->descuento,2)}}</td>
											<td>
												{{$detalle->impuesto->impuesto ?? 'NA' }}
											</td>
											<td>${{number_format($detalle->importe,2)}}</td>
											<td>
												{{$data->fk_id_estatus_factura == 1 ? Form::Text('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_documento_base]',
												$detalle->fk_id_documento_base,['class'=>'form-control integer orden-compra']) :
												$detalle->fk_id_documento_base}}
										  	</td>
											<td>
												<div id="loadinglinea" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
													Cargando... <i class="material-icons align-middle loading">cached</i>
												</div>
												{{Form::cSelectWithDisabled('','relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_linea]',isset($detalle->orden->detalle) ? $detalle->orden->detalle
												->mapWithKeys(function ($detalle){
													return $detalle->upc->upc ? [$detalle->id_documento_detalle=>$detalle->sku->sku.' - '.$detalle->upc->upc] : [$detalle->id_documento_detalle=>$detalle->sku->sku];
												}) : null,null,null,$detalle->fk_id_linea)}}
										  	</td>
										</tr>
										@endforeach
									@elseif($data->version_sat == "3.2")
										@foreach($data->detalle as $detalle)
										<tr>
											<td>
												{{Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][id_documento_detalle]',$detalle->id_documento_detalle)}}
												{{$detalle->descripcion}}
											</td>
											<td>{{$detalle->unidad}}</td>
											<td>{{$detalle->cantidad}}</td>
											<td>${{number_format($detalle->precio_unitario,2)}}</td>
											<td>{{$detalle->importe}}</td>
											<td>{{$data->fk_id_estatus_factura == 1 ?
											 	Form::Text('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_documento_base]',$detalle->fk_id_documento_base,['class'=>'form-control integer orden-compra']) :
											  	$detalle->fk_id_documento_base}}
											</td>
											<td>
												<div id="loadinglinea" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
													Cargando... <i class="material-icons align-middle loading">cached</i>
												</div>
												{{Form::cSelectWithDisabled('','relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_linea]',isset($detalle->orden->detalle) ? $detalle->orden->detalle
												->mapWithKeys(function ($detalle){
													return $detalle->upc->upc ? [$detalle->id_documento_detalle=>$detalle->sku->sku.' - '.$detalle->upc->upc] : [$detalle->id_documento_detalle=>$detalle->sku->sku];
												}) : null,null,null,$detalle->fk_id_linea)}}
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
				<div role="tabpanel" class="tab-pane fade" id="tab-pdf" aria-labelledby="pdf-tab">
					<div id="loadingpdf" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
						Cargando pdf... <i class="material-icons align-middle loading">cached</i>
					</div>
					<div>
						<object id="pdf" data="{!! !Route::currentRouteNamed(currentRouteName('create')) && !Route::currentRouteNamed(currentRouteName('index'))
							&& file_exists(Storage::disk('factura_proveedor')->getDriver()->getAdapter()->getPathPrefix().($data->archivo_pdf ??'.pdf')) ?
							'data:application/pdf;base64,'.base64_encode(file_get_contents(Storage::disk('factura_proveedor')->getDriver()->getAdapter()->getPathPrefix().$data->archivo_pdf)) :
							'' !!}" style="display: block" type="application/pdf" width="100%" height="1100" >
						</object>
					</div>
				</div>
				@inroute(['edit','show'])
				<div role="tabpanel" class="tab-pane fade" id="tab-pagos" aria-labelledby="pagos-tab">
					<div class="card">
						<div class="card-body mt-3">
							<h1 class="text-success text-center">Pagos</h1>
							<table id="pagos" class="table responsive-table highlight" style="display: {{Route::currentRouteNamed(currentRouteName('create')) ?? 'none'}};">
								<thead id="encabezado_pagos">
								@inroute(['show','edit'])
									<tr>
										<th>ID</th>
										<th>Número Referencia</th>
										<th>Banco</th>
										<th>Fecha</th>
										<th>Monto</th>
										<th>Forma de pago</th>
										<th>Moneda</th>
										<th>Tipo Cambio</th>
										<th>Observaciones</th>
										{{--<th></th>--}}
									</tr>
								@endif
								</thead>
								<tbody id="detalle_pagos">
								@inroute(['show','edit'])
									@if(!empty($data->detallePagos))
										@foreach($data->detallePagos->where('eliminar',false) as $detalle)
											<tr>
												<td>{{$detalle->pago->id_pago}}</td>
												<td>{{$detalle->pago->numero_referencia}}</td>
												<td>{{$detalle->pago->banco->banco ?? null}}</td>
												<td>{{$detalle->pago->fecha_pago}}</td>
												<td>${{number_format($detalle->pago->monto,2)}}</td>
												<td>{{$detalle->pago->forma_pago->descripcion}}</td>
												<td>{{'('.$detalle->pago->moneda->moneda.') '.$detalle->pago->moneda->descripcion}}</td>
												<td>{{number_format($detalle->pago->tipo_cambio,2)}}</td>
												<td>{{$detalle->pago->observaciones}}</td>
												{{--<td><a class="eliminar_pago" href="{{companyAction('Compras\PagosController@destroy',['id'=>$pago->id_pago])}}"><i class="material-icons">cancel</i></a></td>--}}
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="9">Sin pagos</td>
										</tr>
									@endif
								@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-ordenes" aria-labelledby="ordenes-tab">
					<div class="card">
						<div class="card-body mt-3">
							<h1 class="text-success text-center">Órdenes relacionadas</h1>
							<table id="ordenes" class="table responsive-table highlight">
								<thead id="ordenes_cabecera">
									<tr>
										<th>ID</th>
										<th>Proveedor</th>
										<th>Fecha del pedido</th>
										<th>Fecha de entrega</th>
										<th>Estatus Orden</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="ordenes_detalle">
								@foreach($data->detalle()->distinct()->orderBy('fk_id_documento_base')->get() as $detalle)
									@if(!empty($detalle->fk_id_documento_base))
									<tr>
										<td>{{$detalle->orden->id_documento}}</td>
										<td>{{$detalle->orden->proveedor->nombre_comercial}}</td>
										<td>{{$detalle->orden->fecha_creacion}}</td>
										<td>{{$detalle->orden->fecha_estimada_entrega}}</td>
										<td>{{$detalle->orden->estatus->estatus ?? ''}}</td>
										<td><a href="{{companyAction('Compras\OrdenesController@show',['id'=>$detalle->orden->id_documento])}}"><i class="material-icons align-middle">visibility</i></a></td>
									</tr>
									@else
									<tr>
										<td colspan="6">Sin órdenes relacionadas</td>
									</tr>
									@endif
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-notas" aria-labelledby="notas-tab">
					<div class="card">
						<div class="card-body mt-3">
							<h1 class="text-success text-center">Notas de Crédito</h1>
							<table class="table">
								<thead>
								<tr>
									<th>#</th>
									<th>Serie y Nota</th>
									<th>Total</th>
									<th>Moneda</th>
									<th>Proveedor</th>
									<th></th>
								</tr>
								</thead>
								<tbody>
								@if(!empty($data->notas))
								@foreach($data->notas as $key => $nota)
									<tr>
										<td>{{$nota->id_documento}}</td>
										<td>{{$nota->serie_factura.$nota->folio_factura}}</td>
										<td>{{$nota->total}}</td>
										<td>{{'('.$nota->moneda->moneda.') '.$nota->moneda->descripcion}}</td>
										<td>{{$nota->proveedor->nombre_comercial ?? null}}</td>
										<td>
											<a href="{{companyAction('Compras\NotasCreditoProveedorController@show',['id'=>$nota->id_documento])}}"><i class="material-icons align-middle">visibility</i></a>
										</td>
									</tr>
									@endforeach
								@else
									<tr>
										<td colspan="6">Sin notas de crédito</td>
									</tr>
								@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
				@endif
			</div>
			<!-- End Content Panel -->
		</div>
	</div>
@endsection

{{-- DONT DELETE --}}
@index
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
                var data = motivo;
                $.delete(this.dataset.deleteUrl,data,function (response) {
                    if(response.success){
                        sessionStorage.reloadAfterPageLoad = true;
                        location.reload();
                    }
                });
            };
            window['smart-model'].actions.showModalCancelar = function(e, rv) {
                e.preventDefault();
                var modal = window['smart-modal'];
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

                        var btn = modal.querySelector('[rv-on-click="action"]');

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
@endif