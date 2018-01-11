@extends(smart())
@section('content-width', 's12 ml2')

@section('header-bottom')
	@parent
@endsection

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-12 col-xs-12">
    		{{ Form::label('subcategoria', '* Subcategoria') }}
    		<div class="input-group">
    		{{ Form::text('subcategoria', null, ['id'=>'subcategoria','class'=>'form-control']) }}
    			<div class="input-group-addon">
    		{{ Form::select('fk_id_categoria', isset($categories) ? $categories : [], old('fk_id_categoria'),['id'=>'fk_id_categoria_','class'=>'form-control']) }}
    			</div>
    		</div>
    		{{ $errors->has('subcategoria') ? HTML::tag('span', $errors->first('subcategoria'), ['class'=>'help-block deep-orange-text']) : '' }}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection