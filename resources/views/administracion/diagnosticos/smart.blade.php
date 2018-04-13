@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-2 col-xs-12">
    		{{ Form::cText('* Clave','clave_diagnostico') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('* Diagnostico','diagnostico') }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cText('Medicamento Sugerido','medicamento_sugerido') }}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{ Form::cSelect('* Empresa cliente', 'fk_id_cliente', $clientes ?? [],['class'=>'select2']) }}
		</div>
    	<div  class="col-md-12 text-center mt-2">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection