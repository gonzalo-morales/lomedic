@extends(smart())
@section('content-width')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Sustancias activas')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nuevo sustancia activa')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar sustancia activa')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Sustancia activa')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-10 col-xs-12">
    		{{ Form::cText('Sustancia Activa','sustancia_activa') }}
    	</div>
    	<div class="form-group col-md-2 col-xs-12 d-flex align-items-center">
    		<input type="hidden" value="0" name="opcion_gramaje">
    		{{ Form::cCheckbox('¿Gramaje?','opcion_gramaje') }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection