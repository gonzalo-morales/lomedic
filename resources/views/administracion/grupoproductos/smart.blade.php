
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
		<div class="form-group col-md-5 col-xs-12">
			{{ Form::label('descripcion', '* Descripcion del grupo') }}
			{{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
			{{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
		</div>
		<div class="form-group col-md-5 col-xs-12">
			{{ Form::label('descripcion_producto', '* Descripcion del producto') }}
			{{ Form::text('descripcion_producto', null, ['id'=>'descripcion_producto','class'=>'form-control']) }}
			{{ $errors->has('descripcion_producto') ? HTML::tag('span', $errors->first('descripcion_producto'), ['class'=>'help-block deep-orange-text']) : '' }}
		</div>
		<div class="col-md-2 col-xs-12">
			{{ Form::label('nomenclatura', '* Nomenclatura') }}
				{{ Form::text('nomenclatura', null, ['id'=>'nomenclatura','class'=>'form-control']) }}
			{{ $errors->has('nomenclatura') ? HTML::tag('span', $errors->first('nomenclatura'), ['class'=>'help-block deep-orange-text']) : '' }}
		</div>
	<div  class="col-md-12 text-center">
		@if(!Route::currentRouteNamed(currentRouteName('show'))?'disabled aria-disabled=true':'')
			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-check btn-secondary {{ (isset($data->tipo) && $data->tipo == 1) || old('tipo') == 1 ? 'active':''}}">
					<input type="radio" name="tipo" id="medicamento" autocomplete="off" value="1"
							{{(isset($data->tipo) && $data->tipo == 1) || old('tipo') == 1 ? 'checked':''}}>Medicamento
				</label>
				<label class="btn btn-check btn-secondary {{ (isset($data->tipo) && $data->tipo == 2) || old('tipo') == 2 ? 'active':''}}">
					<input type="radio" name="tipo" id="material_curacion" autocomplete="off" value="2"
							{{(isset($data->tipo) && $data->tipo == 2) || old('tipo') == 2 ? 'checked':''}}>Material de curación
				</label>
			</div>
		@else
			<div class="btn-group">
				<label class="btn btn-check btn-secondary {{ (isset($data->tipo) && $data->tipo == 1) || old('tipo') == 1 ? 'active':''}}">
					Medicamento
				</label>
				<label class="btn btn-check btn-secondary {{ (isset($data->tipo) && $data->tipo == 2) || old('tipo') == 2 ? 'active':''}}">
					Material de curación
				</label>
			</div>
		@endif
	</div>
	<div  class="col-md-12 text-center mt-2">
		<div class="alert alert-warning" role="alert">
            Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
        </div>
        {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
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