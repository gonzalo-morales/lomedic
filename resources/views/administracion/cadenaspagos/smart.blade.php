@extends(smart())
@section('content-width')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-6 col-xs-6">
		{{ Form::cText('Cadena pago:','cadena_pago') }}
	</div>
	<div class="form-group col-md-6 col-xs-6">
		{{ Form::cText('Descripción:','descripcion') }}
	</div>
	<div  class="col-md-12 text-center mt-2">
		<div class="alert alert-warning" role="alert">
            Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
        </div>
        {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
	</div>
</div>
@endsection