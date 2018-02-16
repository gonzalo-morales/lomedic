@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            {{ Form::cText('* Forma Farmaceutica','forma_farmaceutica') }}
        </div>
        <div class="form-group col-md-6 col-xs-12">
            {{ Form::cText('Descripcion','descripcion') }}
        </div>
        <div  class="col-md-12 text-center mt-2">
            <div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
        </div>
    </div>
@endsection