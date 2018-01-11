@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-12 col-xs-12">
    		{{ Form::cText('* Motivo de devolución','devolucion_motivo') }}
    	</div>
    	<div  class="col-md-4 text-center">
    		{{ Form::cRadio('Solicitante Devolucion','solicitante_devolucion',[0=>'Sucursal',1=>'Proveedor']) }}
    	</div>
    	<div  class="col-md-8 text-center mt-2">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection