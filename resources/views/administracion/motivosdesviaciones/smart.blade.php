
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="col-md-12 col-xs-12">
		{{ Form::label('descripcion', 'Descripción:') }}
		<div class="input-group">
		{{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
			<div class="input-group-addon">
				{{ Form::hidden('activo', 0) }}
				{{ Form::checkbox('activo', 1, old('activo'), ['id'=>'activo']) }}
				{{ Form::label('activo', 'Activo') }}
			</div>
		</div>
	</div>
	{{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
	{{ $errors->has('activo') ?  HTML::tag('span', $errors->first('activo'), ['class'=>'help-block deep-orange-text']) : '' }}
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