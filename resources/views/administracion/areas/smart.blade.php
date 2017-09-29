
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-6 col-xs-12">
		{{ Form::label('area', '* Area') }}
		{{ Form::text('area', null, ['id'=>'area','class'=>'form-control']) }}
		{{ $errors->has('area') ? HTML::tag('span', $errors->first('area'), ['class'=>'help-block deep-orange-text form-control']) : '' }}
	</div>
	<div class="form-group col-md-5 col-xs-12">
		{{ Form::label('clave_area', '* Clave') }}
		{{ Form::text('clave_area', null, ['id'=>'clave_area','class'=>'form-control']) }}
		{{ $errors->has('clave_area') ? HTML::tag('span', $errors->first('clave_area'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div  class="col-md-12 text-center mt-4">
		<div class="alert alert-warning" role="alert">
			Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrará en los modulos correspondientes que se requieran.
		</div>
		<div data-toggle="buttons">
			<label class="btn btn-secondary form-check-label {{ !empty($data->activo) || old('activo') ? 'active':''}}">
				{{Form::checkbox('activo',true,old('activo'),['id'=>'activo',Route::currentRouteNamed(currentRouteName('show'))?'disabled':''])}}
				Activo
			</label>
		</div>
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