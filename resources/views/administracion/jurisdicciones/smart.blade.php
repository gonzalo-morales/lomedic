@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
        <div class="form-group col-md-12 col-xs-12">
            {{ Form::label('jurisdiccion', 'Jurisdicción') }}
            {{ Form::text('jurisdiccion', null, ['id'=>'jurisdiccion','class'=>'form-control']) }}
            {{ $errors->has('jurisdiccion') ? HTML::tag('span', $errors->first('jurisdiccion'), ['class'=>'help-block deep-orange-text']) : '' }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12 col-xs-12">
            {{ Form::label('fk_id_estado', 'Estado') }}
            {{ Form::select('fk_id_estado', isset($states)?$states:[],null, ['id'=>'fk_id_estado','class'=>'form-control']) }}
            {{ $errors->has('fk_id_estado') ? HTML::tag('span', $errors->first('fk_id_estado'), ['class'=>'help-block deep-orange-text']) : '' }}
        </div>
    </div>
    <div  class="col-md-12 text-center mt-2">
        <div class="alert alert-warning" role="alert">
            Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
        </div>
        {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    </div>
@endsection