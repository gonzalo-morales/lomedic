@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            {{ Form::label('descripcion', 'Familia') }}
            {{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
            {{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
        </div>
        <div class="form-group col-md-6 col-xs-12">
            {{ Form::label('nomenclatura', 'Nomenclatura') }}
            {{ Form::text('nomenclatura', null, ['id'=>'nomenclatura','class'=>'form-control']) }}
            {{ $errors->has('nomenclatura') ? HTML::tag('span', $errors->first('nomenclatura'), ['class'=>'help-block deep-orange-text']) : '' }}
        </div>
        <div class="form-group col-md-6 col-xs-12">
            {{ Form::label('fk_id_tipo_producto', 'Tipo') }}
            {{Form::select('fk_id_tipo_producto',isset($product_types)?$product_types:[],null,['id'=>'fk_id_tipo_producto','class'=>'form-control'])}}
            {{ $errors->has('tipo') ? HTML::tag('span', $errors->first('tipo'), ['class'=>'help-block deep-orange-text']) : '' }}
        </div>
        <div class="form-group col-md-6 col-xs-12">
            {{ Form::label('tipo_presentacion', 'Presentacion') }}
            {{ Form::select('tipo_presentacion',
            ['1'=>'Cantidad',
            '2'=>'Cantidad y Unidad',
            '3'=>'Ampolletas (Ã�mpulas)',
            '4'=>'Dosis'],
            null, ['id'=>'tipo_presentacion','class'=>'form-control']) }}
            {{ $errors->has('tipo_presentacion') ? HTML::tag('span', $errors->first('tipo_presentacion'), ['class'=>'help-block deep-orange-text']) : '' }}
        </div>
        <div  class="col-md-12 text-center mt-2">
            <div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
        </div>
    </div>
@endsection