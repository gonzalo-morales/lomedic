@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection
@section('header-bottom')
	@parent
	<script type="text/javascript" src="{{ asset('js/jquery.ui.autocomplete2.js') }}"></script>
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
@endsection

@section('form-actions')
	<div class="row">
		<div class="right">
			{{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-primary']) }}
			{!! HTML::decode(link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn btn-default'])) !!}
		</div>
	</div>
@endsection

@section('content-width', 'container-fluid')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-4 col-xs-4">
		{!! Form::text('codigo',null, ['id'=>'codigo']) !!}
		{{ Form::label('codigo', '* Código') }}
		{{ $errors->has('codigo') ? HTML::tag('span', $errors->first('codigo'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-4">
		{!! Form::text('nombre',null,['id'=>'nombre]) !!}
		{{ Form::label('nombre', '* Nombre') }}
		{{ $errors->has('nombre') ? HTML::tag('span', $errors->first('nombre'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div class="form-group col-md-2 col-xs-2">
		{!! Form::text('fk_id_sucursal',null, ['id'=>'fk_id_sucursal','autocomplete'=>'off','data-url'=>companyAction('Administracion\SucursalesController@obtenerSucursales')]) !!}
		{{ Form::label('fk_id_sucursal', '* Sucursal') }}
		{{ $errors->has('fk_id_sucursal') ? HTML::tag('span', $errors->first('fk_id_sucursal'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div class="form-group col-md-2 col-xs-2">
		{!! Form::checkbox('virtual',1,true) !!}
		{{ Form::label('virtual', '¿Es un almacen virtual?') }}
	</div>
	{{--Si la solicitud está cancelada--}}
		@if(isset($data->fk_id_estatus_solicitud) && $data->fk_id_estatus_solicitud ==3)
			<div class="form-group col-md-2 col-xs-2">
				{!! Form::text('fecha_cancelacion',$data->fecha_cancelacion,['disabled']) !!}
				{{ Form::label('fecha_cancelacion','Fecha de cancelación') }}
			</div>
			<div class="form-group col col-md-10 col-xs-10">
				{!! Form::text('motivo_cancelacion',$data->motivo_cancelacion,['disabled']) !!}
				{{ Form::label('motivo_cancelacion','Motivo de la cancelación') }}
			</div>
		@endif
</div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@include(currentRouteName('index'))
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@include('layouts.smart.show')
@endif