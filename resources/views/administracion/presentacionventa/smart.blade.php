@extends(smart())
@section('content-width', 's12')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Presentaciones de venta')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nueva presentación de venta')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar presentación de venta')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Presentación de venta')
@endif

@section('form-content')
    {{Form::setModel($data)}}
    <div class="row">
        <div class="form-group col-md-12 col-xs-12">
            {{ Form::cText('Presentacion Venta','presentacion_venta') }}
        </div>
        <div  class="col-md-12 text-center mt-2">
            <div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
        </div>
    </div>
@endsection