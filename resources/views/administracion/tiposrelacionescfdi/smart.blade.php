@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
        <div class="col-md-10 row">
            <div class="col-md-4">
            	{{ Form::cText('* Tipo Relacion','tipo_relacion') }}
            </div>
            <div class="col-md-8">
            	{{ Form::cText('* Descripción','descripcion') }}
            </div>
        	<div  class="col-md-12 text-center mt-2">
                <div class="alert alert-warning" role="alert">
                    Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
                </div>
                {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
            </div>
        </div>
        <div class="col-md-2 card z-depth-1-half">
			<h5 class="card-header row">Disponible para:</h5>
			<div class="card-body row">
                <div class="col-md-12">
                	{{ Form::cCheckbox('Factura','factura') }}
                </div>
                <div class="col-md-12">
                	{{ Form::cCheckbox('Nota Credito','nota_credito') }}
                </div>
                <div class="col-md-12">
                	{{ Form::cCheckbox('Nota Cargo','nota_cargo') }}
                </div>
            </div>
        </div>
        
    </div>
@endsection