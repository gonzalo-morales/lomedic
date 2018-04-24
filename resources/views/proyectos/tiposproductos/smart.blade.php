@extends(smart())

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-6">
    		{{Form::cText('Descripcion','descripcion',['id'=>'descripcion'])}}
    	</div>
    	<div class="form-group col-md-6">
    		{{Form::cSelectWithDisabled('Proyecto','fk_id_proyecto',isset($proyectos)?$proyectos:[])}}
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