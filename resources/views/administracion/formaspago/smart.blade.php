@extends(smart())
@section('content-width', 's12')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Formas de pago')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nueva forma de pago')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar forma de pago')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Forma de pago')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-sm-12 col-md-6">
    		{{ Form::cNumber('* Número forma de pago','forma_pago') }}
    	</div>
    	<div class="form-group col-sm-12 col-md-6">
    		{{ Form::cText('* Descripción','descripcion') }}
    	</div>
    	<div  class="col-12 text-center mt-2">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection