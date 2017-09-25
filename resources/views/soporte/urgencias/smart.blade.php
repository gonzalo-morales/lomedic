
@section('content-width', 's12 m7 xl8 offset-xl2')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-12 col-xs-12">
		{{ Form::label('urgencia', '* Urgencia') }}
		<div class="input-group">
		{{ Form::text('urgencia', null, ['id'=>'urgencia','class'=>'form-control']) }}
			<div class="input-group-addon">
				{{ Form::label('activo', '¿Activo?') }}
				{{ Form::hidden('activo', 0) }}
				{{ Form::checkbox('activo', 1, old('activo'), ['id'=>'activo']) }}
			</div>
		</div>
	</div>
	{{ $errors->has('urgencia') ? HTML::tag('span', $errors->first('urgencia'), ['class'=>'help-block deep-orange-text']) : '' }}
	{{ $errors->has('activo') ?  HTML::tag('span', $errors->first('activo'), ['class'=>'help-block deep-orange-text']) : '' }}
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