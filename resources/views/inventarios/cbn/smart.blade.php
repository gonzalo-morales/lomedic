
@section('content-width', 's12')
@section('header-bottom')
	@parent
	<script type="text/javascript">
        $('#vigencia').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' })
	</script>
@endsection

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
    		{{ Form::cText('Vigencia', 'vigencia') }}
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
            {{ Form::cCheckboxBtn('','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
@include('layouts.smart.show')
@endif

@if (Route::currentRouteNamed(currentRouteName('export')))
@include('layouts.smart.export')
@endif