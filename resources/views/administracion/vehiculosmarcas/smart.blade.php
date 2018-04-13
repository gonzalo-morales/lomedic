@extends(smart())
@section('content-width', 's12')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Marcas de vehículos')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nueva marca de vehículo')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar marca de vehículo')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Marca de vehículo')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
        <div class="form-group col-md-12 col-xs-12">
            {{ Form::cText('* Marca','marca') }}
        </div>
        <div  class="col-md-12 text-center mt-4">
            <div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
        </div>
    </div>
@endsection