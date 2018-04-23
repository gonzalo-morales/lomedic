@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-6">
    		{{ Form::cText('* Impuesto', 'impuesto',['class'=>'form-control']) }}
    	</div>
    	<div class="form-group col-md-6">
    		{{ Form::cNumber('* Porcentaje', 'porcentaje', ['max'=>'100','min'=>'0','placeholder' => 'Ejemplo: 16']) }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection