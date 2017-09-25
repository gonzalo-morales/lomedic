
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('estado', 'Estado:') }}
		{{ Form::text('estado', null, ['id'=>'estado','class'=>'form-control']) }}
		{{ $errors->has('estado') ? HTML::tag('span', $errors->first('estado'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12 ">
		{{ Form::label('fk_id_pais', 'País:') }}
		{{ Form::select('fk_id_pais', (isset($paises) ? $paises : []), null, ['id'=>'fk_id_pais', 'class'=>'form-control']) }}
		{{ $errors->has('fk_id_pais') ? HTML::tag('span', $errors->first('fk_id_pais'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-check col-md-4 col-xs-12">
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