@extends(smart())

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cText('* Cuenta','cuenta') }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cText('* Nombre','nombre') }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
            {{ Form::cSelectWithDisabled('Tipo','tipo', $tipos_cuentas ?? [],['class' =>'select2']) }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cSelectWithDisabled('Cuenta Mayor','cuenta_mayor', $cuentas_mayor ?? [],['class' =>'select2']) }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
            {{ Form::cSelectWithDisabled('Subcuenta de','fk_id_cuenta_padre', $cuentas_padre ?? [],['class' =>'select2']) }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cSelectWithDisabled('* Agrupador Cuenta SAT','fk_id_agrupador_cuenta', $agrupadores_cuentas ?? [],['class' =>'select2']) }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cCheckboxBtn('Afectable','Si','afectable', $data['afectable'] ?? null, 'No') }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cCheckboxBtn('Es Cuenta Efectivo','Si','cuenta_efectivo', $data['cuenta_efectivo'] ?? null, 'No') }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection