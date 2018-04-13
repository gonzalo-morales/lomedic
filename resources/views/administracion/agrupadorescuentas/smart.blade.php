@extends(smart())
@section('content-width', 's12')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Agrupadores de cuentas')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nuevo agrupador de cuenta')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar agrupador de cuenta')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Agrupador de cuenta')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-sm-12 col-md-6">
    		{{ Form::cText('* Codigo Agrupador','codigo_agrupador') }}
    	</div>
    	<div class="form-group col-sm-12 col-md-6">
    		{{ Form::cText('* Nombre Cuenta','nombre_cuenta') }}
    	</div>
    	<div  class="col-12 text-center mt-2">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection