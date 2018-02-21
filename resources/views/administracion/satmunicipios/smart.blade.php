@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-6 col-sm-6 col-md-3">
    		{{ Form::cNumber('* Código municipio','sat_municipio') }}
    	</div>
    	<div class="form-group col-6 col-sm-6 col-md-3">
    		{{ Form::cText('* Abreviatura estado','sat_estado') }}
    	</div>
    	<div class="form-group col-12 col-sm-12 col-md-6">
    		{{ Form::cText('* Municipio','municipio') }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection