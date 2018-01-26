@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-2 col-xs-12">
    		{{ Form::cText('* Cedula','cedula') }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cText('* Apellido Paterno','paterno') }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cText('Apellido Materno','materno') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('* Nombre(s)','nombre') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('RFC','rfc') }}
    	</div>
    	<div  class="col-md-8 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection