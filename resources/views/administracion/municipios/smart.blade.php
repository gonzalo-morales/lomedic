
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-5 col-xs-12">
		{{ Form::label('municipio', 'Municipio') }}
		{{ Form::text('municipio', null, ['id'=>'municipio','class'=>'form-control']) }}
		{{ $errors->has('municipio') ? HTML::tag('span', $errors->first('municipio'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-5 col-xs-12">
		{{ Form::label('fk_id_estado', 'Estado') }}
		{{ Form::select('fk_id_estado', (isset($estados) ? $estados : []), null, ['id'=>'fk_id_estado','class'=>'form-control']) }}
		{{ $errors->has('fk_id_estado') ? HTML::tag('span', $errors->first('fk_id_estado'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-2 col-xs-12">
		{{ Form::hidden('activo', 0) }}
		{{ Form::checkbox('activo', 1, old('activo'), ['id'=>'activo']) }}
		{{ Form::label('activo', 'Activo') }}
		{{ $errors->has('activo') ?  HTML::tag('span', $errors->first('activo'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
@endsection

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