@extends(smart())
@section('content-width', 's12')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Grupos de productos')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nuevo grupo de productos')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar grupo de productos')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Grupo de productos')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-6">
    		{{ Form::cText('* Grupo','grupo') }}
    	</div>
    	<div  class="col-md-6 text-center mt-2">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection