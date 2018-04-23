@extends(smart())
@section('content-width')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Condiciones de pago')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nueva condición de pago')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar condición de pago')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Condición de pago')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('* Condición pago','condicion_pago') }}
    	</div>
    	<div  class="col-md-8 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection