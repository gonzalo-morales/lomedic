
@section('content-width', 's12 ml2')

@section('header-bottom')
	@parent
@endsection

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-12 col-xs-12">
		{{ Form::label('subcategoria', '* Subcategoria') }}
		<div class="input-group">
		{{ Form::text('subcategoria', null, ['id'=>'subcategoria','class'=>'form-control']) }}
			<div class="input-group-addon">
		{{ Form::select('fk_id_categoria', isset($categories) ? $categories : [], null,['id'=>'fk_id_categoria','class'=>'form-control']) }}
			</div>
			<div class="input-group-addon">
				{{ Form::hidden('activo', 0) }}
				{{ Form::checkbox('activo', null, old('activo'), ['id'=>'activo','class'=>'']) }}
				{{ Form::label('activo', 'Activo') }}
			</div>
		</div>
	</div>
	{{ $errors->has('subcategoria') ? HTML::tag('span', $errors->first('subcategoria'), ['class'=>'help-block deep-orange-text']) : '' }}
	{{ $errors->has('fk_id_categoria') ? HTML::tag('span', $errors->first('fk_id_categoria'), ['class'=>'help-block deep-orange-text']) : '' }}
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