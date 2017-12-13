@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		{{HTML::script(asset('js/pagos_facturas.js'))}}
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
		{{Form::cText('Monto','monto')}}
	</div>
	<div class="form-group col-md-4 col-sm-6">
		{{Form::cSelectWithDisabled('Forma de pago','fk_id_forma_pago', $formas_pago ?? [])}}
	</div>
	<div class="form-group col-md-4 col-sm-6">
		{{Form::cSelectWithDisabled('Moneda','fk_id_moneda', $monedas ?? [])}}
	</div>
	<div class="form-group col-md-4 col-sm-6">
		{{Form::cText('Tipo de Cambio','tipo_cambio')}}
	</div>
	<div class="form-group col-md-4 col-sm-6">
		{{Form::cSelectWithDisabled('Factura','fk_id_factura',$facturas ?? [])}}
	</div>
	<div class="form-group col-md-4 col-sm-6">
		{{Form::cSelectWithDisabled('Solicitud Pago','fk_id_solicitud',$solicitudes ?? [])}}
	</div>
	<div class="form-group col-md-12 col-sm-12 text-center">
		{{Form::cFile('Comprobante','comprobante_input',['accept'=>'.pdf,image/*'])}}
		<input id="comprobante_hidden" class="custom-file-input" style="display:none" name="comprobante_hidden" type="file">
	</div>
	<div class="form-group col-md-12 text-center mt-6">
		{{Form::cTextArea('Observaciones','observaciones',['rows'=>2,'style'=>'resize:none'])}}
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
