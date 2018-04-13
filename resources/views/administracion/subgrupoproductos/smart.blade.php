@extends(smart())
@section('content-width')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Subgrupos de productos')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nuevo subgrupo de producto')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar subgrupo de producto')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Subgrupo de producto')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-6 col-xs-12">
    		{{ Form::cText('* Subgrupo','subgrupo') }}
    	</div>
    	<div class="form-group col-md-6 col-xs-12">
    		{{ Form::cSelect('* Grupo', 'fk_id_grupo', $groups ?? []) }}
    	</div>
    	<div  class="col-md-12 col-xs-12 text-center mt-2">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection