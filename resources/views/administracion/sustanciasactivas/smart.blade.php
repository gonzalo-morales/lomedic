
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-11 col-xs-12">
		{{ Form::label('sustancia_activa', 'Sustancia Activa') }}
		{{ Form::text('sustancia_activa', null, ['id'=>'sustancia_activa','class'=>'form-control']) }}
		{{ $errors->has('sustancia_activa') ? HTML::tag('span', $errors->first('sustancia_activa'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-1 col-xs-12 d-flex align-items-center">
		{{--{{ Form::hidden('opcion_gramaje', 0) }}--}}
		<input type="hidden" value="0" name="opcion_gramaje">
		{{ Form::checkbox('opcion_gramaje', 1, old('opcion_gramaje'), ['id'=>'opcion_gramaje']) }}
		{{ Form::label('opcion_gramaje', '¿Gramaje?') }}
		{{ $errors->has('opcion_gramaje') ?  HTML::tag('span', $errors->first('opcion_gramaje'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div  class="col-md-12 text-center mt-4">
		<div class="alert alert-warning" role="alert">
            Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
        </div>
        {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
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