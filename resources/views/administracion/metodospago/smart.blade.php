
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-5 col-xs-12">
		{{ Form::label('metodo_pago', 'Metodo de pago') }}
		{{ Form::text('metodo_pago', null, ['id'=>'metodo_pago','class'=>'form-control']) }}
		{{ $errors->has('metodo_pago') ? HTML::tag('span', $errors->first('metodo_pago'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-5 col-xs-12">
		{{ Form::label('descripcion', 'Descripción') }}
		{{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
		{{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-check col-md-2 col-xs-12">
		{{ Form::hidden('activo', 0) }}
		{{ Form::checkbox('activo', null, old('activo'), ['id'=>'activo']) }}
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