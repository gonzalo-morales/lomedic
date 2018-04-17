@extends(smart())
@section('content-width')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Localidades')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nueva localidad')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar localidad')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Localidad')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col">
    		{{ Form::cText('* Localidad', 'localidad') }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection