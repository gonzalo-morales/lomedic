
@section('content-width', 's12 m7 xl8 offset-xl2')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-11 col-xs-12">
		{{ Form::label('impacto', '* Impacto') }}
		{{ Form::text('impacto', null, ['id'=>'impacto','class'=>'form-control']) }}
		{{ $errors->has('impacto') ? HTML::tag('span', $errors->first('impacto'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-check col-md-1 col-xs-12">
		{{ Form::hidden('activo', 0) }}
		{{ Form::checkbox('activo', 1, old('activo'), ['id'=>'activo']) }}
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