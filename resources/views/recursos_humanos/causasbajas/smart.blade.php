
@section('content-width', 's12 ml2')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-8 col-xs-12">
		{{ Form::label('causa_baja', '* Causa Baja') }}
		{{ Form::text('causa_baja', null, ['id'=>'causa_baja','class'=>'form-control']) }}
		{{ $errors->has('causa_baja') ? HTML::tag('span', $errors->first('causa_baja'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-check-label col-md-4 col-xs-12">
		{{ Form::hidden('activo', 0) }}
		{{ Form::checkbox('activo', null, old('activo'), ['id'=>'nacional']) }}
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