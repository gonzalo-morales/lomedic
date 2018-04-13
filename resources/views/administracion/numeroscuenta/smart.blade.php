@extends(smart())
@section('content-width', 's12')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Números de cuenta')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nuevo número de cuenta')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar número de cuenta')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Número de cuenta')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-12 col-xs-12">
    		{{ Form::cText('Número de cuenta','numero_cuenta') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cSelect('Banco','fk_id_banco', $bancos ?? []) }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cSelect('Moneda','fk_id_sat_moneda',$monedas ?? []) }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cSelect('Empresa','fk_id_empresa',$companies ?? []) }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection