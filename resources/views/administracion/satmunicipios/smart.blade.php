
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-6 col-sm-6 col-md-3">
		{{ Form::label('sat_municipio', 'Código SAT municipio') }}
		{{ Form::number('sat_municipio', null, ['id'=>'sat_municipio','class'=>'form-control']) }}
		{{ $errors->has('sat_municipio') ? HTML::tag('span', $errors->first('sat_municipio'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-6 col-sm-6 col-md-3">
		{{ Form::label('sat_estado', 'Abreviatura estado') }}
		{{ Form::text('sat_estado', null, ['id'=>'sat_estado','class'=>'form-control']) }}
		{{ $errors->has('sat_estado') ? HTML::tag('span', $errors->first('sat_estado'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-12 col-sm-12 col-md-6">
		{{ Form::label('municipio', 'Municipio') }}
		{{ Form::text('municipio', null, ['id'=>'municipio','class'=>'form-control']) }}
		{{ $errors->has('municipio') ? HTML::tag('span', $errors->first('municipio'), ['class'=>'help-block deep-orange-text']) : '' }}
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