@extends(smart())
@section('content-width')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Clasificaciones de proyectos')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nueva clasificación de proyecto')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar clasificación de proyecto')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Clasificación de proyecto')
@endif

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-6">
		{{Form::cText('* Clasificación','clasificacion',['id'=>'clasificacion'])}}
	</div>
	<div class="form-group col-md-6">
		{{Form::cText('* Nomenclatura','nomenclatura',['id'=>'nomenclatura'])}}
	</div>
	<div  class="col-md-12 text-center mt-4">
		<div class="alert alert-warning" role="alert">
			Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrará en los modulos correspondientes que se requieran.
		</div>
			{{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
	</div>
</div>
@endsection