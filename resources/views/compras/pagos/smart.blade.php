@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript">
			var comprador_js = '{{$js_comprador ?? ''}}';
		</script>
		{{HTML::script(asset('js/facturas_proveedores.js'))}}
{{--		<script type="text/javascript" src="{{ asset('js/facturas_proveedores.js') }}"></script>--}}
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
@endif
<div class="row">
	<div class="form-group col-md-3 col-sm-12">
		{{Form::cSelectWithDisabled('Proveedor','fk_id_socio_negocio',$proveedores ?? [],
		[!Route::currentRouteNamed(currentRouteName('create')) ? 'disabled' : ''])}}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		<div id="loadingcomprador" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
			Cargando datos... <i class="material-icons align-middle loading">cached</i>
		</div>
		{{Form::label('comprador','Comprador')}}
		{{Form::text('comprador',isset($data->fk_id_socio_negocio) ?
		$data->proveedor->ejecutivocompra->nombre.' '.$data->proveedor->ejecutivocompra->apellido_paterno.' '.$data->proveedor->ejecutivocompra->apellido_materno :
		null,
		['disabled','class'=>'form-control','data-url'=>companyAction('HomeController@index').'/RecursosHumanos.empleados/api'])}}
{{--		{{Form::cText('Comprador','comprador',['disabled','data-url'=>companyAction('HomeController@index').'/RecursosHumanos.empleados/api'])}}--}}
	</div>
	<div class="form-group col-md-3 col-sm-12">
		{{Form::cSelectWithDisabled('Sucursal','fk_id_sucursal', $sucursales ?? [])}}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{Form::cText('Fecha vencimiento','fecha_vencimiento',['class'=>'datepicker','placeholder'=>'Vence'])}}
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
			{{Form::label('fk_id_estatus_factura','Estatus Factura')}}
			{{Form::Text('fk_id_estatus_factura',$data->estatus->estatus,['disabled','class'=>'form-control'])}}
		</div>
		<div class="form-group col-md-2 col-sm-6">
			{{Form::cText('Moneda','fk_id_moneda',['disabled','value'=>'('.$data->moneda->moneda.') '.$data->moneda->descripcion])}}
		</div>
		<div class="form-group col-md-2 col-sm-6">
			{{Form::label('fk_id_forma_pago','Forma Pago')}}
			{{Form::Text('fk_id_forma_pago','('.$data->forma_pago->forma_pago.') '.$data->forma_pago->descripcion,['disabled','class'=>'form-control'])}}
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
		@if($data->fk_id_estatus_factura == 3){{-- Si está cancelado --}}
		<div class="form-group col-md-12 col-sm-12 text-center">
			{{Form::cTextArea('Motivo Cancelacion','motivo_cancelacion',['rows'=>2,'style'=>'resize:none'])}}
		</div>
		@endif
	@endif
	@if(Route::currentRouteNamed(currentRouteName('create')))
		<div class="form-goup col-md-6 text-center">
			{{Form::cFile('XML','archivo_xml_input',['data-url'=>companyAction('parseXML'),'accept'=>'.xml'])}}
			<input id="archivo_xml_hidden" class="custom-file-input" style="display:none" name="archivo_xml_hidden" type="file">
			{{Form::hidden('uuid','',['id'=>'uuid'])}}
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
					@if(!Route::currentRouteNamed(currentRouteName('create')))
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
											<th>Orden de Compra</th>
										</tr>
									@endif
								@endif
								</thead>
								<tbody id="productos_facturados">
								@if(!Route::currentRouteNamed(currentRouteName('create')) && !Route::currentRouteNamed(currentRouteName('index')))
									@if($data->version_sat == "3.3")
										@foreach($data->detalle_facturas_proveedores as $detalle)
										<tr>
											<td>{{$detalle->clave_producto_servicio->clave_producto_servicio}}</td>
											<td>{{$detalle->clave_unidad->clave_unidad}}</td>
											<td>{{$detalle->descripcion}}</td>
											<td>{{$detalle->cantidad}}</td>
											<td>{{$detalle->precio_unitario}}</td>
											<td>{{$detalle->descuento}}</td>
											<td>{{$detalle->impuesto->impuesto}}</td>
											<td>{{$detalle->importe}}</td>
											<td>{{$data->fk_id_estatus_factura == 1 ?
											 Form::Text('producto['.$detalle->id_detalle_factura_proveedor.'][fk_id_orden_compra]',$detalle->fk_id_orden_compra,['class'=>'form-control integer']) :
											  $detalle->fk_id_orden_compra}}</td>
										</tr>
										@endforeach
									@elseif($data->version_sat == "3.2")
										@foreach($data->detalle_facturas_proveedores as $detalle)
										<tr>
											<th>{{$detalle->descripcion}}</th>
											<th>{{$detalle->unidad}}</th>
											<th>{{$detalle->cantidad}}</th>
											<th>{{$detalle->precio_unitario}}</th>
											<th>{{$detalle->importe}}</th>
											<th>{{$data->fk_id_estatus_factura == 1 ?
											 Form::Text('producto['.$detalle->id_detalle_factura_proveedor.'][fk_id_orden_compra]',$detalle->fk_id_orden_compra,['class'=>'form-control integer']) :
											  $detalle->fk_id_orden_compra}}</th>
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
						 'data:application/pdf;base64,'.base64_encode(file_get_contents(Storage::disk('factura_proveedor')->getDriver()->getAdapter()->getPathPrefix().$data->archivo_pdf)) :
						  ''!!}" style="display: block" type="application/pdf" width="100%" height="1100" >
						</object>
					</div>
				</div>
				@if(!Route::currentRouteNamed(currentRouteName('create')) && !Route::currentRouteNamed(currentRouteName('index')))
				<div role="tabpanel" class="tab-pane fade" id="tab-pagos" aria-labelledby="pagos-tab">
					Pagos realizados de esta factura
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-ordenes" aria-labelledby="ordenes-tab">
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
						@foreach($data->detalle_facturas_proveedores()->select('fk_id_orden_compra')->distinct()->orderBy('fk_id_orden_compra')->get() as $detalle)
							@if(!empty($detalle->fk_id_orden_compra))
							<tr>
								<td>{{$detalle->orden->id_orden}}</td>
								<td>{{$detalle->orden->proveedor->nombre_comercial}}</td>
								<td>{{$detalle->orden->fecha_creacion}}</td>
								<td>{{$detalle->orden->fecha_estimada_entrega}}</td>
								<td>{{$detalle->orden->estatus->estatus}}</td>
								<td><a href="{{companyAction('Compras\OrdenesController@show',['id'=>$detalle->orden->id_orden])}}"><i class="material-icons align-middle">visibility</i></a></td>
							</tr>
							@endif
						@endforeach
						</tbody>
					</table>

				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-notas" aria-labelledby="notas-tab">
					Notas de crédito
				</div>
				@endif
			</div>
			<!-- End Content Panel -->
		</div>
	</div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('form-title')
		<h1 class="display-4">Pagos de Facturas</h1>
	@endsection
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@section('form-title')
		<h1 class="display-4">Agregar Pago de Factura</h1>
	@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Editar Pago de Factura</h1>
	@endsection
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('form-title')
		<h1 class="display-4">Pagos de Facturas</h1>
	@endsection
	@include('layouts.smart.show')
@endif

{{--@if (currentRouteName('createSolicitudOrden'))--}}
	{{--@include('layouts.smart.create')--}}
{{--@endif--}}