
@section('content-width')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-sm-6 col-md-3">
		{{ Form::label('aduana', '* Número aduana:') }}
		{{ Form::number('aduana', null, ['id'=>'aduana','class'=>'form-control']) }}
		{{ $errors->has('aduana') ? HTML::tag('span', $errors->first('aduana'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div class="form-group col-sm-6 col-md-3">
		{{ Form::label('patente', '* Número patente:') }}
		{{ Form::number('patente', null, ['id'=>'patente','class'=>'form-control']) }}
		{{ $errors->has('patente') ? HTML::tag('span', $errors->first('patente'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div class="form-group col-sm-6 col-md-3">
		{{ Form::label('ejercicio', '* Número ejercicio:') }}
		{{ Form::number('ejercicio', null, ['id'=>'ejercicio','class'=>'form-control']) }}
		{{ $errors->has('ejercicio') ? HTML::tag('span', $errors->first('ejercicio'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div class="form-group col-sm-6 col-md-3">
		{{ Form::label('cantidad', '* Número cantidad:') }}
		{{ Form::number('cantidad', null, ['id'=>'cantidad','class'=>'form-control']) }}
		{{ $errors->has('cantidad') ? HTML::tag('span', $errors->first('cantidad'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div  class="col-md-12 text-center mt-2">
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