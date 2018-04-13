@extends(smart())
@section('content-width')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Pedimentos de aduana')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nuevo pedimento de aduana')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar pedimento de aduana')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Pedimento de aduana')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-sm-6 col-md-3">
    		{{ Form::cNumber('* Número aduana:','aduana') }}
    	</div>
    	<div class="form-group col-sm-6 col-md-3">
    		{{ Form::cNumber('* Número patente:','patente') }}
    	</div>
    	<div class="form-group col-sm-6 col-md-3">
    		{{ Form::cNumber('* Número ejercicio:','ejercicio') }}
    	</div>
    	<div class="form-group col-sm-6 col-md-3">
    		{{ Form::cNumber('* Número cantidad:','cantidad') }}
    	</div>
    	<div  class="col-md-12 text-center mt-2">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection