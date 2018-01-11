@extends(smart())
@section('content-width', 's12 m7 xl8 offset-xl2')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-11 col-xs-12">
    		{{ Form::label('modo_contacto', '* Modo Contacto') }}
    		{{ Form::text('modo_contacto', null, ['id'=>'modo_contacto','class'=>'form-control']) }}
    		{{ $errors->has('modo_contacto') ? HTML::tag('span', $errors->first('modo_contacto'), ['class'=>'help-block deep-orange-text']) : '' }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection