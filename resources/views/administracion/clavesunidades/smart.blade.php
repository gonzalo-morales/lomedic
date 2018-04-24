@extends(smart())

@section('form-content')
	{{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-12 col-md-6">
    		{{ Form::cText('* Clave Unidad','clave_unidad') }}
    	</div>
    	<div class="form-group col-12 col-md-6">
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