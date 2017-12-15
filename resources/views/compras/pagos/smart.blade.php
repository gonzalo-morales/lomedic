@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript">
            var factura_js = '{{$js_factura ?? ''}}';
            var solicitud_js = '{{$js_solicitud ?? ''}}';
		</script>
		{{HTML::script(asset('js/pagos.js'))}}
{{--		<script type="text/javascript" src="{{ asset('js/facturas_proveedores.js') }}"></script>--}}
	@endif
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
@if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
	<div class="row">
		<div class="col-md-12 text-center text-success">
			<h3>Pago No. {{$data->id_pago}}</h3>
		</div>
	</div>
@endif
<div class="row">
	<div class="form-group col-md-4 col-sm-6">
		{{Form::cText('Número Referencia','numero_referencia')}}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{Form::cSelectWithDisabled('Banco','fk_id_banco',$bancos ?? [],[])}}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{Form::cText('Fecha pago','fecha_pago',['class'=>'datepicker','placeholder'=>'dd-mm-yyyy'])}}
	</div>
	<div class="form-group col-md-2 col-sm-6">
		{{Form::cNumber('Monto','monto',[],$data->monto ?? null)}}
	</div>
	<div class="form-group col-md-4 col-sm-6">
		{{Form::cSelectWithDisabled('Forma de pago','fk_id_forma_pago', $formas_pago ?? [])}}
	</div>
	<div class="form-group col-md-4 col-sm-6">
		{{Form::cSelectWithDisabled('Moneda','fk_id_moneda', $monedas ?? [])}}
	</div>
	<div class="form-group col-md-4 col-sm-6">
		{{Form::cNumber('Tipo de Cambio','tipo_cambio',[],$data->tipo_cambio ?? null)}}
	</div>
	<div class="form-group col-md-12 col-sm-12 text-center">
		{{Form::cFile('Comprobante','comprobante_input',['accept'=>'.pdf,image/*'])}}
		<input id="comprobante_hidden" class="custom-file-input" style="display:none" name="comprobante_hidden" type="file">
	</div>
	<div class="form-group col-md-12 text-center mt-6">
		{{Form::cTextArea('Observaciones','observaciones',['rows'=>2,'style'=>'resize:none'])}}
	</div>
</div>
<div class="row card">
	<div class="card-header col-md-12 col-sm-12">
		<div class="row">
			<div class="form-group col-md-4 col-sm-6">
				{{Form::cSelect('Factura','fk_id_factura',$facturas ?? [],['data-url'=>companyAction('HomeController@index').'/Compras.facturasproveedores/api'])}}
			</div>
			<div class="form-group col-md-4 col-sm-6">
				{{Form::cSelect('Solicitud Pago','fk_id_solicitud',$solicitudes ?? [],['data-url'=>companyAction('HomeController@index').'/Compras.solicitudespagos/api'])}}
			</div>
			<div class="form-group col-md-4 col-sm-6">
				{{Form::cText('Monto','monto_detalle')}}
			</div>
			<div class="col-md-12 col-sm-12">
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
		</div>
	</div>
	<div class="card-body col-md-12 col-sm-12 mt-3">
		<div id="loadingpagos" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
			Cargando datos... <i class="material-icons align-middle loading">cached</i>
		</div>
		<h1 class="text-success text-center">Documentos pagados</h1>
		<table id="pagos" class="table responsive-table highlight">
			<thead id="encabezado_pagos">
				<tr>
					<th>Descripcion</th>
					<th>Total Documento</th>
					<th>Pagado</th>
					<th>Monto</th>
					@if(Route::currentRouteNamed(currentRouteName('create')))
					<th></th>
					@endif
				</tr>
			</thead>
			<tbody id="pagos_realizados">
			@if (Route::currentRouteNamed(currentRouteName('edit')) || Route::currentRouteNamed(currentRouteName('show')))
				@foreach($data->detalle->where('eliminar',0) as $index=>$detalle)
					<tr>
						<td>
							{{$detalle->fk_id_tipo_documento == 7 ? 'Factura no.'.$detalle->fk_id_documento : 'Solicitud no.'.$detalle->fk_id_documento}}
							{{Form::hidden('index',$index,['id'=>$index])}}
							{{Form::hidden('relations[has]['.$index.'][id_detalle_pago]',$detalle->id_detalle_pago,['id'=>'id_detalle_pago'])}}
						</td>
						<td>
							{{$detalle->fk_id_tipo_documento == 7 ? number_format($detalle->facturaproveedor->total,2) : number_format($detalle->solicitudpago->total,2)}}
						</td>
						<td>
							{{$detalle->fk_id_tipo_documento == 7 ? number_format($detalle->facturaproveedor->total_pagado,2) : number_format($detalle->solicitudpago->total_pagado,2)}}
						</td>
						<td>
							{{number_format($detalle->monto,2)}}
							{{Form::hidden('relations[has]['.$index.'][monto]',$detalle->monto,['class'=>'monto'])}}
						</td>
					</tr>
				@endforeach
			@endif
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3"></td>
					<td>
						{{Form::cNumber('Monto Aplicado','monto_aplicado',['disabled'],$data->monto_aplicado ?? null,null,'')}}
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('form-title')
		<h1 class="display-4">Pagos</h1>
	@endsection
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@section('form-title')
		<h1 class="display-4">Agregar Pago</h1>
	@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Editar Pago</h1>
	@endsection
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('form-title')
		<h1 class="display-4">Pago</h1>
	@endsection
	@include('layouts.smart.show')
@endif
