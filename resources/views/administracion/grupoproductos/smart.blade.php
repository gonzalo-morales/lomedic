
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-6 col-xs-12">
		{{ Form::label('descripcion', 'Descripcion del grupo') }}
		{{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
		{{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-6 col-xs-12">
		{{ Form::label('descripcion_producto', 'Descripcion del producto') }}
		{{ Form::text('descripcion_producto', null, ['id'=>'descripcion_producto','class'=>'form-control']) }}
		{{ $errors->has('descripcion_producto') ? HTML::tag('span', $errors->first('descripcion_producto'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="col-md-12 col-xs-12">
		{{ Form::label('nomenclatura', '* Nomenclatura') }}
		<div class="input-group">
			{{ Form::text('nomenclatura', null, ['id'=>'nomenclatura','class'=>'form-control']) }}
			{{--{{ Form::label('tipo', 'Tipo') }}--}}
			{{--{{ Form::text('tipo', null, ['id'=>'tipo','class'=>'form-control']) }}--}}
			{{--{{ $errors->has('tipo') ? HTML::tag('span', $errors->first('tipo'), ['class'=>'help-block deep-orange-text']) : '' }}--}}
			<div class="input-group-addon" role="group" aria-label="tipo" data-toggle="buttons">
				<label class="btn btn-check btn-secondary">
					<input type="radio" name="tipo" id="medicamento" autocomplete="off" value="1" class="btn btn-secondary">Medicamento
				</label>
				<label class="btn btn-check btn-secondary">
					<input type="radio" name="tipo" id="material_curacion" autocomplete="off" value="2"  class="btn btn-secondary">Material de Curación
				</label>
			</div>
			<div class="input-group-addon">
				{{ Form::hidden('activo', 0) }}
				{{ Form::checkbox('activo', 1, old('activo'), ['id'=>'activo']) }}
				{{ Form::label('activo', 'Activo') }}
			</div>
		</div>
	</div>
	{{ $errors->has('nomenclatura') ? HTML::tag('span', $errors->first('nomenclatura'), ['class'=>'help-block deep-orange-text']) : '' }}
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