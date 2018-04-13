@extends(smart())
@section('content-width')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Aplicaciones de medicamento')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nueva aplicación de medicamento')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar aplicación de medicamento')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Aplicación de medicamento')
@endif

@section('form-content')
	{{ Form::setModel($data) }}
    <div class="row">
        <div class="form-group col-md-12 col-xs-12">
            {{ Form::cText('* Aplicación','aplicacion') }}
        </div>
        <div  class="col-md-12 text-center mt-4">
            <div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
        </div>
    </div>
@endsection