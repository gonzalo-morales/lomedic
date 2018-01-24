@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('* Tipo Socio Negocio','tipo_socio') }}
    	</div>
    	<div class="form-group col-md-2 col-xs-12">
    		{{ Form::cRadio('* Tipo','para_venta', [0=>'Compra',1=>'Venta']) }}
    	</div>
    	<div  class="col-md-6 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection