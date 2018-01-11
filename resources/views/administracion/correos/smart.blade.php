@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('Correo:','correo') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::label('fk_id_empresa', 'Empresa:') }}
    		{{ Form::cSelect('Empresa:','fk_id_empresa', $companies ?? []) }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cSelect('Usuario:','fk_id_usuario', $users ?? []) }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection