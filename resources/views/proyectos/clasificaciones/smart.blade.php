
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-4">
		{{Form::cText('Clasificación','clasificacion',['id'=>'clasificacion'])}}
	</div>
	<div class="form-group col-md-4">
		{{Form::cText('Nomenclatura','nomenclatura',['id'=>'nomenclatura'])}}
	</div>
	<div class="form-group col-md-4">
		{{Form::cSelectWithDisabled('Tipo de clasificación','fk_id_tipo_proyecto',isset($tiposProyectos)?$tiposProyectos:[])}}
	</div>
	<div  class="col-md-12 text-center mt-4">
		<div class="alert alert-warning" role="alert">
			Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrará en los modulos correspondientes que se requieran.
		</div>
		<div data-toggle="buttons">
			<label class="btn btn-secondary form-check-label {{ !empty($data->activo) || old('activo') ? 'active':''}}">
				{{Form::checkbox('activo',true,old('activo'),['id'=>'activo',Route::currentRouteNamed(currentRouteName('show'))?'disabled':''])}}
				Activo
			</label>
		</div>
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