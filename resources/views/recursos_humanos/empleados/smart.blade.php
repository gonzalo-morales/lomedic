
@section('content-width', 's12 ml2')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('nombre', '* Nombres') }}
		{{ Form::text('nombre', null, ['id'=>'nombre','class'=>'form-control']) }}
		{{ $errors->has('nombre') ? HTML::tag('span', $errors->first('nombre'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('apellido_paterno', '* Apellido Paterno') }}
		{{ Form::text('apellido_paterno', null, ['id'=>'apellido_paterno','class'=>'form-control']) }}
		{{ $errors->has('apellido_paterno') ? HTML::tag('span', $errors->first('apellido_paterno'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('apellido_materno', '* Apellido Materno') }}
		{{ Form::text('apellido_materno', null, ['id'=>'apellido_materno','class'=>'form-control']) }}
		{{ $errors->has('apellido_materno') ? HTML::tag('span', $errors->first('apellido_materno'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fecha_nacimiento', '* Fecha Nacimiento') }}
		{{ Form::text('fecha_nacimiento', null, ['id'=>'fecha_nacimiento','class'=>'datepicker form-control']) }}
		{{ $errors->has('fecha_nacimiento') ? HTML::tag('span', $errors->first('fecha_nacimiento'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('curp', 'Curp') }}
		{{ Form::text('curp', null, ['id'=>'curp','class'=>'form-control']) }}
		{{ $errors->has('curp') ? HTML::tag('span', $errors->first('curp'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('rfc', 'Rfc') }}
		{{ Form::text('rfc', null, ['id'=>'rfc','class'=>'form-control']) }}
		{{ $errors->has('rfc') ? HTML::tag('span', $errors->first('rfc'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('correo_personal', 'Correo Personal') }}
		{{ Form::text('correo_personal', null, ['id'=>'correo_personal','class'=>'form-control']) }}
		{{ $errors->has('correo_personal') ? HTML::tag('span', $errors->first('correo_personal'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('telefono', 'Telefono') }}
		{{ Form::text('telefono', null, ['id'=>'telefono','class'=>'form-control']) }}
		{{ $errors->has('telefono') ? HTML::tag('span', $errors->first('telefono'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('celular', 'Celular') }}
		{{ Form::text('celular', null, ['id'=>'celular','class'=>'form-control']) }}
		{{ $errors->has('celular') ? HTML::tag('span', $errors->first('celular'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_empresa_alta_imss', 'Empresa Alta Imss') }}
		{{ Form::select('fk_id_empresa_alta_imss', (isset($companies) ? $companies : []), ['id'=>'fk_id_empresa_alta_imss','class'=>'form-control']) }}
		{{ $errors->has('fk_id_empresa_alta_imss') ? HTML::tag('span', $errors->first('fk_id_empresa_alta_imss'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('numero_imss', 'Numero Imss') }}
		{{ Form::text('numero_imss', null, ['id'=>'numero_imss','class'=>'form-control']) }}
		{{ $errors->has('numero_imss') ? HTML::tag('span', $errors->first('numero_imss'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_empresa_laboral', 'Empresa Laboral') }}
		{{ Form::select('fk_id_empresa_laboral', (isset($companies) ? $companies : []), ['id'=>'fk_id_empresa_laboral','class'=>'form-control']) }}
		{{ $errors->has('fk_id_empresa_laboral') ? HTML::tag('span', $errors->first('fk_id_empresa_laboral'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>

<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_departamento', 'Departamento') }}
		{{ Form::select('fk_id_departamento', (isset($departments) ? $departments : []), ['id'=>'fk_id_departamento','class'=>'form-control']) }}
		{{ $errors->has('fk_id_departamento') ? HTML::tag('span', $errors->first('fk_id_departamento'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_puesto', 'Puesto') }}
		{{ Form::select('fk_id_puesto', (isset($titles) ? $titles : []), ['id'=>'fk_id_puesto','class'=>'form-control']) }}
		{{ $errors->has('fk_id_puesto') ? HTML::tag('span', $errors->first('fk_id_puesto'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_sucursal', 'Sucursal') }}
		{{ Form::select('fk_id_sucursal', (isset($offices) ? $offices : []), ['id'=>'fk_id_sucursal','class'=>'form-control']) }}
		{{ $errors->has('fk_id_sucursal') ? HTML::tag('span', $errors->first('fk_id_sucursal'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>

<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('factor_descuento', 'Factor Descuento') }}
		{{ Form::text('factor_descuento', null, ['id'=>'factor_descuento','class'=>'form-control']) }}
		{{ $errors->has('factor_descuento') ? HTML::tag('span', $errors->first('factor_descuento'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('numero_infonavit', 'Numero Infonavit') }}
		{{ Form::text('numero_infonavit', null, ['id'=>'numero_infonavit','class'=>'form-control']) }}
		{{ $errors->has('numero_infonavit') ? HTML::tag('span', $errors->first('numero_infonavit'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-check-label col-md-4 col-xs-12">
		{{ Form::hidden('activo', 0) }}
		{{ Form::checkbox('activo', null, old('activo'), ['id'=>'activo']) }}
		{{ Form::label('activo', '¿Activo?') }}
		{{ $errors->has('activo') ?  HTML::tag('span', $errors->first('activo'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>

@endsection

@section('form-utils')
@stop

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@include('layouts.smart.index')
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

@if (Route::currentRouteNamed(currentRouteName('export')))
	@include('layouts.smart.export')
@endif