@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-12">
    		{{Form::cText('Tipo proyecto','tipo_proyecto',['id'=>'tipo_proyecto'])}}
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
    			Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrará en los modulos correspondientes que se requieran.
    		</div>
    		<div data-toggle="buttons">
    			<label class="btn btn-secondary form-check-label {{ !empty($data->activo) || old('activo') ? 'active':''}}">
    				{{Form::checkbox('activo',true,old('activo'),['id'=>'activo',Route::currentRouteNamed(currentRouteName('show'))?'disabled':''])}}
    				Activo
    			</label>
    		</div>
    	</div>
    </div>
@endsection