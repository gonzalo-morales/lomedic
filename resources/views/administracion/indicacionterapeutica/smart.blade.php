@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            {{ Form::label('indicacion_terapeutica', 'Indicacion Terapeutica') }}
            {{ Form::text('indicacion_terapeutica', null, ['id'=>'indicacion_terapeutica','class'=>'form-control']) }}
            {{ $errors->has('indicacion_terapeutica') ? HTML::tag('span', $errors->first('indicacion_terapeutica'), ['class'=>'help-block deep-orange-text']) : '' }}
        </div>
        <div class="form-group col-md-6 col-xs-12">
            {{ Form::label('descripcion', 'Descripcion') }}
            {{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
            {{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
        </div>
        <div  class="col-md-12 text-center mt-2">
            <div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
        </div>
    </div>
@endsection