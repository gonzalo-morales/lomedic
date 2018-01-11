@extends(smart())
@section('content-width', 's12 m7 xl8 offset-xl2')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-6 col-xs-12">
    		{{ Form::label('accion', '* Acción') }}
    		{{ Form::text('accion', null, ['id'=>'accion','class'=>'form-control']) }}
    		{{ $errors->has('accion') ? HTML::tag('span', $errors->first('accion'), ['class'=>'help-block deep-orange-text']) : '' }}
    	</div>
    	<div class="form-group col-md-6 col-xs-12">
    		{{ Form::label('fk_id_subcategoria', '* Subcategoria') }}
    		{{ Form::select('fk_id_subcategoria', (isset($subcategorys) ? $subcategorys : []),null, ['id'=>'fk_id_subcategoria','class'=>'form-control']) }}
    		{{ $errors->has('fk_id_subcategoria') ? HTML::tag('span', $errors->first('fk_id_subcategoria'), ['class'=>'help-block deep-orange-text']) : '' }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection