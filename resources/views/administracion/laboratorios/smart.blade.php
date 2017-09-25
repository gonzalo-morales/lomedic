
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-12 col-xs-12">
		{{ Form::label('laboratorio', 'Laboratorio:') }}
		{{ Form::text('laboratorio', null, ['id'=>'laboratorio','class'=>'form-control']) }}
		{{ $errors->has('laboratorio') ? HTML::tag('span', $errors->first('laboratorio'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="row">
	<div class="form-check col-md-12 col-xs-12">
		{{ Form::hidden('activo', 0) }}
		{{ Form::checkbox('activo', 1, old('activo'), ['id'=>'activo']) }}
		{{ Form::label('activo', 'Activo:') }}
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