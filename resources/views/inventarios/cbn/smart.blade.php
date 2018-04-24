@extends(smart())

@index
	@section('title', 'Cuadro Basico Nacional')
@endif

@section('form-content')
	{{ Form::setModel($data) }}
    <div class="row">
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::cText('Clave', 'clave_cbn') }}
    		</div>
    	</div>
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::cText('Descripcion', 'descripcion') }}
    		</div>
    	</div>
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		{{ Form::cText('Vigencia', 'vigencia',['class'=>'datepicker']) }}
    	</div>
    	
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::label('precio_comision', 'Precio Comision') }}
                {{ Form::Number('precio_comision', null, ['id' => 'precio_comision', 'class' => 'form-control']) }}
                {{ $errors->has('precio_comision') ? HTML::tag('span', $errors->first('precio_comision'), ['class'=>'help-block error-help-block']) : '' }}
    		</div>
    	</div>
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::label('precio_causes', 'Precio Causes') }}
                {{ Form::Number('precio_causes', null, ['id' => 'precio_causes', 'class' => 'form-control']) }}
                {{ $errors->has('precio_causes') ? HTML::tag('span', $errors->first('precio_causes'), ['class'=>'help-block error-help-block']) : '' }}
    		</div>
    	</div>
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::label('precio_imss', 'Precio Imss') }}
                {{ Form::Number('precio_imss', null, ['id' => 'precio_imss', 'class' => 'form-control']) }}
                {{ $errors->has('precio_imss') ? HTML::tag('span', $errors->first('precio_imss'), ['class'=>'help-block error-help-block']) : '' }}
    		</div>
    	</div>
    	
    	<div class="col-sm-12 text-center">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection