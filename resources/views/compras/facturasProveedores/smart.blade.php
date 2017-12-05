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
<div class="row">
	<div class="form-group col-md-3 col-sm-12">
		{{Form::cSelectWithDisabled('Proveedor','fk_id_socio_negocio',$proveedores ?? [])}}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		<div id="loadingcomprador" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
			Cargando datos... <i class="material-icons align-middle loading">cached</i>
		</div>
		{{Form::cText('Comprador','comprador',['disabled','data-url'=>companyAction('HomeController@index').'/RecursosHumanos.empleados/api'])}}
	</div>
	<div class="form-group col-md-3 col-sm-12">
		{{Form::cSelectWithDisabled('Sucursal','fk_id_sucursal', $sucursales ?? [])}}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{Form::cText('Fecha vencimiento','fecha_vencimiento',['class'=>'datepicker','placeholder'=>'Vence'])}}
	</div>
	@if(!Route::currentRouteNamed(currentRouteName('create')))
		<div class="form-group col-md-2 col-sm-6">
			{{Form::cText('Serie y Folio','serie_folio_factura',['disabled'])}}
		</div>
		<div class="form-group col-md-4 col-sm-6">
			{{Form::cText('Fecha Factura','fecha_factura',['disabled'])}}
		</div>
		<div class="form-group col-md-2 col-sm-6">
			{{Form::cText('Estatus Factura','fk_id_estatus_factura',['disabled','value'=> $data->estatus->estatus])}}
		</div>
		<div class="form-group col-md-2 col-sm-6">
			{{Form::cText('Moneda','fk_id_moneda',['disabled','value'=>'('.$data->moneda->moneda.') '.$data->moneda->descripcion])}}
		</div>
		<div class="form-group col-md-2 col-sm-6">
			{{Form::cText('Forma Pago','fk_id_forma_pago',['disabled','value'=>'('.$data->forma_pago->forma_pago.') '.$data->forma_pago->descripcion])}}
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
		</div>
		<div class="form-goup col-md-6 text-center">
			{{Form::cFile('PDF','archivo_pdf_input',['accept'=>'.pdf'])}}
		</div>
		<div class="form-group col-sm-12 text-center mt-3">
			<div class="sep">
				<div class="sepBtn">
					<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large tooltipped "
							data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" id="agregar">
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

@if(!Route::currentRouteNamed(currentRouteName('create')))
	<div id="detallefactura" class="container-fluid w-100 mt-2 px-0">
		<div class="card text-center z-depth-1-half" style="min-height: 555px">
			<div class="card-header py-2">
				<h4 class="card-title">Detalle de la factura</h4>
				<div class="divider my-2"></div>
				<ul id="clothing-nav" class="nav nav-pills nav-justified" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" role="tab" data-toggle="tab" href="#tab-pdf" id="pdf-tab" aria-controls="pdf" aria-expanded="true">PDF</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-xml" id="xml-tab" aria-controls="xml" aria-expanded="true">XML</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-pagos" id="pagos-tab" aria-controls="pagos" aria-expanded="true">Pagos</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-ordenes" id="ordenes-tab" aria-controls="ordenes" aria-expanded="true">Órdenes</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" role="tab" data-toggle="tab" href="#tab-notas" id="notas-tab" aria-controls="notas" aria-expanded="true">Notas</a>
					</li>
				</ul>
			</div>
			<!-- Content Panel -->
			<div id="clothing-nav-content" class="card-body tab-content">
				<div role="tabpanel" class="tab-pane fade show active" id="tab-pdf" aria-labelledby="pdf-tab">

				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-xml" aria-labelledby="xml-tab">
					Datos generales del proyecto
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-pagos" aria-labelledby="pagos-tab">
					Pagos realizados de esta factura
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-ordenes" aria-labelledby="ordenes-tab">
					 Ordenes relacionadas
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab-notas" aria-labelledby="notas-tab">
					Notas de crédito
				</div>
			</div>
			<!-- End Content Panel -->
		</div>
	</div>
@elseif(Route::currentRouteNamed(currentRouteName('create')))
	<div class="card">
		<div class="card-body mt-3">
			<div id="loadingxml" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
				Cargando datos... <i class="material-icons align-middle loading">cached</i>
			</div>
			<h1 class="text-success text-center">Productos facturados</h1>
			<table id="rows" class="table responsive-table highlight">
				<thead>
					<tr>
						<th>Clave Producto Servicio</th>
						<th>Clave Unidad</th>
						<th>Descripcion</th>
						<th>Cantidad</th>
						<th>Precio Unitario</th>
						<th>Descuento</th>
						<th>Impuesto</th>
						<th>Importe</th>
						<th>Orden de Compra</th>
					</tr>
				</thead>
				<tbody id="productos_facturados">
				</tbody>
			</table>
		</div>
	</div>
@endif
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('form-title')
		<h1 class="display-4">Facturas de Proveedores</h1>
	@endsection
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@section('form-title')
		<h1 class="display-4">Agregar Factura de Proveedor</h1>
	@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Editar Factura de Proveedor</h1>
	@endsection
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('form-title')
		<h1 class="display-4">Factura de Proveedor</h1>
	@endsection
	@include('layouts.smart.show')
@endif

{{--@if (currentRouteName('createSolicitudOrden'))--}}
	{{--@include('layouts.smart.create')--}}
{{--@endif--}}