@extends(smart())
@section('content-width', 's12')

@notroute(['index'])
    @section('form-content')
        {{ Form::setModel($data) }}
        <div class="row">
        	<div class="form-group col-4">
        		{{ Form::cText('* Abreviatura de la moneda','moneda') }}
        	</div>
        	<div class="form-group col-4">
        		{{ Form::cNumber('* Cantidad de decimales','total_decimales') }}
        	</div>
        	<div class="form-group col-4">
        		{{ Form::cNumber('* Porcentaje de variacion','porcentaje_variacion') }}
        	</div>
        	<div class="form-group col-12">
        		{{ Form::cText('* Descripción','descripcion') }}
        	</div>
        	<div  class="col-md-12 text-center mt-2">
        		<div class="alert alert-warning" role="alert">
                    Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
                </div>
                {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
        	</div>
        </div>
    @endsection>
@endif