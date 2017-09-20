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
			{{ Form::button('Guardar', ['type' =>'submit', 'class'=>'waves-effect waves-light btn orange']) }}
			{!! HTML::decode(link_to(companyRoute('index'), 'Cerrar', ['class'=>'waves-effect waves-teal btn-flat teal-text'])) !!}
		</div>
	</div>
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="input-field col s4 m4">
		{!! Form::text('codigo',null, ['id'=>'codigo']) !!}
		{{ Form::label('codigo', '* Código') }}
		{{ $errors->has('codigo') ? HTML::tag('span', $errors->first('codigo'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="input-field col s4 m4">
		{!! Form::text('nombre',null,['id'=>'nombre]) !!}
		{{ Form::label('nombre', '* Nombre') }}
		{{ $errors->has('nombre') ? HTML::tag('span', $errors->first('nombre'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="input-field col s2 m2">
		{!! Form::text('fk_id_sucursal',null, ['id'=>'fk_id_sucursal','autocomplete'=>'off','data-url'=>companyAction('Administracion\SucursalesController@obtenerSucursales')]) !!}
		{{ Form::label('fk_id_sucursal', '* Sucursal') }}
		{{ $errors->has('fk_id_sucursal') ? HTML::tag('span', $errors->first('fk_id_sucursal'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="input-field col s2 m2">
		{!! Form::checkbox('virtual',1,true) !!}
		{{ Form::label('virtual', '¿Es un almacen virtual?') }}
	</div>
	{{--Si la solicitud está cancelada--}}
		@if(isset($data->fk_id_estatus_solicitud) && $data->fk_id_estatus_solicitud ==3)
			<div class="input-field col s2 m2">
				{!! Form::text('fecha_cancelacion',$data->fecha_cancelacion,['disabled']) !!}
				{{ Form::label('fecha_cancelacion','Fecha de cancelación') }}
			</div>
			<div class="input-field col s10 m10">
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